<?php

namespace Xima\XmFormcycle\Service;

use finfo;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;

final readonly class FormcycleService
{
    private string $url;

    private string $clientId;

    private IntegrationMode $defaultIntegrationMode;

    /**
     * @throws NotFoundExceptionInterface
     * @throws FormcycleConfigurationException
     * @throws ContainerExceptionInterface
     */
    public function __construct(private FrontendInterface $cache, private Site $site)
    {
        $this->url = rtrim($site->getSettings()->get('formcycle.url'), '/');
        $this->clientId = $site->getSettings()->get('formcycle.clientId');
        $this->defaultIntegrationMode = IntegrationMode::fromSiteSettings($site->getSettings()->get('formcycle.defaultIntegrationMode'));

        if (!$this->url || !GeneralUtility::isValidUrl($this->url)) {
            throw new FormcycleConfigurationException('Formcycle URL is not configured', 1734275643);
        }

        if (!$this->clientId) {
            throw new FormcycleConfigurationException('Formcycle Client ID is not configured', 1734275657);
        }
    }

    public function hasAvailableFormsCached(): bool
    {
        return $this->cache->has('availableForms');
    }

    public function resetAvailableFormsCache(): void
    {
        $this->cache->remove('availableForms');
    }

    public function getAvailableFormConfigurationByFormId(string $formId): array
    {
        $forms = $this->getAvailableForms();

        $index = array_search((int)$formId, array_column($forms, 'id'), true);

        return $index !== false ? $forms[$index] : [];
    }

    /**
     * @throws FormcycleConnectionException
     */
    public function getAvailableForms(): array
    {
        $forms = $this->cache->get('availableForms');
        if (!$forms) {
            $forms = $this->loadAvailableForms();
        }

        return $forms;
    }

    /**
     * @throws FormcycleConnectionException
     */
    private function loadAvailableForms(): array
    {
        $jsonResponse = GeneralUtility::getUrl($this->getFormListUrl());

        if (!$jsonResponse) {
            throw new FormcycleConnectionException('Loading available forms: No response of endpoint', 1709102526);
        }

        try {
            $forms = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new FormcycleConnectionException(
                'Loading available forms: Invalid JSON response of endpoint',
                1709102526
            );
        }

        if (!is_array($forms)) {
            throw new FormcycleConnectionException(
                'Loading available forms: Invalid JSON response of endpoint',
                1709102526
            );
        }

        self::encodePreviewImages($forms);

        $this->cache->set('availableForms', $forms);

        return $forms;
    }

    public function getFormListUrl(): string
    {
        return sprintf(
            '%s/plugin?name=FormListJson&xfc-rp-client=%s',
            $this->url,
            $this->clientId,
        );
    }

    private static function encodePreviewImages(array &$forms): void
    {
        foreach ($forms as &$form) {
            $thumbnail = $form['thumbnail'] ?? '';
            if ($thumbnail && GeneralUtility::isValidUrl($thumbnail)) {
                $imageData = GeneralUtility::getUrl($thumbnail);
                $fileInfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $fileInfo->buffer($imageData);
                $form['thumbnail'] = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            }
        }
    }

    public function getCspUrl(): string
    {
        $adminUrl = $this->getAdminUrl();
        return pathinfo($adminUrl, PATHINFO_DIRNAME);
    }

    public function getAdminUrl(): string
    {
        return $this->url;
    }

    public function getIframeUrl(ElementSettings $settings): string
    {
        $url = sprintf('%s/form/provide/%s', $this->url, $settings->formId);

        $params = $this->getCommonQueryParams($settings);
        $params['xfc-height-changed-evt'] = true;
        $params['xfc-rp-usejq'] = 1;
        $params['xfc-rp-useui'] = 1;

        return $url . '?' . http_build_query($params);
    }

    private function getCommonQueryParams(ElementSettings $settings): array
    {
        $params = [
            'xfc-rp-inline' => true,
            'xfc-rp-usejq' => 0,
            'xfc-rp-useui' => 0,
            'xfc-rp-usebs' => 1,
            'xfc-pp-external' => true,
            'xfc-pp-base-url' => '',
            'xfc-pp-use-base-url' => false,
            'xfc-rp-keepalive' => true,
            'lang' => $settings->language ?: 'de',
        ];

        if ($settings->successPid) {
            $params['xfc-pp-success-url'] = $this->generateAbsoluteUri($settings->successPid, $settings->language);
        }

        if ($settings->errorPid) {
            $params['xfc-pp-error-url'] = $this->generateAbsoluteUri($settings->errorPid, $settings->language);
        }

        return $params;
    }

    protected function generateAbsoluteUri(int $pageUid, string $language): string
    {
        $uri = $this->site->getRouter()->generateUri(
            $pageUid,
            ['_language' => $language]
        );
        // fix for sites without absolute uri
        if (empty($uri->getHost()) || empty($uri->getScheme())) {
            $uri = $uri->withHost((string)GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'));
            $uri = $uri->withScheme('https');
        }
        return (string)$uri;
    }

    public function getAjaxUrl(ElementSettings $settings): string
    {
        if ($settings->integrationMode === IntegrationMode::AjaxTypo3) {
            return '?type=1464705954&formId=' . $settings->formId;
        }

        $url = sprintf('%s/form/provide/%s', $this->url, $settings->formId);

        $params = $this->getCommonQueryParams($settings);
        $params['xfc-rp-form-only'] = true;

        return $url . '?' . http_build_query($params);
    }

    public function getDefaultIntegrationMode(): IntegrationMode
    {
        return $this->defaultIntegrationMode;
    }

    public function getFormHtml(ElementSettings $settings): string
    {
        $url = $this->getIntegratedFormUrl($settings);
        return GeneralUtility::getUrl($url) ?: '';
    }

    public function getIntegratedFormUrl(ElementSettings $settings): string
    {
        $url = sprintf('%s/form/provide/%s', $this->url, $settings->formId);

        $params = $this->getCommonQueryParams($settings);

        return $url . '?' . http_build_query($params);
    }
}
