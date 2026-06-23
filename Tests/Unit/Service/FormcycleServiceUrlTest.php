<?php

namespace Xima\XmFormcycle\Tests\Unit\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Settings\Settings;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormcycleServiceUrlTest extends UnitTestCase
{
    private \Xima\XmFormcycle\Service\FormcycleService $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $cache = $this->createMock(FrontendInterface::class);

        $site = $this->createMock(Site::class);
        $site->method('getSettings')->willReturn(
            SiteSettings::create(new Settings([
                'formcycle.clientId' => '1',
                'formcycle.url' => 'https://example.com/formcycle/',
                'formcycle.defaultIntegrationMode' => 'integrated',
            ]))
        );

        $connectionPool = $this->createMock(ConnectionPool::class);

        $factory = new FormcycleServiceFactory($cache, $connectionPool);
        $this->subject = $factory->createFromSite($site);
    }

    private function makeSettings(string $additionalParameters = ''): ElementSettings
    {
        $settings = new ElementSettings();
        $settings->formId = '42';
        $settings->additionalParameters = $additionalParameters;
        $settings->integrationMode = IntegrationMode::Ajax;
        $settings->language = 'de';
        return $settings;
    }

    public function testAdditionalParamAppearsInIframeUrl(): void
    {
        $url = $this->subject->getIframeUrl($this->makeSettings('sel_EmpfaengerLRA=4'));
        self::assertStringContainsString('sel_EmpfaengerLRA=4', $url);
    }

    public function testAdditionalParamAppearsInIntegratedUrl(): void
    {
        $url = $this->subject->getIntegratedFormUrl($this->makeSettings('sel_EmpfaengerLRA=4'));
        self::assertStringContainsString('sel_EmpfaengerLRA=4', $url);
    }

    public function testAdditionalParamAppearsInAjaxUrl(): void
    {
        $url = $this->subject->getAjaxUrl($this->makeSettings('sel_EmpfaengerLRA=4'));
        self::assertStringContainsString('sel_EmpfaengerLRA=4', $url);
    }

    public function testMultipleAdditionalParamsAppearInUrl(): void
    {
        $url = $this->subject->getIframeUrl($this->makeSettings('foo=bar&baz=qux'));
        self::assertStringContainsString('foo=bar', $url);
        self::assertStringContainsString('baz=qux', $url);
    }

    public function testEmptyAdditionalParamsDoNotCorruptUrl(): void
    {
        $url = $this->subject->getIframeUrl($this->makeSettings(''));
        self::assertStringContainsString('xfc-rp-inline=1', $url);
        self::assertStringNotContainsString('=&', $url);
    }
}
