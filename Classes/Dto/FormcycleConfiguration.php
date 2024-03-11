<?php

namespace Xima\XmFormcycle\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

final class FormcycleConfiguration
{
    private string $formCycleUrl;

    private IntegrationMode $integrationMode;

    private string $formcycleClientId;

    /**
     * @throws FormcycleConfigurationException
     */
    public static function createFromExtensionConfiguration(array $extConfiguration): self
    {
        $config = new self();
        $config->formCycleUrl = rtrim($extConfiguration['formcycleUrl'] ?? '', '/');
        if (!$config->formCycleUrl || !GeneralUtility::isValidUrl($config->formCycleUrl)) {
            throw new FormcycleConfigurationException(
                'Invalid formcycleUrl "' . $config->formCycleUrl . '"',
                1709041996
            );
        }

        $config->formcycleClientId = $extConfiguration['formcycleClientId'] ?? '';
        if (!$config->formcycleClientId) {
            throw new FormcycleConfigurationException('No formcycleClientId set', 1709538688);
        }

        $config->integrationMode = IntegrationMode::tryFrom($extConfiguration['integrationMode'] ?? '') ?? IntegrationMode::Integrated;

        return $config;
    }

    public function getFormListUrl(): string
    {
        return sprintf(
            '%s/plugin?name=FormListJson&xfc-rp-client=%s',
            $this->formCycleUrl,
            $this->formcycleClientId,
        );
    }

    public function getFormCycleUrl(): string
    {
        return $this->formCycleUrl;
    }

    public function getAdminUrl(): string
    {
        return $this->formCycleUrl;
    }

    public function getIntegrationMode(): IntegrationMode
    {
        return $this->integrationMode;
    }
}
