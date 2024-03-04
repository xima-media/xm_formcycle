<?php

namespace Xima\XmFormcycle\Tests\Unit\Form\Element;

use PHPUnit\Framework\TestCase;
use Xima\XmFormcycle\Form\Element\FormcycleSelection;

class FormcycleSelectionTest extends TestCase
{
    public function testGroupForms(): void
    {
        self::assertEmpty(FormcycleSelection::groupForms([]));

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

        $result = FormcycleSelection::groupForms($arr);

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
