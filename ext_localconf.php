<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['xm_formcycle']);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Xima.' . $_EXTKEY,
    'Xmformcycle',
    array(
        'Formcycle' => 'list, formContent',

    ),
    // non-cacheable actions
    $extConf['integrationMode'] != 'default' ? array('Formcycle' => 'list') : array()
);
