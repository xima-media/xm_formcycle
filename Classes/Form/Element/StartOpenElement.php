<?php

declare(strict_types=1);

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class StartOpenElement extends AbstractFormElement
{
    public function render()
    {
        // Custom TCA properties and other data can be found in $this->data, for example the above
        // parameters are available in $this->data['parameterArray']['fieldConf']['config']['parameters']

        $user_lang = $GLOBALS['BE_USER']->uc['lang'];

        $mssage_style = '<br/><span style="font-size:11px;">';
        $mssage_link_style = '<br/><span style="font-size:11px;color:#C50084;font-weight: normal;">';
        $message_link = $mssage_link_style . 'FormCycle TYPO3 extension help</span>';

        if ($user_lang == 'de') {
            $message_link = $mssage_link_style . 'Hilfe für FormCycle TYPO3 Erweiterung öffnen</span>';
        }
        $fc_HelpUrl = 'http://help.formcycle.eu/xwiki/bin/view/CMS+Extension/Typo3+Extension';

        $result = $this->initializeResultArray();
        $result['html'] = '&nbsp;&nbsp;<a href="' . $fc_HelpUrl . '" target="_blank">' . $message_link . '</a><br/>';
        return $result;
    }
}
