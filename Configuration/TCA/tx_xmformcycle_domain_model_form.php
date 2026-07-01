<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'id',
        'iconfile' => 'EXT:xm_formcycle/Resources/Public/Icons/Form.svg',
        'searchFields' => 'title',
        'security' => [
            'ignorePageTypeRestriction' => true,
            'ignoreRootLevelRestriction' => true,
            'ignoreWebMountRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' =>
                '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, form_id, client_name, title, categories, online',
        ],
    ],
    'columns' => [
        'id' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.id',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'required' => true,
            ],
        ],
        'form_id' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.form_id',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'required' => true,
                'readOnly' => true,
            ],
        ],
        'client_name' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.client_name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
                'readOnly' => true,
            ],
        ],
        'thumbnail' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.thumbnail',
            'config' => [
                'type' => 'text',
                'cols' => 20,
                'rows' => 1,
            ],
        ],
        'url' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.url',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'change_date' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.change_date',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'fc_date' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.fc_date',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'online' => [
            'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang_db.xlf:tx_xmformcycle_domain_model_form.online',
            'config' => [
                'type' => 'check',
                'readOnly' => true,
            ],
        ],
        'categories' => [
            'config' => [
                'type' => 'category',
            ],
        ]
    ],
];
