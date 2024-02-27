<?php

declare(strict_types=1);

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use Xima\XmFormcycle\Helper\FcHelper;

class FcElement extends AbstractFormElement
{
    public function render()
    {
        // Custom TCA properties and other data can be found in $this->data, for example the above
        // parameters are available in $this->data['parameterArray']['fieldConf']['config']['parameters']

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

        $result = $this->initializeResultArray();
        $result['html'] = '&nbsp;&nbsp;<a href="' . $fc_AdminUrl . '" target="_blank">' . $message_link . '</a><br/>&nbsp;&nbsp;' . $message . '<br/><br/>';
        return $result;
    }
}
