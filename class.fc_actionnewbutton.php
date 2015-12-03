<?php 

include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));
class tx_newbutton extends Tx_Extbase_MVC_Controller_ActionController {
    
 
    function startnew($PA, $fobj){
	
	$fch = new FcHelper();
	$fc_iFrameUrl = $fch->getFcIframeUrl();
	
	$user_lang = $GLOBALS['BE_USER']->uc['lang'];
	$user_name = $GLOBALS['gFcUser'];
	$user_pass = $GLOBALS['gFcPass'];

	$selProjectId = $this->settings['xf']['xfc_p_id'];
		
	$xx = $PA['row']["pi_flexform"];
	
	if($xx!=null){
	
	$xml = new SimpleXMLElement($xx);
	$pid = $xml->data->sheet[0]->language->field->value->__toString();
	
	}
	
	$retTemp = '<a href="javascript:refreshMe()">refresh</a><script>
		function refreshMe(){var ii = document.getElementById("fcIframe"); ii.src=ii.src;}
		roEelem = getInputElem();
		//roEelem.readOnly=true;
		roEelem.style.display = "none";
		if (window.addEventListener) 
		{
		     window.addEventListener("message", function(a){
			elem = getInputElem();
			elem.readOnly=false;
			elem.value = a["data"];
			elem.onchange();
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
		    var selElem = document.getElementsByClassName("formField");
		
		    for ( var i = 0; i < selElem.length; i++ ) {
		        var fcProjectElem = selElem[i].name;
		        if(null != fcProjectElem){
		    	if (fcProjectElem.match(/(^|[\W_])xfc_p_id([\W_]|$)/)) {
				return selElem[i];
		            }
			}
		    }
		}
		</script>
	<iframe id="fcIframe" style="height:170px;border:none;" src="'.$fc_iFrameUrl.'/cp.html?lang='.$user_lang.'&pid='.$pid.'&dc='.time().rand(1,10000).'&user='.$user_name.'&pass='.$user_pass.'"></iframe>';
	
	return $retTemp;
    } 
  } 
?>