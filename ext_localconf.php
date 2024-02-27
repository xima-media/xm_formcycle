<?php
if (!defined('TYPO3')) {
    die ('Access denied.');
}

$extConf = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_formcycle'];

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version()) > 10000000) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'XmFormcycle',
        'Xmformcycle',
        [
            \Xima\XmFormcycle\Controller\FormcycleController::class => 'list, formContent',
        ],
        // non-cacheable actions
        $extConf['integrationMode'] == 'integrated' ? [\Xima\XmFormcycle\Controller\FormcycleController::class => 'list'] : []
    );
} else {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'XmFormcycle',
        'Xmformcycle',
        [
            \Xima\XmFormcycle\Controller\FormcycleController::class => 'list, formContent',
        ],
        // non-cacheable actions
        $extConf['integrationMode'] == 'integrated' ? ['Formcycle' => 'list'] : []
    );
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593170596] = [
    'nodeName' => 'startNewElement',
    'priority' => 40,
    'class'    => \Xima\XmFormcycle\Form\Element\StartNewElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593173971] = [
    'nodeName' => 'startOpenElement',
    'priority' => 40,
    'class'    => \Xima\XmFormcycle\Form\Element\StartOpenElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1593174782] = [
    'nodeName' => 'fcElement',
    'priority' => 40,
    'class'    => \Xima\XmFormcycle\Form\Element\FcElement::class,
];

/**
 * Icon Registry
 */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'xmformcycle-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:xm_formcycle/Resources/Public/Icons/xmformcycle_default.png']
);

/**
 * Wizards
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    'mod {
        wizards.newContentElement.wizardItems.plugins {
            elements {
                search {
                    iconIdentifier = xmformcycle-icon
                    title = LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_domain_model_formcycle.wizard.name
                    description = LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:tx_xmformcycle_domain_model_formcycle.wizard.description
                    tt_content_defValues {
                        CType = list
                        list_type = xmformcycle_xmformcycle
                    }
                }
            }
            show = *
        }
    }'
);

// register custom form element
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1709039883] = [
    'nodeName' => 'formcycle-selection',
    'priority' => 40,
    'class' => \Xima\XmFormcycle\Form\Element\FormcycleSelection::class
];

// register cache
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xm_formcycle'] ??= [];
