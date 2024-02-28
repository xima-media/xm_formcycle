<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Service\FormcycleService;

abstract class AbstractProcessor implements DataProcessorInterface
{
    protected ElementSettings $settings;

    public function __construct(
        protected readonly FormcycleService $formcycleService,
        private readonly FlexFormService $flexFormService
    ) {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $this->settings = ElementSettings::createFromContentElement(
            $this->flexFormService,
            $cObj,
        );

        if ($this->settings->integrationMode !== $this->getIntegrationMode()) {
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
