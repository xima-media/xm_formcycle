<?php

namespace Xima\XmFormcycle\Dto;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;

class ElementSettings
{
    public int $successPid = 0;

    public int $errorPid = 0;

    public string $formId = '';

    public IntegrationMode $integrationMode;

    public bool $loadFormcycleJquery = true;

    public bool $loadFormcycleJqueryUi = false;

    public string $additionalParameters = '';

    public string $language = '';

    public static function createFromContentElement(
        ContentObjectRenderer $cObj,
    ): self {
        $settings = new self();
        $settings->formId = $cObj->data['tx_xmformcycle_form_id'] ?? '';

        try {
            $settings->language = (string)$cObj->getRequest()->getAttribute('language')->getLocale();
        } catch (\Error|ContentRenderingException) {
        }

        $settings->successPid = $cObj->data['tx_xmformcycle_redirect_success'] ?? 0;
        $settings->errorPid = $cObj->data['tx_xmformcycle_redirect_error'] ?? 0;
        $settings->loadFormcycleJquery = (bool)($cObj->data['tx_xmformcycle_is_jquery'] ?? 1);
        $settings->loadFormcycleJqueryUi = (bool)($cObj->data['tx_xmformcycle_is_jquery_ui'] ?? 0);
        $settings->additionalParameters = $cObj->data['tx_xmformcycle_additional_params'] ?? '';
        $settings->integrationMode = IntegrationMode::fromDatabase($cObj->data['tx_xmformcycle_integration_mode']);

        return $settings;
    }
}
