<?php

namespace Xima\XmFormcycle\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final readonly class FormcycleServiceFactory
{
    public function __construct(private FrontendInterface $cache, private UriBuilder $uriBuilder)
    {
    }

    public function createFromSiteSettings(SiteSettings $settings): FormcycleService
    {
        return new FormcycleService(
            $this->cache,
            $this->uriBuilder,
            $settings
        );
    }

    public function createFromPageUid(mixed $pageUid): FormcycleService
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageUid);
        $settings = $site->getSettings();
        return $this->createFromSiteSettings($settings);
    }
}
