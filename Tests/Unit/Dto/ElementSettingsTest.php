<?php

namespace Xima\XmFormcycle\Tests\Unit\Dto;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\ElementSettings;

class ElementSettingsTest extends UnitTestCase
{
    public function testBrokenElementSettings(): void
    {
        $cObj = $this->getAccessibleMock(ContentObjectRenderer::class);
        $flexFormService = $this->getAccessibleMock(FlexFormService::class);
        $settings = ElementSettings::createFromContentElement($flexFormService, $cObj);
        self::assertEquals(0, $settings->successPid);
        self::assertEquals(0, $settings->errorPid);
        self::assertEquals('', $settings->formId);
        self::assertTrue($settings->loadFormcycleJquery);
        self::assertFalse($settings->loadFormcycleJqueryUi);
        self::assertEquals('', $settings->additionalParameters);
        self::assertNull($settings->integrationMode);
    }
}
