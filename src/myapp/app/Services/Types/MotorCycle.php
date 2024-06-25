<?php

namespace app\Services\Types;

use App\Models\ParkingSpot;

class MotorCycle implements IParkingSlotType
{
    use ParkingSlotTypeTrait;
    const VALUE = 'motorcycle';

    public function canPark(string $spotType): bool
    {
        return $spotType == ParkingSpot::PARKING_SPOT_TYPE_MOTORCYCLE
            || $spotType == ParkingSpot::PARKING_SPOT_TYPE_NORMAL;
    }
}
