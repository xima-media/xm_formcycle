<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Xima\XmFormcycle\Dto\IntegrationMode;

class AjaxProcessor extends AbstractProcessor
{
    public function subProcess(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData = []
    ): array {
        return $processedData;
    }

    protected function getIntegrationMode(): IntegrationMode
    {
        return IntegrationMode::Ajax;
    }
}
