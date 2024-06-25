<?php

namespace app\Services\Types;

use App\Models\ParkingSpot;

class Van implements IParkingSlotType
{
    use ParkingSlotTypeTrait;
    const VALUE = 'van';

    public function canPark(string $spotType): bool
    {
        return $spotType == ParkingSpot::PARKING_SPOT_TYPE_NORMAL;
    }
}
