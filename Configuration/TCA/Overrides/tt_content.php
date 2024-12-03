<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

$tempFields = [
    'tx_xmformcycle_form_id' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_form_id.label',
        'config' => [
            'type' => 'user',
            'renderType' => 'formcycle-selection',
        ],
    ],
    'tx_xmformcycle_redirect_success' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_redirect_success',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'minitems' => 0,
            'maxitems' => 1,
            'size' => 1,
        ],
    ],
    'tx_xmformcycle_redirect_error' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_redirect_error',
        'config' => [
            'type' => 'group',
            'allowed' => 'pages',
            'minitems' => 0,
            'maxitems' => 1,
            'size' => 1,
        ],
    ],
    'tx_xmformcycle_integration_mode' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode.0', 0],
                ['LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode.1', 1],
                ['LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode.2', 2],
                ['LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode.3', 3],
                ['LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_integration_mode.4', 4],
            ],
        ],
    ],
    'tx_xmformcycle_is_jquery' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_is_jquery',
        'config' => [
            'type' => 'check',
            'default' => 1,
        ],
    ],
    'tx_xmformcycle_is_jquery_ui' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_is_jquery_ui',
        'config' => [
            'type' => 'check',
            'default' => 1,
        ],
    ],
    'tx_xmformcycle_additional_params' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_additional_params',
        'config' => [
            'type' => 'input',
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:xm_formcycle/Configuration/FlexForms/flexform_list.xml',
    'formcycle'
);
