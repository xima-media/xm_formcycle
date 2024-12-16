<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Enums;

enum Selectors: string
{
    case CONTENT_WIZARD = 'typo3-backend-new-record-wizard';
    case CONTENT_WIZARD_BUTTON = 'typo3-backend-new-content-element-wizard-button';
    case CONTENT_WIZARD_FORM_TAB = 'button.navigation-item:nth-child(2)';
    case CONTENT_WIZARD_FORMCYCLE = 'button[data-identifier="forms_formcycle"]';
}
