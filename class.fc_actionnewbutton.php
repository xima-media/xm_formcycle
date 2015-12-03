<?php 

include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));
class tx_newbutton {
  	
 
    function startnew($PA, $fobj){
    	
		$fch = new FcHelper();
		$fc_iFrameUrl = $fch->getFcIframeUrl();
		$user_lang = $GLOBALS['BE_USER']->uc['lang'];
		
		return '<iframe style="height:130px;border:none;" src="'.$fc_iFrameUrl.'/cp.html?lang='.$user_lang.'"></iframe>';

    } 
  } 
?>