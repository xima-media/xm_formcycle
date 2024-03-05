<?php

namespace Xima\XmFormcycle\Tests\Unit\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
use Xima\XmFormcycle\Dto\IntegrationMode;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

class FormcycleConfigurationTest extends UnitTestCase
{
    private array $validExtensionConfiguration = [
        'formcycleUrl' => 'https://example.com',
        'formcycleClientId' => '123456',
        'integrationMode' => '',
    ];

    public function testCreateFromEmptyExtensionConfiguration(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        FormcycleConfiguration::createFromExtensionConfiguration([]);
    }

    public function testCreateValidExtensionConfiguration(): void
    {
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testInvalidFormcycleUrl(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709041996);
        $this->validExtensionConfiguration['formcycleUrl'] = 'x';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testMissingClientId(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709538688);
        $this->validExtensionConfiguration['formcycleClientId'] = '';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testDefaultIntegrationMode(): void
    {
        $config = FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
        self::assertEquals(IntegrationMode::Integrated, $config->getIntegrationMode());
    }

    public function testValidFormcycleUrls()
    {
        $config = FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
        self::assertTrue(GeneralUtility::isValidUrl($config->getAdminUrl()), 'Valid admin url');
        self::assertTrue(GeneralUtility::isValidUrl($config->getFormListUrl()), 'Valid form list url');
        self::assertTrue(GeneralUtility::isValidUrl($config->getFormCycleUrl()), 'Valid form cycle url');
    }
}
