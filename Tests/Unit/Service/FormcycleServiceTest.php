<?php

namespace Xima\XmFormcycle\Tests\Unit\Service;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Xima\XmFormcycle\Service\FormcycleService;

class FormcycleServiceTest extends UnitTestCase
{
    public function testGroupForms(): void
    {
        self::assertEmpty(FormcycleService::groupForms([]));

        $arr = [
            [
                'group' => 'b',
                'name' => 'Foo',
            ],
            [
                'name' => 'Bar',
            ],
            [
                'group' => 'a',
                'name' => 'Hossa',
            ],
        ];

        $result = FormcycleService::groupForms($arr);

        self::assertEquals([
            'a' => [
                [
                    'group' => 'a',
                    'name' => 'Hossa',
                ],
            ],
            'b' => [
                [
                    'group' => 'b',
                    'name' => 'Foo',
                ],
            ],
            0 => [
                [
                    'name' => 'Bar',
                ],
            ],
        ], $result);
    }
}
