<?php

namespace app\Repositories;

use App\Models\ParkingSpot;
use Illuminate\Support\Facades\DB;

class ParkingSpotRepository implements IParkingSpotRepository
{
    public function getParkingSpotGroups(): array
    {
        return DB::table('parking_spots')
            ->select(['group_id'])
            ->groupBy('group_id')
            ->get()
            ->pluck('group_id')
            ->toArray();
    }

    public function getAvailSpotMapByGroupId(int $groupId): array
    {
        $result = ParkingSpot::where('group_id', $groupId)
            ->whereNull('vehicle_type')
            ->orderBy('code', 'asc')
            ->get();

        return array_combine(
            $result->pluck('code')->toArray(),
            $result->pluck('type')->toArray()
        );
    }
}
