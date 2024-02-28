<?php

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;
use Xima\XmFormcycle\Service\FormcycleService;

class FormcycleSelection extends AbstractFormElement
{
    public function render()
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_formcycle/Resources/Private/Templates/Backend/FormcycleSelection.html');

        try {
            /** @var FormcycleService $fcService */
            $fcService = GeneralUtility::makeInstance(FormcycleService::class);
            $forms = $fcService->getAvailableForms();
            $forms = FormcycleService::groupForms($forms);
            $view->assign('forms', $forms);
        } catch (FormcycleConfigurationException $e) {
            $errorCode = $e->getCode();
            $view->assign('errorCode', $errorCode);
            $view->assign('errorType', 'configuration');
        } catch (FormcycleConnectionException $e) {
            $errorCode = $e->getCode();
            $view->assign('errorCode', $errorCode);
            $view->assign('errorType', 'connection');
        }

        $resultArray = $this->initializeResultArray();
        $resultArray['html'] = $view->render();
        return $resultArray;
    }
}
