<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Service\FormcycleService;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

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
        $this->settings = ElementSettings::createFromContentElement($cObj);

        $this->formcycleService = GeneralUtility::makeInstance(FormcycleServiceFactory::class)->createFromPageUid($cObj->data['pid']);

        // check if integration mode is set
        if ($this->settings->integrationMode === IntegrationMode::Default) {
            $this->settings->integrationMode = $this->formcycleService->getDefaultIntegrationMode();
        }

        // check if concrete processor should be used
        if ($this->settings->integrationMode->forDataProcessing() !== $this->getIntegrationMode()) {
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
