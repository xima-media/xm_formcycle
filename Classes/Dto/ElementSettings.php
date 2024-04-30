<?php

namespace Xima\XmFormcycle\Dto;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ElementSettings
{
    public int $successPid = 0;

    public int $errorPid = 0;

    public string $formId = '';

    public ?IntegrationMode $integrationMode = null;

    public bool $loadFormcycleJquery = true;

    public bool $loadFormcycleJqueryUi = false;

    public string $additionalParameters = '';

    public string $language = '';

    public static function createFromContentElement(
        FlexFormService $flexFormService,
        ContentObjectRenderer $cObj,
    ): self {
        $xml = $flexFormService->convertFlexFormContentToArray($cObj->data['pi_flexform'] ?? '');

        $settings = new self();
        $settings->formId = $cObj->data['tx_xmformcycle_form_id'] ?? '';

        $language = $cObj->getRequest()?->getAttribute('language')?->getTwoLetterIsoCode() ?? '';
        $settings->language = $language;

        $settings->successPid = $xml['settings']['xf']['siteok'] ?? 0;
        $settings->errorPid = $xml['settings']['xf']['siteerror'] ?? 0;
        $settings->loadFormcycleJquery = (bool)($xml['settings']['xf']['useFcjQuery'] ?? 1);
        $settings->loadFormcycleJqueryUi = (bool)($xml['settings']['xf']['useFcjQueryUi'] ?? 0);
        $settings->additionalParameters = $xml['settings']['xf']['useFcUrlParams'] ?? '';
        $settings->integrationMode = IntegrationMode::tryFrom($xml['settings']['xf']['integrationMode']);

        return $settings;
    }
}
