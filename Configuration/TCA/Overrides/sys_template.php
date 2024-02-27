<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

ExtensionManagementUtility::addStaticFile(
    'xm_formcycle',
    'Configuration/TypoScript',
    'FORMCYCLE'
);
