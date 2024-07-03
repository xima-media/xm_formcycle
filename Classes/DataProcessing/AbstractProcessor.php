<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Service\FormcycleService;

abstract class AbstractProcessor implements DataProcessorInterface
{
    protected ElementSettings $settings;

    protected FormcycleService $formcycleService;

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        // construct element settings
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $this->settings = ElementSettings::createFromContentElement(
            $flexFormService,
            $cObj,
        );

        $this->formcycleService = GeneralUtility::makeInstance(FormcycleService::class);

        // check if concrete processor should be used
        $currentIntegrationMode = $this->settings->integrationMode ?? $this->formcycleService->getDefaultIntegrationMode();
        if ($currentIntegrationMode->forDataProcessing() !== $this->getIntegrationMode()) {
            return $processedData;
        }

        return $this->subProcess($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
    }

    abstract protected function getIntegrationMode(): IntegrationMode;

    abstract public function subProcess(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array;
}
