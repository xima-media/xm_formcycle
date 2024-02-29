<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class AjaxProcessor extends AbstractProcessor
{
    public function subProcess(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if ($this->settings->loadFormcycleJquery) {
            $pageRenderer->addJsFooterFile('EXT:xm_formcycle/Resources/Public/JavaScript/Frontend/jquery-3.7.1.min.js');
        }
        $pageRenderer->addJsFooterFile('EXT:xm_formcycle/Resources/Public/JavaScript/Frontend/FormcycleAjax.js');

        $processedData['ajax'] = [];
        $processedData['ajax']['url'] = $this->formcycleService->getAjaxUrl($this->settings);

        return $processedData;
    }

    protected function getIntegrationMode(): IntegrationMode
    {
        return IntegrationMode::Ajax;
    }
}
