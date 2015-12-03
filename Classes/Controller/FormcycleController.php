<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package xm_formcycle
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */

 		/*error_reporting(E_ALL);
		ini_set('display_errors',1);
		*/
include(t3lib_extMgm::extPath('xm_formcycle','Classes/Helper/Fchelper.php'));

class Tx_XmFormcycle_Controller_FormcycleController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * action list
	 *
	 * @return void
	 */
	 
	public function listAction() {
		$GLOBALS['icss'] = $this->settings['xf']['icss']; 
		$selErrorPage = $this->getRedirectURL($this->settings['xf']['siteerror']);
		$selOkPage = $this->getRedirectURL($this->settings['xf']['siteok']);
		$usejq = $this->settings['xf']['useFcjQuery'];
		$useui = $this->settings['xf']['useFcjQueryUi'];
		$usebs = $this->settings['xf']['useFcBootStrap'];
		$selProjectId = $this->settings['xf']['xfc_p_id'];
		$frontendLang = $GLOBALS['TSFE']->config['config']['language'];
		$fcParams = $this->settings['xf']['useFcUrlParams'];
		
		$fch = new FcHelper();
		$fc_ContentUrl = $fch->getFormContent($selProjectId,t3lib_div::getIndpEnv('TYPO3_SITE_URL'), $selOkPage, $selErrorPage, $usejq, $useui, $usebs, $frontendLang, $fcParams);
		$fc_Content = $fch->getFileContent($fc_ContentUrl, '', '', '');		
		$this->view->assign('form', $fc_Content);
	}


	function getRedirectURL($uid) {
	    return $this->uriBuilder
	                ->reset()
	                ->setArguments(array('L' => $GLOBALS['TSFE']->sys_language_uid))
	                ->setTargetPageUid($uid)
                        ->setUseCacheHash(false)
                        ->buildFrontendUri();
	}
	
	function url_origin($s, $use_forwarded_host=false)
	{
	    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	    $sp = strtolower($s['SERVER_PROTOCOL']);
	    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	    $port = $s['SERVER_PORT'];
	    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
	    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
	    return $protocol . '://' . $host;
	}

	function full_url($s, $use_forwarded_host=false)
	{
	    return $this->url_origin($s, $use_forwarded_host) . strtok($s['REQUEST_URI'],'?');
	}
}
?>