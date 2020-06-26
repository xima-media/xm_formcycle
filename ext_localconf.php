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
    $extConf['integrationMode'] == 'integrated' ? array('Formcycle' => 'list') : array()
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593170596] = [
    'nodeName' => 'startNewElement',
    'priority' => 40,
    'class' => \Xima\XmFormcycle\Form\Element\StartNewElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593173971] = [
    'nodeName' => 'startOpenElement',
    'priority' => 40,
    'class' => \Xima\XmFormcycle\Form\Element\StartOpenElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593174782] = [
    'nodeName' => 'fcElement',
    'priority' => 40,
    'class' => \Xima\XmFormcycle\Form\Element\FcElement::class,
];
