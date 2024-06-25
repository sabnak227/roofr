<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use app\Services\ParkingSpotService;

class ParkingLotController extends Controller
{
    private ParkingSpotService $parkingSpotService;

    public function __construct()
    {
        $this->parkingSpotService = resolve(ParkingSpotService::class);
    }

    public function index()
    {
        $result = $this->parkingSpotService->getSlotOverview();
        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function get(ParkingSpot $parkingSpot)
    {
        return response()->json([
            'success' => true,
            'data' => $parkingSpot,
        ]);
    }
}
