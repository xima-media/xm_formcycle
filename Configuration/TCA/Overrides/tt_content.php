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
];
ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:xm_formcycle/Configuration/FlexForms/flexform_list.xml',
    'formcycle'
);
