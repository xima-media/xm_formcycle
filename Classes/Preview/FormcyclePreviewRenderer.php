<?php

namespace Xima\XmFormcycle\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Service\FormcycleService;

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

        if (!$row['tx_xmformcycle_form_id'] || !is_string($row['tx_xmformcycle_form_id'])) {
            $content = 'No form selected';
            return $this->linkEditContent($content, $row);
        }

        try {
            /** @var FormcycleService $formcycleService */
            $formcycleService = GeneralUtility::makeInstance(FormcycleService::class);
        } catch (FormcycleConfigurationException $e) {
            $content = 'Formcycle extension configuration error';
            return $this->linkEditContent($content, $row);
        }

        $cacheExists = $formcycleService->hasAvailableFormsCached();
        $formConfiguration = $cacheExists ? $formcycleService->getAvailableFormConfigurationByFormId($row['tx_xmformcycle_form_id']) : [];
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

        return $this->linkEditContent($content, $row);
    }
}
