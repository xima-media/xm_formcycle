<?php
namespace Xima\XmFormcycle\Helper;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;

if (!class_exists(FcHelper::class)) {

    /**
     * Class FcHelper
     * @package Xima\XmFormcycle\Helper
     */
    class FcHelper
    {

        /**
         * FcHelper constructor.
         * @param bool $frontendServerUrl
         * @throws ExtensionConfigurationExtensionNotConfiguredException
         * @throws ExtensionConfigurationPathDoesNotExistException
         */
        public function __construct($frontendServerUrl = false)
        {
            $ek = 'xm_formcycle';
            $this->extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get($ek);
            $GLOBALS['gFcUrl'] = ($frontendServerUrl && $this->extConf['formCycleFrontendUrl'] != '') ? $this->extConf['formCycleFrontendUrl'] : $this->extConf['formCycleUrl'];
            $GLOBALS['gFcUser'] = $this->extConf['formCycleUser'];
            $GLOBALS['gFcPass'] = $this->extConf['formCyclePass'];
        }

        /**
         * @return mixed
         */
        public function getIcss()
        {
            return $GLOBALS['icss'];
        }

        /**
         * @param $myURL
         * @param $myAction
         * @param $myUser
         * @param $myPasswd
         * @return mixed|string
         */
        public function getFileContent($myURL, $myAction, $myUser, $myPasswd)
        {
            $curlPostfields = '';

            if (function_exists('curl_init')) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, $myURL);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPostfields);

                if ($myAction == 'version') {
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                } else {
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPGET, false);
                }

                if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']) {
                    curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']);

                    if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']) {
                        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL,
                            $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']);
                    }
                    if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']) {
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']);
                    }
                }

                $result = curl_exec($ch);
                // Closing
                curl_close($ch);
            } else {
                $httpArray = [
                    'method'          => 'POST',
                    'request_fulluri' => true,
                ];
                $opts = [
                    'http'  => $httpArray,
                    'https' => $httpArray,
                ];
                $context = stream_context_create($opts);
                $result = file_get_contents($myURL, false, $context);
            }

            return $result;
        }

        /**
         * @return mixed
         */
        public function getTypoSiteURL()
        {
            return GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        }

        /**
         * @param $projektId
         * @param $siteok
         * @param $siteerror
         * @param $usejq
         * @param $useui
         * @param $usebs
         * @param $frontendLang
         * @param $fcParams
         * @return string
         */
        public function getFormContent(
            $projektId,
            $siteok,
            $siteerror,
            $usejq,
            $useui,
            $usebs,
            $frontendLang,
            $fcParams
        ) {

            $okUrl = $siteok;
            $errorUrl = $siteerror;
            $sessionID = $GLOBALS['TSFE']->fe_user->id;

            $GLOBALS['gFcUrl'] = trim($GLOBALS['gFcUrl'], " /\t\n\r\0\x0B");
            $GLOBALS['gFcUrl'] .= '/';

            if (preg_match('~&lang=([^&]+)~', $fcParams, $matches) === 1) {

                $frontendLang = $matches[1];
                $fcParams = str_replace($matches[0], '', $fcParams);
            }

            return $GLOBALS['gFcUrl'] . 'form/provide/' . $projektId .
                '?xfc-rp-usejq=' . $usejq .
                '&xfc-rp-useui=' . $useui .
                '&xfc-rp-usebs=' . $usebs .
                '&xfc-rp-inline=true' .
                '&lang=' . $frontendLang .
                '&xfc-pp-external=true' .
                '&xfc-pp-base-url=' . $GLOBALS['gFcUrl'] .
                '&xfc-pp-success-url=' . $okUrl .
                '&xfc-pp-error-url=' . $errorUrl .
                '&xfc-rp-keepalive=false' .
                $fcParams;
        }

        /**
         * @return string
         */
        public function getFcIframeUrl()
        {
            return $GLOBALS['gFcUrl'] . '/external';
        }

        /**
         * @return mixed
         */
        public function getFcAdministrationUrl()
        {

            return $GLOBALS['gFcUrl'];
        }

    }

}
