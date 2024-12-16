<?php

namespace Xima\XmFormcycle\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XmFormcycle\Form\Element\FormcycleSelection;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

final class BackendController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly FormcycleServiceFactory $formcycleServiceFactory
    ) {
    }

    public function reloadAvailableForms(ServerRequestInterface $request): ResponseInterface
    {
        $pageUid = $request->getQueryParams()['pageUid'] ?? 0;
        $this->formcycleServiceFactory->createFromPageUid($pageUid)->resetAvailableFormsCache();

        return $this->getAvailableForms($request);
    }

    public function getAvailableForms(ServerRequestInterface $request): ResponseInterface
    {
        $pageUid = $request->getQueryParams()['pageUid'] ?? 0;
        $forms = $this->formcycleServiceFactory->createFromPageUid($pageUid)->getAvailableForms();
        $forms = FormcycleSelection::groupForms($forms);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename('EXT:xm_formcycle/Resources/Private/Templates/Backend/FormcycleSelection.html');
        $view->assign('forms', $forms);
        $html = $view->render();

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(
            json_encode(['html' => $html], JSON_THROW_ON_ERROR)
        );
        return $response;
    }
}
