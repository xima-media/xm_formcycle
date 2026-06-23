<?php

namespace Xima\XmFormcycle\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Service\FormcycleService;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormcyclePreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();

        if (!isset($row['tx_xmformcycle_form_id'])) {
            $content = 'Database field not found, please update database schema';
            return $this->linkEditContent($content, $row);
        }

        if ($row['tx_xmformcycle_display_mode'] == 0 && (!$row['tx_xmformcycle_form_id'] || !is_string($row['tx_xmformcycle_form_id']))) {
            $content = 'No form selected';
            return $this->linkEditContent($content, $row);
        }

        try {
            /** @var FormcycleService $formcycleService */
            $formcycleService = GeneralUtility::makeInstance(FormcycleServiceFactory::class)->createFromPageUid($row['pid']);
        } catch (\Exception) {
            $content = 'Formcycle extension configuration error';
            return $this->linkEditContent($content, $row);
        }

        if ($row['tx_xmformcycle_display_mode'] == 0) {
            try {
                $formConfiguration = $formcycleService->getAvailableFormConfigurationByFormId($row['tx_xmformcycle_form_id']);
            } catch (\Throwable) {
                $formConfiguration = null;
            }
            if (empty($formConfiguration)) {
                $content = 'Configured form ID: ' . $row['tx_xmformcycle_form_id'];
                return $this->linkEditContent($content, $row);
            }

            if ($formConfiguration['thumbnail'] ?? false) {
                $content .= '<span class="d-inline-block mb-2" style="background-color:#ededed"><img width="200" alt="Formcycle form preview" src="' . $formConfiguration['thumbnail'] . '" /></span><br />';
            }

            if ($formConfiguration['title'] ?? false) {
                $content .= $formConfiguration['title'];
            }
        } elseif ($row['tx_xmformcycle_display_mode'] == 1) {
            try {
                $formConfigurations = $formcycleService->getAvailableForms();
            } catch (\Throwable) {
                $formConfigurations = null;
            }

            if (empty($formConfigurations)) {
                $content = 'No forms found for current configuration';
                return $this->linkEditContent($content, $row);
            }

            for ($i = 0, $max = 5; $i < $max; $i++) {
                $content .= '<ul><li>' . $formConfigurations[$i]['title'] . '</li></ul>';
            }
            $content .= '<ul><li>...</li></ul>';
        }

        return $this->linkEditContent($content, $row);
    }
}
