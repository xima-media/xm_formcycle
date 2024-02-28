<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class IntegratedProcessor extends AbstractProcessor
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        ?array $processedData
    ) {
        $this->initElementSettings($cObj);

        if ($this->settings->integrationMode !== IntegrationMode::Integrated) {
            return $processedData;
        }

        return $processedData;
    }
}
