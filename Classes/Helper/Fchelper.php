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


	function getFileContent($myURL, $myAction, $myUser, $myPasswd) {
			
		$result = '';
		$httpParams = array();
		$curlPostfields = '';
		
		if (function_exists('curl_init')){
			$ch = curl_init();
					
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			curl_setopt($ch, CURLOPT_URL,$myURL);
			curl_setopt($ch,CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_HTTPGET, false);

			if($myAction == 'version') {
			    curl_setopt($ch,CURLOPT_POST, false);
			    curl_setopt($ch,CURLOPT_HTTPGET, true);
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPostfields);
			
			
			if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']) {
        		    curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']);
 
        		    if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']) {
            			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']);
        		    }
        		    if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']) {
            			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']);
        		    }
    			}
			
			// Execute
			$result=curl_exec($ch);
			// Closing
			curl_close($ch); 
			
		} else {
			$httpArray = array();
			
			array_push($httpArray, 'method');
			$httpArray['method'] = 'POST';
			
			array_push($httpArray, 'request_fulluri');
			$httpArray['request_fulluri'] = true;
	
			$opts = array( 
				'http' => $httpArray, 
				'https' => $httpArray 
			); 
			$context = stream_context_create($opts); 
			$result =  file_get_contents($myURL, false, $context);
			
		}
		return $result;
	}

	function getTypoSiteURL(){
		return t3lib_div::getIndpEnv('TYPO3_SITE_URL');
	}
	
	function getFormContent($projektId, $returnurl, $siteok, $siteerror, $usejq, $useui, $usebs, $frontendLang, $fcParams){
		
		$okUrl = $returnurl.$siteok;
		$errorUrl = $returnurl.$siteerror;
		$sessionID = $GLOBALS['TSFE']->fe_user->id;

		return $GLOBALS['gFcUrl'] . '/form/provide/' . $projektId . '?xfc-pp-form-only=true'.
		'&xfc-rp-usejq='.$usejq.
		'&xfc-rp-useui='.$useui.
		'&xfc-rp-usebs='.$usebs.
		'&xfc-rp-inline=true'.
		'&lang='.$frontendLang.
		'&xfc-pp-external=true'.
		'&xfc-pp-base-url='.$GLOBALS['gFcUrl'].
		'&xfc-pp-success-url='.$okUrl.
		'&xfc-pp-error-url='.$errorUrl.
		$fcParams;
	}
	
	function getFcIframeUrl(){
		$res = $GLOBALS['gFcUrl'] . '/external';
		return $res;
	}	
	
	function getFcAdministrationUrl(){
		
		return $GLOBALS['gFcUrl'];
	}
}	

}


?>