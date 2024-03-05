<?php

namespace Xima\XmFormcycle\Tests\Functional\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Xima\XmFormcycle\Service\FormcycleService;

class FormcycleServiceTest extends FunctionalTestCase
{
    protected array $configurationToUseInTestInstance = [
        'EXTENSIONS' => [
            'xm_formcycle' => [
                'formcycleClientId' => '2252',
                'formcycleUrl' => 'https://pro.form.cloud/formcycle/',
                'defaultIntegrationMode' => 'integrated',
            ],
        ],
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/xm_formcycle',
    ];

    private FormcycleService $subject;

    public function setUp(): void
    {
        parent::setUp();

        $extConf = $this->get(ExtensionConfiguration::class);
        $cache = $this->createMock(FrontendInterface::class);
        $uriBuilder = $this->get(UriBuilder::class);

        $this->subject = new FormcycleService($extConf, $cache, $uriBuilder);
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
