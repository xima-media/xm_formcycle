<?php

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;
use Xima\XmFormcycle\Service\FormcycleService;

class FormcycleSelection extends AbstractFormElement
{
    public function render()
    {
        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult(
            $this->initializeResultArray(),
            $fieldInformationResult,
            false
        );

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_formcycle/Resources/Private/Templates/Backend/FormcycleSelection.html');
        $view->assign('itemFormElValue', $this->data['parameterArray']['itemFormElValue']);

        try {
            /** @var FormcycleService $fcService */
            $fcService = GeneralUtility::makeInstance(FormcycleService::class);
            $view->assign('adminUrl', $fcService->getAdminUrl());
            if ($fcService->hasAvailableFormsCached()) {
                $forms = $fcService->getAvailableForms();
                $forms = self::groupForms($forms);
                $view->assign('forms', $forms);
            } else {
                $view->assign('loading', true);
            }
        } catch (FormcycleConfigurationException $e) {
            $errorCode = $e->getCode();
            $view->assign('errorCode', $errorCode);
            $view->assign('errorType', 'configuration');
        } catch (FormcycleConnectionException $e) {
            $errorCode = $e->getCode();
            $view->assign('errorCode', $errorCode);
            $view->assign('errorType', 'connection');
        }

        $hiddenInput = sprintf(
            '<input type="hidden" name="%s" id="%s" value="%s" data-formengine-input-name="%s" />',
            htmlspecialchars($this->data['parameterArray']['itemFormElName']),
            $this->data['parameterArray']['itemFormElID'],
            htmlspecialchars($this->data['parameterArray']['itemFormElValue'], ENT_QUOTES),
            htmlspecialchars($this->data['parameterArray']['itemFormElName']),
        );

        $resultArray['html'] = '<div class="formengine-field-item t3js-formengine-field-item">' . $hiddenInput . '</div><div id="xm-formcycle-forms" class="open">' . $view->render() . '</div>';
        $resultArray['stylesheetFiles'][] = 'EXT:xm_formcycle/Resources/Public/Css/Backend/FormcycleSelection.css';

        $resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create('@xima/xm-formcycle/FormcycleSelectionElement.js')
            ->instance($this->data['parameterArray']['itemFormElID']);

        return $resultArray;
    }

    public static function groupForms(array $forms): array
    {
        $groupedForms = [];
        foreach ($forms as $form) {
            $index = $form['group'] ?? 0;
            $groupedForms[$index] ??= [];
            $groupedForms[$index][] = $form;
        }
        // sort "others" group (index=0) to end of array
        uksort($groupedForms, static function ($a, $b) {
            return $b === 0 ? -1 : $a > $b;
        });
        return $groupedForms;
    }
}
