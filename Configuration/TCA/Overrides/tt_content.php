<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {
        $extKey = 'xm_formcycle';

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            'Xmformcycle',
            'FormCycle Integrator'
        );

        $pluginSignature = str_replace('_', '', $extKey) . '_xmformcycle';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_list.xml'
        );
    }
);
