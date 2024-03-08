<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class IntegratedProcessor extends AbstractProcessor
{
    public function subProcess(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $form = $this->formcycleService->getFormHtml($this->settings);

        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if ($this->settings->loadFormcycleJquery) {
            $pageRenderer->addJsFile('EXT:xm_formcycle/Resources/Public/JavaScript/Frontend/jquery.min.js');
        }
        if ($this->settings->loadFormcycleJqueryUi) {
            $pageRenderer->addJsFile('EXT:xm_formcycle/Resources/Public/JavaScript/Frontend/jquery-ui.min.js');
        }

        $processedData['form'] = [];
        $processedData['form']['html'] = $form;

        return $processedData;
    }

    protected function getIntegrationMode(): IntegrationMode
    {
        return IntegrationMode::Integrated;
    }
}
