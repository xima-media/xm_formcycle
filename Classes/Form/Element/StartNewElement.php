<?php

declare(strict_types=1);

namespace Xima\XmFormcycle\Form\Element;

use SimpleXMLElement;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use Xima\XmFormcycle\Helper\FcHelper;

class StartNewElement extends AbstractFormElement
{
    public function render()
    {
        // Custom TCA properties and other data can be found in $this->data, for example the above
        // parameters are available in $this->data['parameterArray']['fieldConf']['config']['parameters']
        $PA = $this->data;

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($PA);

        $fch = new FcHelper();
        $fc_iFrameUrl = $fch->getFcIframeUrl();

        $user_lang = $GLOBALS['BE_USER']->uc['lang'];
        $user_name = $GLOBALS['gFcUser'];
        $user_pass = $GLOBALS['gFcPass'];

        $selProjectId = $this->settings['xf']['xfc_p_id'];

        if (is_array($PA['databaseRow']['pi_flexform']) && array_key_exists(
            'data',
            $PA['databaseRow']['pi_flexform']
        )) {
            $pid = $PA['databaseRow']['pi_flexform']['data']['sheetGeneralOptions']['lDEF']['settings.xf.xfc_p_id']['vDEF'];
        } else {
            $xml = new SimpleXMLElement($PA['databaseRow']['pi_flexform']);
            $pid = $xml->data->sheet[0]->language->field->value->__toString();
        }

        $retTemp = '
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <button class="btn btn-sm btn-default" onclick="javascript:refreshMe()">refresh</button>
        <script>

        var $j = jQuery.noConflict();

        function refreshMe(){var ii = document.getElementById("fcIframe"); ii.src=ii.src;}

        $j("document").ready(function($){
            roEelem = getInputElem();
            roEelem.readOnly=true;
            if (window.addEventListener)
            {
                 window.addEventListener("message", function(a){
                    elem = getInputElem();
                    elem.readOnly=false;
                    elem.value = a["data"];
                    elem.readOnly=true;
                 }, false);
            } else {
                window.attachEvent("onmessage", function(a){
                    elem = getInputElem();
                    elem.readOnly=false;
                    elem.value = a["data"];
                    elem.readOnly=true;
                });
            }

            function createNewProjektOption(myId, myName) {
                var opt = document.createElement("option");
                opt.value = myId;
                opt.text = myName;
                opt.setAttribute("selected", "selected");
                addMyOption(opt);
            }

            function getInputElem()
            {
                var fcEl = false;
                var xfcFields = $j("[data-formengine-input-name*=\'xfc_p_id\'], input[name*=\'xfc_p_id\']");

                for ( var i = 0; i < xfcFields.length; i++ ) {

                    var tmpFcEl = xfcFields[i];
                console.log(tmpFcEl);
                    if ("hidden" == tmpFcEl.getAttribute("type")) {
                        fcEl = tmpFcEl;
                    } else {
                        if (tmpFcEl.getAttribute("id")) {
                            $j("#" + tmpFcEl.getAttribute("id")).parent().hide();
                        } else {
                            tmpFcEl.hide();
                        }
                    }
                }

                return fcEl;
            }
        });
        </script>
        <iframe id="fcIframe" style="height:620px;width:100%;border:none;" src="' . $fc_iFrameUrl . '/cp.html?lang=' . $user_lang . '&pid=' . $pid . '&dc=' . time() . random_int(
            1,
            10000
        ) . '&user=' . $user_name . '&pass=' . $user_pass . '"></iframe>';

        $result = $this->initializeResultArray();
        $result['html'] = $retTemp;
        return $result;
    }
}
