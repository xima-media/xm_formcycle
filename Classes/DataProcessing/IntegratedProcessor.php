<?php

namespace Xima\XmFormcycle\DataProcessing;

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

        $processedData['form'] = [];
        $processedData['form']['html'] = $form;

        return $processedData;
    }

    protected function getIntegrationMode(): IntegrationMode
    {
        return IntegrationMode::Integrated;
    }
}
