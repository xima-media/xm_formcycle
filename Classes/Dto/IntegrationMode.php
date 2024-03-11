<?php

namespace Xima\XmFormcycle\Dto;

enum IntegrationMode: string
{
    case Integrated = 'integrated';
    case Ajax = 'AJAX';
    case Iframe = 'iFrame';
    case AjaxTypo3 = 'AJAX (TYPO3)';
    case AjaxFormcycle = 'AJAX (FORMCYCLE)';

    public function forDataProcessing(): self
    {
        if ($this === self::AjaxTypo3 || $this === self::AjaxFormcycle) {
            return self::Ajax;
        }
        return $this;
    }
}
