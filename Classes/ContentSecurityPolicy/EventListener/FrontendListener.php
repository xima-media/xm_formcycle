<?php

namespace Xima\XmFormcycle\ContentSecurityPolicy\EventListener;

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Event\PolicyMutatedEvent;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use Xima\XmFormcycle\Service\FormcycleService;

final class FrontendListener
{
    public function __construct(private readonly FormcycleService $formcycleService)
    {
    }

    public function __invoke(PolicyMutatedEvent $event): void
    {
        if ($event->scope->type->isBackend()) {
            return;
        }

        $formcycleUrl = $this->formcycleService->getCspUrl();

        $event->getCurrentPolicy()->extend(
            Directive::ConnectSrc,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::FrameSrc,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::FontSrc,
            new UriValue($formcycleUrl),
        );
    }
}
