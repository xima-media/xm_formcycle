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
<a href="javascript:refreshMe()">refresh</a><script>
		function refreshMe(){var ii = document.getElementById("fcIframe"); ii.src=ii.src;}
		roEelem = getInputElem();
		roEelem.readOnly=true;
		roEelem.style.display = "none";
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

		function getInputElem(myId)
		{
		    var selElem = getTypo6LtsInput(myId);

		    if (false == selElem) {
                selElem = getTypo7LtsInput(myId);
            }

		    return selElem;
		}

		function getTypo6LtsInput(myId) {
		    var selElem = document.getElementsByClassName("formField");

		    for ( var i = 0; i < selElem.length; i++ ) {
		        var fcProjectElem = selElem[i].name;
		        if(null != fcProjectElem){
                    if (fcProjectElem.match(/(^|[\W_])xfc_p_id([\W_]|$)/)) {
                        return selElem[i];
                    }
                }
		    }

		    return false;
		}

		function getTypo7LtsInput(myId) {

		    var result = false;
		    var fieldNameToHide = false;
            var fc = document.getElementsByTagName("input");
            for (var i = 0; i < fc.length; i++){

                var fcEl = fc[i];
                var attr = fcEl.getAttribute("name");
                if (attr && attr.length >= 0 && attr.contains("xfc_p_id")) {
                    result = fcEl;
                    fieldNameToHide = attr;
                    continue;
                }
            }

            if (fieldNameToHide) {
                var fc2 = document.getElementsByClassName("form-control");
                for (var i = 0; i < fc2.length; i++){
                    var fc2El = fc2[i];
                    var attr = fc2El.getAttribute("data-formengine-input-name");
                    if (attr && attr.length >= 0 && fieldNameToHide == attr) {
                        fc2El.style.display = "none";
                        continue;
                    }
                }
            }

            return result;
        }
		</script>
	<iframe id="fcIframe" style="height:170px;border:none;" src="' . $fc_iFrameUrl . '/cp.html?lang=' . $user_lang . '&pid=' . $pid . '&dc=' . time() . rand(1,
                10000) . '&user=' . $user_name . '&pass=' . $user_pass . '"></iframe>';

        return $retTemp;
    }
}
