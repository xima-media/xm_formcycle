<?php

use Xima\XmFormcycle\Controller\BackendController;

return [
    'xm_formcycle_form_selection' => [
        'path' => '/xm-formcycle/form-selection',
        'target' => BackendController::class . '::getAvailableForms',
    ],
    'xm_formcycle_form_reload' => [
        'path' => '/xm-formcycle/form-reload',
        'target' => BackendController::class . '::reloadAvailableForms',
    ],
];
