<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    use HasFactory;

    const PARKING_SPOT_TYPE_MOTORCYCLE = "motorcycle";
    const PARKING_SPOT_TYPE_NORMAL = "normal";
}
