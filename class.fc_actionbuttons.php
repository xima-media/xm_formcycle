<?php 

include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));
class tx_editbutton {
  	
 
    function startedit($PA, $fobj){
    	
		$fch = new FcHelper();
		$fc_DesignerUrl = $fch->getFcUrlWithSession();
		$typoSiteURL = $fch->getTypoSiteURL();
		$user_lang = $GLOBALS['BE_USER']->uc['lang'];
		$button_value = "edit";
		
		if($user_lang == 'de'){
			$button_value = "Bearbeiten";
		}
		 
      return '<br/><span></span><input type="button" name="'.$PA['itemFormElName'].'" 
value="'.$button_value.'" onclick="editForm();">

<script type="text/javascript">

function editForm(){
	var projectId = findProjectElement();
	var icssURLs = findCssElement();

	window.open("'.$fc_DesignerUrl.'" + projectId + icssURLs);
}


function findProjectElement(name)
{
	var selElem = document.getElementsByClassName("select");

    for ( var i = 0; i < selElem.length; i++ ) {
	    var fcProjectElem = selElem[i].name;
	    if(null != fcProjectElem){
	    	if (fcProjectElem.match(/(^|[\W_])xfc_p_id([\W_]|$)/)) {
	        	return selElem[i].value;
	        }
		}
    }
}

function findCssElement(name)
{
	var selElem = document.getElementsByClassName("formField");

    for ( var i = 0; i < selElem.length; i++ ) {
	    var fcProjectElem = selElem[i].name;
	    if(null != fcProjectElem){
	    	if (fcProjectElem.match(/(^|[\W_])xfc_p_icss([\W_]|$)/)) {
	    		var selE = selElem[i];
				var ret = "";
	 		    for (var y = 0; y < selE.length; y++) {
        			ret = ret + "&icss='.$typoSiteURL.'" + selE.options[y].value;
    			}
	        	return ret;
	        }
		}
    }
}
</script>'; 
    } 
  } 
?>