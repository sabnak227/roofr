<?php

namespace tests\Unit\Services\ParkingSlotTypes;

use app\Services\Types\Van;
use Tests\TestCase;

class VanTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */
    public function testVan($list, $expected): void
    {
        $entity = new Van();
        $slots = $entity->calcSpotByCodeAndType($list);

        $this->assertCount(count($expected), $slots);
        for ($i = 0; $i < count($slots); $i++) {
            $this->assertEquals($expected[$i], $slots[$i]);
        }
    }

    public static function dataSet()
    {
        return [
            [
                [100 => 'normal'],
                []
            ],[
                [100 => 'normal', 103 => 'normal'],
                []
            ],[
                [100 => 'normal', 101 => 'normal', 102 => 'normal', 103 => 'normal'],
                [100]
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 104 => 'normal'],
                [102]
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 105 => 'normal', 106 => 'normal'],
                []
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 104 => 'normal', 105 => 'normal'],
                [102]
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 104 => 'normal', 105 => 'normal', 201 => 'normal', 202 => 'normal', 203 => 'normal'],
                [102, 201]
            ],[
                [100 => 'motorcycle'],
                []
            ],[
                [100 => 'motorcycle', 103 => 'normal'],
                []
            ],[
                [100 => 'motorcycle', 101 => 'normal', 102 => 'normal', 103 => 'normal'],
                [101]
            ],[
                [100 => 'motorcycle', 102 => 'normal', 103 => 'normal', 104 => 'normal'],
                [102]
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 105 => 'normal', 106 => 'normal'],
                []
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'motorcycle', 104 => 'normal', 105 => 'normal'],
                []
            ],[
                [100 => 'normal', 102 => 'normal', 103 => 'normal', 104 => 'motorcycle', 105 => 'normal', 201 => 'normal', 202 => 'normal', 203 => 'normal'],
                [201]
            ],
        ];
    }
}
