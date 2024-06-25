<?php

namespace Database\Seeders;

use App\Models\ParkingSpot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingSpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 10; $i++) {
            for ($j = 0; $j < $i; $j++) {
                $code = $i * 100 + $j;
                ParkingSpot::create([
                    'group_id' => $i,
                    'code' => $code,
                ]);
            }
        }

    }
}
