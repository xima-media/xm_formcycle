<?php

return [
    'frontend' => [
        'xm-formcycle/form' => [
            'target' => \Xima\XmFormcycle\Middleware\FormMiddleware::class,
            'after' => [
                'typo3/cms-frontend/tsfe',
            ],
        ],
    ],
];
