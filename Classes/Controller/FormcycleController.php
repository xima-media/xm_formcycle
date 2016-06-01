<?php
namespace Xima\XmFormcycle\Controller;

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
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $cObj = $this->configurationManager->getContentObject();

        $this->view->assignMultiple(array(
            'uid' => $cObj->data['uid'],
        ));
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
        $GLOBALS['icss'] = $this->settings['icss'];
        $selErrorPage = $this->getRedirectURL($this->settings['siteerror']);
        $selOkPage = $this->getRedirectURL($this->settings['siteok']);
        $usejq = $this->settings['useFcjQuery'];
        $useui = $this->settings['useFcjQueryUi'];
        $usebs = $this->settings['useFcBootStrap'];
        $selProjectId = $this->settings['xfc_p_id'];
        $frontendLang = $GLOBALS['TSFE']->config['config']['language'];
        $fcParams = $this->settings['useFcUrlParams'];

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

        $this->view->assignMultiple(array(
            'form' => $fch->getFileContent($fc_ContentUrl, '', '', '')
        ));
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

}
