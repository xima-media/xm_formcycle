<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Xima\XmFormcycle\Preview\FormcyclePreviewRenderer;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:wizard.title',
        'formcycle',
        'xm-formcycle',
    ],
    'felogin_login',
    'after'
);

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['formcycle'] = 'xm-formcycle';

$GLOBALS['TCA']['tt_content']['palettes']['formcycle'] = [
    'label' => 'LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:palettes.formcycle.title',
    'showitem' => 'tx_xmformcycle_form_id,--linebreak--,pi_flexform',
];

$GLOBALS['TCA']['tt_content']['types']['formcycle'] = [
    'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;headers,
                --div--;LLL:EXT:xm_formcycle/Resources/Private/Language/locallang.xlf:formcycle,
                    --palette--;;formcycle,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                    --palette--;;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
    'previewRenderer' => FormcyclePreviewRenderer::class,
];
