<?php

namespace Xima\XmFormcycle\Service;

use finfo;
use JsonException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
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
        private readonly FrontendInterface $cache
    ) {
        $extConfig = $this->extensionConfiguration->get('xm_formcycle');
        $this->configuration = FormcycleConfiguration::createFromExtensionConfiguration($extConfig);
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

        $index = array_search((int)$formId, array_column($forms, 'form_id'), true);

        return $index !== false ? $forms[$index] : [];
    }
}
