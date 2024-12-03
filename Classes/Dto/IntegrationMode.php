<?php

namespace Xima\XmFormcycle\Dto;

enum IntegrationMode: string
{
    case Integrated = 'integrated';
    case Ajax = 'AJAX';
    case Iframe = 'iFrame';
    case AjaxTypo3 = 'AJAX (TYPO3)';
    case AjaxFormcycle = 'AJAX (FORMCYCLE)';

    case Default = 'Default';

    public static function fromDatabase($value): self
    {
        return match ($value) {
            1 => self::Integrated,
            2 => self::AjaxTypo3,
            3 => self::AjaxFormcycle,
            4 => self::Iframe,
            default => self::Default,
        };
    }

    public static function fromSiteSettings($value): self
    {
        return match ($value) {
            'iframe' => self::Iframe,
            'ajax_typo3' => self::AjaxTypo3,
            'ajax_formcycle' => self::AjaxFormcycle,
            default => self::Integrated,
        };
    }

    public function forDataProcessing(): self
    {
        if ($this === self::AjaxTypo3 || $this === self::AjaxFormcycle) {
            return self::Ajax;
        }
        return $this;
    }
}
