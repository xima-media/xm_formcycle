<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}



//use TxXmFormCycle\Classes\Helper\Helper;

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Xmformcycle',
	'FormCycle Integrator'
);


//error_reporting(E_ALL);
//ini_set('display_errors',1);
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'FormCycle');

$pluginSignature = str_replace('_','',$_EXTKEY) . '_xmformcycle';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');
?>