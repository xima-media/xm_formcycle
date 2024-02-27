<?php

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XmFormcycle\Service\FormcycleService;

class FormcycleSelection extends AbstractFormElement
{

    public function render()
    {
        /** @var FormcycleService $fcService */
        $fcService = GeneralUtility::makeInstance(FormcycleService::class);
        $forms = $fcService->getAvailableForms();

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_formcycle/Resources/Private/Templates/Backend/FormcycleSelection.html');
        $view->assign('forms', $forms);


        $resultArray = $this->initializeResultArray();
        $resultArray['html'] = $view->render();
        return $resultArray;
    }
}
