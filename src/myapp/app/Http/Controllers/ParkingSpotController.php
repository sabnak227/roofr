<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParkingSpotRequest;
use App\Models\ParkingSpot;
use app\Services\ParkingSpotService;

class ParkingSpotController extends Controller
{

    private ParkingSpotService $parkingSpotService;

    public function __construct()
    {
        $this->parkingSpotService = resolve(ParkingSpotService::class);
    }

    public function park(ParkingSpotRequest $request, ParkingSpot $parkingSpot)
    {
        $this->parkingSpotService->park($parkingSpot, $request->vehicle_type);
        return response()->json([
            'success' => true,
        ]);
    }


    public function unpark(ParkingSpot $parkingSpot)
    {
        $this->parkingSpotService->unpark($parkingSpot);
        return response()->json([
            'success' => true,
        ]);
    }
}
