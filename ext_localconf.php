<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Xima.' . $_EXTKEY,
    'Xmformcycle',
    array(
        'Formcycle' => 'list',

    ),
    // non-cacheable actions
    array(
        'Formcycle' => 'list',

    )
);
