<?php
/**
 * 
 */
 
  $gFcAdminUrl = "";
 $gFcProvideUrl = "";
 $gFcUser = "";
 $gFcPass = "";

if ( ! class_exists('FcHelper')){ 
class FcHelper {

	function __construct(){
		$ek = 'xm_formcycle';
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$ek]);
		$GLOBALS['gFcUrl'] = $this->extConf['formCycleUrl'];
		$GLOBALS['gFcUser'] = $this->extConf['formCycleUser'];
		$GLOBALS['gFcPass'] = $this->extConf['formCyclePass'];
	}
	
	function getIcss(){
		return $GLOBALS['icss'];
	}

	function getSessionId() {
	
		$result = file_get_contents($GLOBALS['gFcUrl'] . "/ext-4.0.2/servlet/aktuellerBenutzer?action=login&user=".$GLOBALS['gFcUser']."&pass=".$GLOBALS['gFcPass']);
		$json_data = json_decode($result, true);
		$sessId = $json_data['sessionId'];	
		
		return $sessId;
	}
	
	function getFcUrlWithSession(){
		list($protokoll, $nix, $domain,$appname) = explode("/", $GLOBALS['gFcUrl']);
		return $protokoll.'//'.$domain.'/;jsessionid='.$this->getSessionId().'/'.$appname;
	}
	
	function getTypoSiteURL(){
		return t3lib_div::getIndpEnv('TYPO3_SITE_URL');
	}
	
	function getFcDesignerEditUrl(){
		return $this->getFcUrlWithSession();
	}
	
	function getFormContent($projektId, $returnurl, $siteok, $siteerror, $usejq, $useui, $usebs, $frontendLang){
		
		$okUrl = $returnurl.$siteok;
		$errorUrl = $returnurl.$siteerror;
		
		return $GLOBALS['gFcUrl'] . '/form/provide/' . $projektId . '?xfc-pp-form-only=true'.
		'&usejq='.$usejq.
		'&useui='.$useui.
		'&usebs='.$usebs.
		'&lang='.$frontendLang.
		'&xfc-pp-base-url='.$GLOBALS['gFcUrl'].
		'&xfc-pp-success-url='.$okUrl.
		'&xfc-pp-error-url='.$errorUrl;
	}
	
	function getProjectListUrl(){
		return $this->getFcUrlWithSession() . '/ext-4.0.2/servlet/projekt';
	}
	
	function getFcIframeUrl(){
		return $this->getFcUrlWithSession() . '/external';
	}	
	
	function getFcAdministrationUrl(){
		return $GLOBALS['gFcUrl'];
	}
}	

}

?>