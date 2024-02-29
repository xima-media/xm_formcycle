<?php

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
