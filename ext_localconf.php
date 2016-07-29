<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Xima.' . $_EXTKEY,
    'Xmformcycle',
    array(
        'Formcycle' => 'list, formContent',

    ),
    // non-cacheable actions
    array(

    )
);
