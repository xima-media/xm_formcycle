<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Enums;

enum SelectorsV11: string
{
    case CONTENT_WIZARD = '.t3-new-content-element-wizard-window';
    case CONTENT_WIZARD_BUTTON = 'Content';
    case CONTENT_WIZARD_FORM_TAB = '.nav-tabs li:nth-child(3) a';
}
