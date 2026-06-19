<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Service\FormcycleService;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormListProcessor implements DataProcessorInterface
{
    protected ElementSettings $settings;

    protected FormcycleService $formcycleService;

    public function __construct(private readonly FormcycleServiceFactory $formcycleServiceFactory)
    {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        // construct element settings
        $this->settings = ElementSettings::createFromContentElement($cObj);

        $this->formcycleService = $this->formcycleServiceFactory->createFromPageUid($cObj->data['pid']);

        $forms = $this->formcycleService->getAvailableForms();

        $processedData['forms'] = $forms;

        return $processedData;
    }
}
