<?php

namespace Xima\XmFormcycle\Tests\Unit\Dto;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\FormcycleConfiguration;
use Xima\XmFormcycle\Error\FormcycleConfigurationException;

class FormcycleConfigurationTest extends UnitTestCase
{
    private array $validExtensionConfiguration = [
        'formCycleUrl' => 'https://example.com',
        'formCycleFrontendUrl' => '',
        'formCycleUser' => 'username',
        'formCyclePass' => 'password',
        'integrationMode' => '',
    ];

    public function testCreateFromExtensionConfiguration(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        FormcycleConfiguration::createFromExtensionConfiguration([]);
    }

    public function testInvalidFormcycleUrl(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709041996);
        $this->validExtensionConfiguration['formCycleUrl'] = 'x';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testInvalidFormcycleFrontendUrl(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709052152);
        $this->validExtensionConfiguration['formCycleFrontendUrl'] = 'x';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testMissingUsername(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709052037);
        $this->validExtensionConfiguration['formCycleUser'] = '';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testMissingPassword(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709052037);
        $this->validExtensionConfiguration['formCyclePass'] = '';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }

    public function testInvalidIntegrationMode(): void
    {
        $this->expectException(FormcycleConfigurationException::class);
        $this->expectExceptionCode(1709052040);
        $this->validExtensionConfiguration['integrationMode'] = 'ajax';
        FormcycleConfiguration::createFromExtensionConfiguration($this->validExtensionConfiguration);
    }
}
