<?php

namespace Xima\XmFormcycle\Service;

use finfo;
use JsonException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;

final class FormcycleService
{
    private ?FormcycleConfiguration $configuration;

    /**
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws FormcycleConfigurationException
     */
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly FrontendInterface $cache,
        private readonly UriBuilder $uriBuilder
    ) {
        $extConfig = $this->extensionConfiguration->get('xm_formcycle');
        $this->configuration = FormcycleConfiguration::createFromExtensionConfiguration($extConfig);
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
        if ($forms === false) {
            $forms = $this->loadAvailableForms();
        }

        return $forms;
    }

    /**
     * @throws FormcycleConnectionException
     */
    private function loadAvailableForms(): array
    {
        $jsonResponse = GeneralUtility::getUrl($this->configuration->getFormListUrl());

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

    public function getAdminUrl(): string
    {
        return $this->configuration->getAdminUrl();
    }

    public function getIframeUrl(ElementSettings $settings): string
    {
        $url = sprintf('%s/form/provide/%s', $this->configuration->getFormCycleUrl(), $settings->formId);

        $params = $this->getCommonQueryParams($settings);
        $params['xfc-height-changed-evt'] = true;

        return $url . '?' . http_build_query($params);
    }

    public function getAjaxUrl(ElementSettings $settings): string
    {
        $url = sprintf('%s/form/provide/%s', $this->configuration->getFormCycleUrl(), $settings->formId);

        $params = $this->getCommonQueryParams($settings);
        $params['xfc-rp-form-only'] = true;

        return $url . '?' . http_build_query($params);
    }

    private function getCommonQueryParams(ElementSettings $settings): array
    {
        $params = [
            'xfc-rp-inline' => true,
            'xfc-rp-usejq' => $settings->loadFormcycleJquery ? 1 : 0,
            'xfc-rp-useui' => $settings->loadFormcycleJqueryUi ? 1 : 0,
            'xfc-rp-usebs' => $settings->loadResponseJs ? 1 : 0,
            'xfc-pp-external' => true,
            'xfc-pp-base-url' => $this->configuration->getFormCycleUrl(),
            'xfc-rp-keepalive' => true,
        ];

        if ($settings->successPid) {
            $url = $this->uriBuilder
                ->setTargetPageUid($settings->successPid)
                ->setCreateAbsoluteUri(true)
                ->build();
            $params['xfc-pp-success-url'] = $url;
        }

        if ($settings->errorPid) {
            $url = $this->uriBuilder
                ->setTargetPageUid($settings->errorPid)
                ->setCreateAbsoluteUri(true)
                ->build();
            $params['xfc-pp-error-url'] = $url;
        }

        return $params;
    }

    public function getDefaultIntegrationMode(): IntegrationMode
    {
        return $this->configuration->getIntegrationMode();
    }
}
