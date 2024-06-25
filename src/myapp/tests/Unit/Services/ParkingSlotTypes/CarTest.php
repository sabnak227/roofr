<?php

namespace tests\Unit\Services\ParkingSlotTypes;

use app\Services\Types\Car;
use Tests\TestCase;

class CarTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */
    public function testCar($list, $expected): void
    {
        $entity = new Car();
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
                []
            ],[
                [100=>'normal', 101=>'motorcycle', 102=>'normal', 103=>'normal'],
                [100, 102, 103]
            ],[
                [100=>'normal', 103=>'motorcycle'],
                [100]
            ]
        ];
    }
}
