<?php

namespace Xima\XmFormcycle\Service;

use finfo;
use JsonException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

final class FormcycleService
{
    private ?FormcycleConfiguration $configuration = null;

    private string $error = '';

    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private FrontendInterface $cache
    ) {
        try {
            $extConfig = $this->extensionConfiguration->get('xm_formcycle');
            $this->configuration = FormcycleConfiguration::createFromExtensionConfiguration($extConfig);
        } catch (FormcycleConfigurationException $e) {
            $this->error = $e->getMessage();
        }
    }

    public static function groupForms(array $forms): array
    {
        $groupedForms = [];
        foreach ($forms as $form) {
            $index = $form['group'] ?? 0;
            $groupedForms[$index] ??= [];
            $groupedForms[$index][] = $form;
        }
        // sort "others" group (index=0) to end of array
        uksort($groupedForms, static function ($a, $b) {
            return $b === 0 ? -1 : $a > $b;
        });
        return $groupedForms;
    }

    public function getAvailableForms(): array
    {
        if (!$this->configuration) {
            return [];
        }

        $forms = $this->cache->get('availableForms');
        if ($forms === false) {
            $forms = $this->loadAvailableForms();
            $this->cache->set('availableForms', $forms);
        }

        return $forms;
    }

    private function loadAvailableForms(): array
    {
        if (!$this->configuration) {
            return [];
        }

        $jsonResponse = GeneralUtility::getUrl($this->configuration->getFormListUrl());

        if (!$jsonResponse) {
            return [];
        }

        try {
            $forms = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->error = 'Invalid JSON response of available forms endpoint';
            return [];
        }

        if (!is_array($forms)) {
            $this->error = 'Invalid JSON response of available forms endpoint';
            return [];
        }

        self::encodePreviewImages($forms);

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
}
