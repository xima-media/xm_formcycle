<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceKeyword;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceScheme;
use TYPO3\CMS\Core\Type\Map;

return Map::fromEntries([
    Scope::frontend(),

    new MutationCollection(
        new Mutation(
            MutationMode::Set,
            Directive::ScriptSrc,
            SourceKeyword::self,
            SourceKeyword::unsafeInline
        ),
        new Mutation(
            MutationMode::Set,
            Directive::StyleSrcElem,
            SourceKeyword::self,
            SourceKeyword::unsafeInline
        ),
        new Mutation(
            MutationMode::Extend,
            Directive::FontSrc,
            SourceScheme::data
        )
    ),
]);
