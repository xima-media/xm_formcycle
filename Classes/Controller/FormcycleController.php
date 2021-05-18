<?php

namespace Xima\XmFormcycle\Controller;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    protected $extConf = [];

    /**
     *
     */
    public function initializeListAction()
    {
        $this->extConf = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][$this->extKey];
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $viewVars = [];

        $typo3link = false;
        $formcycleServerUrl = '';
        $integrationMode = $this->extConf['integrationMode'];

        if (array_key_exists('integrationMode',
                $this->settings['xf']) && $this->settings['xf']['integrationMode'] != 'default') {
            $integrationMode = $this->settings['xf']['integrationMode'];
        }

        switch ($integrationMode) {
            case 'AJAX (TYPO3)':
                $viewVars = $this->getByAjax();
                $partialsTemplate = 'AJAX';
                $typo3link = true;
                break;
            case 'AJAX (FORMCYCLE)':
                $partialsTemplate = 'AJAX';
                $formcycleServerUrl = $this->getFcUrl(new FcHelper(true), '&xfc-rp-form-only=true');
                break;
            case 'iFrame':
                $partialsTemplate = 'iFrame';
                $formcycleServerUrl = $this->getFcUrl(new FcHelper(true)) . '&xfc-height-changed-evt=true';
                break;
            case 'integrated':
            default:
                $viewVars = $this->getDirectly(false);
                $partialsTemplate = 'integrated';
        }

        $viewVars = array_merge($viewVars, [
            'typo3link'          => $typo3link,
            'formcycleServerUrl' => $formcycleServerUrl,
            'partialsTemplate'   => $partialsTemplate,
            'integrationModeKey' => $this->extConf['integrationMode'],
        ]);

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
        $this->view->assignMultiple($this->getDirectly(true));
    }

    /**
     * @param bool $frontendServerUrl
     * @return array
     */
    protected function getDirectly($frontendServerUrl = false)
    {
        $fch = new FcHelper($frontendServerUrl);
        $fc_ContentUrl = $this->getFcUrl($fch, '&xfc-rp-form-only=true');

        return [
            'form' => $fch->getFileContent($fc_ContentUrl, '', '', ''),
        ];
    }

    /**
     * @param \Xima\XmFormcycle\Helper\FcHelper $fch
     * @param string $fcParams
     * @return string
     */
    protected function getFcUrl(FcHelper $fch, $fcParams = '')
    {
        $GLOBALS['icss'] = $this->settings['xf']['icss'];
        $selErrorPage = $this->getRedirectURL($this->settings['xf']['siteerror']);
        $selOkPage = $this->getRedirectURL($this->settings['xf']['siteok']);
        $usejq = $this->settings['xf']['useFcjQuery'];
        $useui = $this->settings['xf']['useFcjQueryUi'];
        $usebs = $this->settings['xf']['useFcBootStrap'];
        $selProjectId = $this->settings['xf']['xfc_p_id'];
        $frontendLang = $GLOBALS['TSFE']->config['config']['language'];
        $fcParams .= $this->settings['xf']['useFcUrlParams'];

        $fcParams = $this->resolveCustomParameters($fcParams);

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

        return $fc_ContentUrl;
    }

    /**
     * Process to load form by AJAX
     *
     * @return array
     */
    protected function getByAjax()
    {
        $cObj = $this->configurationManager->getContentObject();

        return [
            'uid' => $cObj->data['uid'],
        ];
    }

    /**
     * @param $uid
     * @return string
     */
    function getRedirectURL($uid)
    {
        return $this->uriBuilder
            ->reset()
            ->setArguments(['L' => GeneralUtility::makeInstance(Context::class)->getAspect('language')])
            ->setTargetPageUid(intval($uid))
            ->setCreateAbsoluteUri(true)
            ->build();
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
     * @param string $fcParams
     * @return string
     */
    private function resolveCustomParameters(string $fcParams)
    {
        $result = $fcParams;

        if (preg_match_all('~(?:{|%7B)(?<params>[\d\w]+)(?:}|%7D)~', $fcParams, $matches) !== false) {

            foreach ($matches['params'] as $idx => $param) {
                if (array_key_exists($param, $_GET)) {
                    $result = str_replace(
                        $matches[0][$idx],
                        $_GET[$param],
                        $result
                    );
                }
                else {
                    $result = str_replace(
                        $matches[0][$idx],
                        '',
                        $result
                    );
                }
            }
        }

        return $result;
    }
}
