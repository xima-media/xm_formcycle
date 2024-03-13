<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

// register custom form element
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1709039883] = [
    'nodeName' => 'formcycle-selection',
    'priority' => 40,
    'class' => \Xima\XmFormcycle\Form\Element\FormcycleSelection::class,
];

// register cache
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xm_formcycle'] ??= [];

// register PageTS for v11
$versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
if ($versionInformation->getMajorVersion() < 12) {
    ExtensionManagementUtility::addPageTSConfig('@import "EXT:xm_formcycle/Configuration/page.tsconfig"');
}
