<?php

namespace Xima\XmFormcycle\ContentSecurityPolicy\EventListener;

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Event\PolicyMutatedEvent;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;

final readonly class FrontendListener
{
    public function __invoke(PolicyMutatedEvent $event): void
    {
        if ($event->scope->type->isBackend()) {
            return;
        }

        $formcycleUrl = $event->scope->site->getSettings()->get('formcycle.url') ?? '';
        if (!$formcycleUrl) {
            return;
        }

        $event->getCurrentPolicy()->extend(
            Directive::ConnectSrc,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::FrameSrc,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::StyleSrc,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::StyleSrcElem,
            new UriValue($formcycleUrl),
        );

        $event->getCurrentPolicy()->extend(
            Directive::FontSrc,
            new UriValue($formcycleUrl),
        );
    }
}
