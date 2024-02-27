<?php

namespace Xima\XmFormcycle\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

final class FormcycleService
{
    private ?FormcycleConfiguration $configuration = null;

    private string $error = '';

    public function __construct(private readonly ExtensionConfiguration $extensionConfiguration)
    {
        try {
            $extConfig = $this->extensionConfiguration->get('xm_formcycle');
            $this->configuration = new FormcycleConfiguration($extConfig);
        } catch (FormcycleConfigurationException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getAvailableForms(): array
    {
        if (!$this->configuration) {
            return [];
        }

        $jsonResponse = GeneralUtility::getUrl($this->configuration->getFormListUrl());

        return json_decode($jsonResponse);
    }
}
