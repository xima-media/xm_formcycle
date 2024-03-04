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
    }
}
