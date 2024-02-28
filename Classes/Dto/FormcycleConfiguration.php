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

    private string $client = '24871';

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
        $config->formCyclePass = $extConfiguration['formCyclePass'] ?? '';
        if (!$config->formCycleUser || !$config->formCyclePass) {
            throw new FormcycleConfigurationException('No formCycleUser or formCyclePass set', 1709052037);
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
            $this->client,
        );
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
