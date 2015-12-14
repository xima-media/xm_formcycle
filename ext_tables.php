<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

error_reporting(E_ERROR);
ini_set('display_errors',1);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'Xmformcycle',
    'FormCycle Integrator'
);


//error_reporting(E_ALL);
//ini_set('display_errors',1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'FormCycle');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_xmformcycle';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml'
);
