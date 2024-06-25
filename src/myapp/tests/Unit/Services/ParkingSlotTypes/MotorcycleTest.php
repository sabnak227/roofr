<?php

namespace tests\Unit\Services\ParkingSlotTypes;

use app\Services\Types\MotorCycle;
use Tests\TestCase;

class MotorcycleTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */
    public function testMotorCycle($list, $expected): void
    {
        $entity = new MotorCycle();
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
                [100=>'normal'],
                [100]
            ],[
                [100=>'motorcycle'],
                [100]
            ],[
                [100=>'normal', 101=>'motorcycle', 102=>'normal', 103=>'normal'],
                [100, 101, 102, 103]
            ],[
                [100=>'normal', 103=>'motorcycle'],
                [100, 103]
            ]
        ];
    }
}
