<?php

namespace Xima\XmFormcycle\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Service\FormcycleService;

abstract class AbstractProcessor implements DataProcessorInterface
{
    protected ElementSettings $settings;

    public function __construct(
        protected readonly FormcycleService $formcycleService,
        private readonly FlexFormService $flexFormService
    ) {
    }

    protected function initElementSettings(ContentObjectRenderer $cObj): void
    {
        $this->settings = ElementSettings::createFromContentElement(
            $this->flexFormService,
            $cObj,
        );
    }
}
