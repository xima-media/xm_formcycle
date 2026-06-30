<?php

namespace Xima\XmFormcycle\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use Xima\XmFormcycle\Form\Element\FormcycleSelection;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

final readonly class BackendController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private FormcycleServiceFactory $formcycleServiceFactory,
        protected ViewFactoryInterface $viewFactory
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

        $view = $this->viewFactory->create(new ViewFactoryData(
            templateRootPaths: ['EXT:xm_formcycle/Resources/Private/Templates/Backend/'],
            request: $request,
        ));
        $view->assign('forms', $forms);
        $html = $view->render('FormcycleSelection');

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(
            json_encode(['html' => $html], JSON_THROW_ON_ERROR)
        );
        return $response;
    }
}
