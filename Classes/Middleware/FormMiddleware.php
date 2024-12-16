<?php

namespace Xima\XmFormcycle\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Routing\PageArguments;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FormcycleServiceFactory $formcycleServiceFactory,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var PageArguments $pageArguments */
        $pageArguments = $request->getAttribute('routing');
        if ($pageArguments->getPageType() !== '1464705954') {
            return $handler->handle($request);
        }

        $params = $request->getQueryParams();
        if (!isset($params['formId'])) {
            return $this->responseFactory->createResponse(400);
        }

        $settings = new ElementSettings();
        $settings->formId = $params['formId'];

        $site = $request->getAttribute('site');
        $html = $this->formcycleServiceFactory->createFromSite($site)->getFormHtml($settings);

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($html);

        return $response;
    }
}
