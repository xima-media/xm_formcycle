<?php

namespace Xima\XmFormcycle\Tests\Unit\Dto;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Dto\IntegrationMode;

class IntegrationModeTest extends UnitTestCase
{
    public function testForDataProcessing(): void
    {
        self::assertEquals(IntegrationMode::Ajax, IntegrationMode::AjaxTypo3->forDataProcessing());
        self::assertEquals(IntegrationMode::Ajax, IntegrationMode::AjaxFormcycle->forDataProcessing());
        self::assertEquals(IntegrationMode::Integrated, IntegrationMode::Integrated->forDataProcessing());
        self::assertEquals(IntegrationMode::Iframe, IntegrationMode::Iframe->forDataProcessing());
        self::assertEquals(IntegrationMode::Default, IntegrationMode::Default->forDataProcessing());
    }

    public function testFromDatabase(): void
    {
        self::assertEquals(IntegrationMode::Default, IntegrationMode::fromDatabase(0));
        self::assertEquals(IntegrationMode::Integrated, IntegrationMode::fromDatabase(1));
        self::assertEquals(IntegrationMode::AjaxTypo3, IntegrationMode::fromDatabase(2));
        self::assertEquals(IntegrationMode::AjaxFormcycle, IntegrationMode::fromDatabase(3));
        self::assertEquals(IntegrationMode::Iframe, IntegrationMode::fromDatabase(4));
        self::assertEquals(IntegrationMode::Default, IntegrationMode::fromDatabase(5));
    }

    public function testFromSiteSettings(): void
    {
        self::assertEquals(IntegrationMode::Integrated, IntegrationMode::fromSiteSettings(''));
        self::assertEquals(IntegrationMode::Iframe, IntegrationMode::fromSiteSettings('iframe'));
        self::assertEquals(IntegrationMode::AjaxTypo3, IntegrationMode::fromSiteSettings('ajax_typo3'));
        self::assertEquals(IntegrationMode::AjaxFormcycle, IntegrationMode::fromSiteSettings('ajax_formcycle'));
        self::assertEquals(IntegrationMode::Integrated, IntegrationMode::fromSiteSettings('random'));
    }
}
