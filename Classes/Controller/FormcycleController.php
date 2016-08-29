<?php
namespace Xima\XmFormcycle\Controller;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XmFormcycle\Helper\FcHelper;
use Xima\XmFormcycle\Helper\WorkaroundHelper;

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
 * Class FormcycleController
 *
 * @package xm_formcycle
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FormcycleController extends ActionController
{
    /**
     * @var string
     */
    protected $extKey = 'xm_formcycle';

    /**
     * @var array
     */
    protected $extConf = array();

    /**
     *
     */
    public function initializeListAction()
    {
        if ($this->settings['enableJs'] == true){
            $this->includeJavaScript($this->settings['jsFiles']);
        }

        $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $viewVars = array();

        switch ($this->extConf['integrationMode']){
            case 'AJAX':
                $viewVars = $this->getByAjax();
                break;
            case 'default':
            default:
                $viewVars = $this->getDirectly();
        }

        $viewVars['integrationModeKey'] = $this->extConf['integrationMode'];

        $this->view->assignMultiple($viewVars);
    }

    /**
     * Initialize filter action
     */
    public function initializeFormContentAction()
    {
        /** @var WorkaroundHelper $workarounds */
        $workarounds = $this->objectManager->get('Xima\\XmFormcycle\\Helper\\WorkaroundHelper');
        $args = $this->request->getArguments();

        $this->settings = array_merge(
            $this->settings,
            $workarounds->findFlexformDataByUid($args['uid'])
        );
    }

    /**
     *
     */
    public function formContentAction()
    {
        $this->view->assignMultiple($this->getDirectly());
    }

    /**
     *
     */
    protected function getDirectly()
    {
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
        $fc_ContentUrl = $fch->getFormContent(
            $selProjectId,
            $selOkPage,
            $selErrorPage,
            $usejq,
            $useui,
            $usebs,
            $frontendLang,
            $fcParams
        );

        return array(
            'form' => $fch->getFileContent($fc_ContentUrl, '', '', '')
        );
    }

    /**
     * Process to load form by AJAX
     *
     * @return array
     */
    protected function getByAjax()
    {
        $cObj = $this->configurationManager->getContentObject();

        return array(
            'uid' => $cObj->data['uid'],
        );
    }

    /**
     * @param $uid
     * @return string
     */
    function getRedirectURL($uid)
    {
        return $this->uriBuilder
            ->reset()
            ->setArguments(array('L' => $GLOBALS['TSFE']->sys_language_uid))
            ->setTargetPageUid($uid)
            ->setUseCacheHash(false)
            ->setCreateAbsoluteUri(true)
            ->buildFrontendUri();
    }

    /**
     * @param $s
     * @param bool|false $use_forwarded_host
     * @return string
     */
    function url_origin($s, $use_forwarded_host = false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    /**
     * @param $s
     * @param bool|false $use_forwarded_host
     * @return string
     */
    function full_url($s, $use_forwarded_host = false)
    {
        return $this->url_origin($s, $use_forwarded_host) . strtok($s['REQUEST_URI'], '?');
    }

    /**
     * Binds JavaScript files in the HTML head of the page (TYPO3).
     *
     * @param array $files file names, starting with http or relative
     * @param bool $footer includes the scripts at the footer if set to true
     */
    public function includeJavaScript(array $files, $footer = true)
    {
        foreach ($files as $file) {

            // support typo3 notation (Ext:Resources/Public/js/xyz.js) and short notation (Ext:xyz.js)
            if (strstr($file, 'EXT:')) {
                $file = explode(':', $file);
                $relPath = ExtensionManagementUtility::siteRelPath($this->extKey);
                $file = $relPath.$file[1];
            }

            if ($GLOBALS['TSFE']->getPageRenderer()) {
                if ($footer == true){
                    $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($file);
                } else {
                    $GLOBALS['TSFE']->getPageRenderer()->addJsFile($file);
                }
            }
        }
    }
}
