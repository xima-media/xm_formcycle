<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class iFrameProcessor extends AbstractProcessor
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $this->initElementSettings($cObj);

        if ($this->settings->integrationMode !== IntegrationMode::Iframe) {
            return $processedData;
        }

        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFooterFile('EXT:xm_formcycle/Resources/Public/JavaScript/Frontend/FormcycleIframe.js');

        return $processedData;
    }
}
