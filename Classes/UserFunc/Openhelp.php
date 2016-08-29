<?php

namespace Xima\XmFormcycle\UserFunc;

/**
 * Class Openhelp
 *
 * @package Xima\XmFormcycle\UserFunc
 */
class Openhelp
{

    function startopen($PA, $fobj)
    {

        $user_lang = $GLOBALS['BE_USER']->uc['lang'];

        $mssage_style = '<br/><span style="font-size:11px;">';
        $mssage_link_style = '<br/><span style="font-size:11px;color:#C50084;font-weight: normal;">';
        $message_link = $mssage_link_style . 'FormCycle TYPO3 extension help</span>';

        if ($user_lang == 'de') {

            $message_link = $mssage_link_style . 'Hilfe für FormCycle TYPO3 Erweiterung öffnen</span>';
        }
        $fc_HelpUrl = 'http://help4.formcycle.de/xwiki/bin/view/CMS+Extension/Typo3+Extension';
        return '&nbsp;&nbsp;<a href="' . $fc_HelpUrl . '" target="_blank">' . $message_link . '</a><br/>';
    }
}
