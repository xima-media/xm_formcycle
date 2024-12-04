<?php

namespace Xima\XmFormcycle\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final readonly class FormcycleServiceFactory
{
    public function __construct(private FrontendInterface $cache)
    {
    }

    public function createFromSite(Site $site): FormcycleService
    {
        return new FormcycleService(
            $this->cache,
            $site
        );
    }

    public function createFromPageUid(mixed $pageUid): FormcycleService
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageUid);
        return $this->createFromSite($site);
    }
}
