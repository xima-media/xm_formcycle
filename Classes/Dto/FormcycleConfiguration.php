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

    private string $integrationMode;

    private string $client = '24871';

    /**
     * @throws FormcycleConfigurationException
     */
    public function __construct(array $extConfiguration)
    {
        $this->formCycleUrl = rtrim($extConfiguration['formCycleUrl'] ?? '', '/');
        if (!$this->formCycleUrl || !GeneralUtility::isValidUrl($this->formCycleUrl)) {
            throw new FormcycleConfigurationException('Invalid formCycleUrl "' . $this->formCycleUrl . '"', 1709041996);
        }

        $this->formCycleFrontendUrl = $extConfiguration['formCycleFrontendUrl'] ?? '';
        $this->formCycleUser = $extConfiguration['formCycleUser'] ?? '';
        $this->formCyclePass = $extConfiguration['formCyclePass'] ?? '';
        $this->integrationMode = $extConfiguration['integrationMode'] ?? '';
    }

    public function getFormListUrl(): string
    {
        return sprintf('%s/plugin?name=FormList&xfc-rp-username=maik.schneider@xima.de&xfc-rp-password=pdn*UFZ3rvm0zec2ugd&xfc-rp-client=24871&format=json', $this->formCycleUrl);
    }
}
