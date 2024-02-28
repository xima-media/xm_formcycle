<?php

namespace Xima\XmFormcycle\Dto;

enum IntegrationMode: string
{
    case Integrated = 'integrated';
    case Ajax = 'AJAX';
    case Iframe = 'iFrame';
}
