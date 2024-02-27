<?php

namespace Xima\XmFormcycle\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

final class FormcycleConfiguration
{
    public const INTEGRATION_MODE_INTEGRATED = 'integrated';
    public const INTEGRATION_MODE_IFRAME = 'iFrame';
    public const INTEGRATION_MODE_AJAX = 'AJAX';

    private string $formCycleUrl;

    private string $formCycleFrontendUrl;

    private string $formCycleUser;

    private string $formCyclePass;

    private string $integrationMode;

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

        $mode = $extConfiguration['integrationMode'] ?? '';
        $config->integrationMode = $mode ?: self::INTEGRATION_MODE_INTEGRATED;
        if (!in_array(
            $config->integrationMode,
            [self::INTEGRATION_MODE_IFRAME, self::INTEGRATION_MODE_INTEGRATED, self::INTEGRATION_MODE_AJAX],
            true
        )) {
            throw new FormcycleConfigurationException(
                'Invalid integration mode "' . $config->integrationMode . '"',
                1709052040
            );
        }

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
            '%s/plugin?name=FormList&xfc-rp-username=maik.schneider@xima.de&xfc-rp-password=pdn*UFZ3rvm0zec2ugd&xfc-rp-client=24871&format=json',
            $this->formCycleUrl
        );
    }
}
