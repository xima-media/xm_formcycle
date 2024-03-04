<?php

namespace Xima\XmFormcycle\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

final class FormcycleConfiguration
{
    private string $formCycleUrl;

    private string $formCycleFrontendUrl;

    private string $formCycleUser;

    private string $formCyclePass;

    private IntegrationMode $integrationMode;

    private string $formCycleClientId;

    /**
     * @throws FormcycleConfigurationException
     */
    public static function createFromExtensionConfiguration(array $extConfiguration): self
    {
        $config = new self();
        $config->formCycleUrl = rtrim($extConfiguration['formCycleUrl'] ?? '', '/');
        if (!$config->formCycleUrl || !GeneralUtility::isValidUrl($config->formCycleUrl)) {
            throw new FormcycleConfigurationException(
                'Invalid formCycleUrl "' . $config->formCycleUrl . '"',
                1709041996
            );
        }

        $config->formCycleUser = $extConfiguration['formCycleUser'] ?? '';
        if (!$config->formCycleUser) {
            throw new FormcycleConfigurationException('No formCycleUser set', 1709052037);
        }

        $config->formCyclePass = $extConfiguration['formCyclePass'] ?? '';
        if (!$config->formCyclePass) {
            throw new FormcycleConfigurationException('No formCyclePass set', 1709538727);
        }

        $config->formCycleClientId = $extConfiguration['formCycleClientId'] ?? '';
        if (!$config->formCycleClientId) {
            throw new FormcycleConfigurationException('No formCycleClientId set', 1709538688);
        }

        $config->integrationMode = IntegrationMode::tryFrom($extConfiguration['integrationMode'] ?? '') ?? IntegrationMode::Integrated;

        $config->formCycleFrontendUrl = $extConfiguration['formCycleFrontendUrl'] ?? '';
        if ($config->formCycleFrontendUrl && !GeneralUtility::isValidUrl($config->formCycleFrontendUrl)) {
            throw new FormcycleConfigurationException(
                'Invalid formCycleFrontendUrl "' . $config->formCycleFrontendUrl . '"',
                1709052152
            );
        }

        return $config;
    }

    public function getFormListUrl(): string
    {
        return sprintf(
            '%s/plugin?name=FormList&xfc-rp-username=%s&xfc-rp-password=%s&xfc-rp-client=%s&format=json',
            $this->formCycleUrl,
            $this->formCycleUser,
            $this->formCyclePass,
            $this->formCycleClientId,
        );
    }

    public function getFormCycleUrl(): string
    {
        return $this->formCycleUrl;
    }

    public function getAdminUrl(): string
    {
        return $this->formCycleFrontendUrl ?: $this->formCycleUrl;
    }

    public function getIntegrationMode(): IntegrationMode
    {
        return $this->integrationMode;
    }
}
