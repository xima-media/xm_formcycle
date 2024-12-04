<?php

namespace Xima\XmFormcycle\Tests\Unit\Dto;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\ElementSettings;
use Xima\XmFormcycle\Dto\IntegrationMode;

class ElementSettingsTest extends UnitTestCase
{
    public function testBrokenElementSettings(): void
    {
        $cObj = $this->getAccessibleMock(ContentObjectRenderer::class);
        $settings = ElementSettings::createFromContentElement($cObj);
        self::assertEquals(0, $settings->successPid);
        self::assertEquals(0, $settings->errorPid);
        self::assertEquals('', $settings->formId);
        self::assertTrue($settings->loadFormcycleJquery);
        self::assertTrue($settings->loadFormcycleJqueryUi);
        self::assertEquals('', $settings->additionalParameters);
        self::assertEquals(IntegrationMode::Default, $settings->integrationMode);
    }
}
