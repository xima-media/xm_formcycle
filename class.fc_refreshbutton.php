<?php 

include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));
class tx_refreshbutton {
  	
 
    function startrefresh($PA, $fobj){
    	
		$fch = new FcHelper();
		$fc_DesignerUrl = $fch->getFcUrlWithSession();
		$user_lang = $GLOBALS['BE_USER']->uc['lang'];
		
		$button_value = "refresh form list";
		
		if($user_lang == 'de'){
			$button_value = "Formularliste aktualisieren";
		}
		 
      return '<input type="button" name="xfc_refr" 
value="'.$button_value.'" onclick="refreshForm();"><br/>
<script type="text/javascript">

function refreshForm(){
	location.reload();
}

</script>'; 
    } 
  } 
?>