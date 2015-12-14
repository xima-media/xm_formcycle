<?php
namespace Xima\XmFormcycle\UserFunc;

use Xima\XmFormcycle\Helper\FcHelper;

/**
 * Class Openfc
 *
 * @package Xima\XmFormcycle\UserFunc
 */
class Openfc
{
    
    /**
     * @param $PA
     * @param $fobj
     * @return string
     */
    function startnew($PA, $fobj)
    {
        $fch = new FcHelper();
        $fc_AdminUrl = $fch->getFcAdministrationUrl();
        $user_lang = $GLOBALS['BE_USER']->uc['lang'];
        $mssage_style = '<br/><span style="font-size:11px;">';
        $mssage_link_style = '<br/><span style="font-size:12px;color:#C50084;font-weight: bold;">';
        $message_link = $mssage_link_style . 'Open FormCycle Administration</span>';
        $message = $mssage_style . 'Link opens in a new window with a seperat login dialog.</span>';

        if ($user_lang == 'de') {
            $message_link = $mssage_link_style . 'FormCycle Administration öffnen</span>';
            $message = $mssage_style . 'Der Link öffnet ein neues Fenster mit eigenen Login-Dialog.</span>';
        }

        return '&nbsp;&nbsp;<a href="' . $fc_AdminUrl . '" target="_blank">' . $message_link . '</a><br/>&nbsp;&nbsp;' . $message . '<br/><br/>';

    }
}
