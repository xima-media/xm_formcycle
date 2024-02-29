<?php

return [
    'frontend' => [
        'xm-formcycle/form' => [
            'target' => \Xima\XmFormcycle\Middleware\FormMiddleware::class,
            'before' => [
                'typo3/cms-frontend/tsfe',
            ],
        ],
    ],
];
