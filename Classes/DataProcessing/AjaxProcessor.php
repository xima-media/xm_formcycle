<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class AjaxProcessor extends AbstractProcessor
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData = []
    ) {
        $this->initElementSettings($cObj);

        if ($this->settings->integrationMode !== IntegrationMode::Ajax) {
            return $processedData;
        }

        return $processedData;
    }
}
