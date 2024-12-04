<?php

namespace Xima\XmFormcycle\Tests\Functional\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Xima\XmFormcycle\Service\FormcycleService;
use Xima\XmFormcycle\Service\FormcycleServiceFactory;

class FormcycleServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/xm_formcycle',
    ];

    private FormcycleService $subject;

    public function setUp(): void
    {
        parent::setUp();

        $cache = $this->createMock(FrontendInterface::class);

        $site = $this->createMock(Site::class);
        $site->method('getSettings')->willReturn(
            new SiteSettings([
                'formcycle.clientId' => '2252',
                'formcycle.url' => 'https://pro.form.cloud/formcycle/',
                'formcycle.defaultIntegrationMode' => 'integrated',
            ], [], [])
        );

        $factory = new FormcycleServiceFactory($cache);

        $this->subject = $factory->createFromSite($site);
    }

    public function testLoadAvailableForms(): void
    {
        $forms = $this->subject->getAvailableForms();
        self::assertIsArray($forms);
        self::assertGreaterThanOrEqual(3, count($forms), 'At least 3 test forms in response');
        self::assertArrayHasKey('form_id', $forms[0], 'Formcycle response: form_id is set');
        self::assertArrayHasKey('thumbnail', $forms[0], 'Formcycle response: thumbnail is set');
    }
}
