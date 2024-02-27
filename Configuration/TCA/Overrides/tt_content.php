<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

$extKey = 'xm_formcycle';

ExtensionUtility::registerPlugin(
    $extKey,
    'Xmformcycle',
    'FormCycle Integrator'
);

$pluginSignature = str_replace('_', '', $extKey) . '_xmformcycle';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_list.xml'
);

$tempFields = [
    'tx_xmformcycle_form_id' => [
        'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_form_id.label',
        'config' => [
            'type' => 'formcycle-selection',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);
