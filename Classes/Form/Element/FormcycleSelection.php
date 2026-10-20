<?php

namespace Xima\XmFormcycle\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;
use Xima\XmFormcycle\Error\FormcycleConnectionException;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormcycleSelection extends AbstractFormElement
{
    public function __construct(
        protected ViewFactoryInterface $viewFactory,
    ) {}

    public function render(): array
    {
        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult(
            $this->initializeResultArray(),
            $fieldInformationResult,
            false
        );

        $view = $this->viewFactory->create(new ViewFactoryData(
            templateRootPaths: ['EXT:xm_formcycle/Resources/Private/Templates/Backend/'],
        ));

        $view->assign('itemFormElValue', $this->data['parameterArray']['itemFormElValue']);

        try {
            $factory = GeneralUtility::makeInstance(FormcycleServiceFactory::class);
            $site = $this->data['site'];
            if (!$site instanceof NullSite) {
                $fcService = $factory->createFromSite($site);
            } else {
                $fcService = $factory->createFromPageUid($this->data['effectivePid'] ?? 0);
            }
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
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $view->assign('errorCode', $errorCode);
            $view->assign('errorType', 'unknown');
        }

        $hiddenInput = sprintf(
            '<input type="hidden" name="%s" id="%s" value="%s" data-formengine-input-name="%s" />',
            htmlspecialchars((string)$this->data['parameterArray']['itemFormElName']),
            $this->data['fieldName'],
            htmlspecialchars((string)$this->data['parameterArray']['itemFormElValue'], ENT_QUOTES),
            htmlspecialchars((string)$this->data['parameterArray']['itemFormElName']),
        );

        $resultArray['html'] = '<div class="formengine-field-item t3js-formengine-field-item">' . $hiddenInput . '</div><div id="xm-formcycle-forms" class="open">' . $view->render('FormcycleSelection') . '</div>';
        $resultArray['stylesheetFiles'][] = 'EXT:xm_formcycle/Resources/Public/Css/Backend/FormcycleSelection.css';

        $resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create('@xima/xm-formcycle/FormcycleSelectionElement.js')
                ->instance($this->data['fieldName'], $this->data['effectivePid']);

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
            if ($b === 0) {
                return -1;
            }
            return $a > $b ? 0 : 1;
        });
        return $groupedForms;
    }
}
