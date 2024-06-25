<?php

namespace app\Services\Types;

use App\Models\ParkingSpot;

class Car implements IParkingSlotType
{
    use ParkingSlotTypeTrait;
    const VALUE = 'car';

    public function canPark(string $spotType): bool
    {
        return $spotType == ParkingSpot::PARKING_SPOT_TYPE_NORMAL;
    }
}
