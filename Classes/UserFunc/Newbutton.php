<?php

namespace Xima\XmFormcycle\UserFunc;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmFormcycle\Helper\FcHelper;

/**
 * Class Newbutton
 *
 * @package Xima\XmFormcycle\UserFunc
 */
class Newbutton extends ActionController
{

    /**
     * @param $PA
     * @param $fobj
     * @return string
     */
    function startnew($PA, $fobj)
    {
        $fch = new FcHelper();
        $fc_iFrameUrl = $fch->getFcIframeUrl();

        $user_lang = $GLOBALS['BE_USER']->uc['lang'];
        $user_name = $GLOBALS['gFcUser'];
        $user_pass = $GLOBALS['gFcPass'];

        $selProjectId = $this->settings['xf']['xfc_p_id'];

        $xx = $PA['row']["pi_flexform"];

        if (!empty($xx) && is_string($xx)) {

            $xml = new \SimpleXMLElement($xx);
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
	    <iframe id="fcIframe" style="height:170px;border:none;" src="' . $fc_iFrameUrl . '/cp.html?lang=' . $user_lang . '&pid=' . $pid . '&dc=' . time() . rand(1,
                10000) . '&user=' . $user_name . '&pass=' . $user_pass . '"></iframe>';

        return $retTemp;
    }
}
