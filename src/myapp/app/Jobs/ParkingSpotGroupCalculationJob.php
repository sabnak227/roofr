<?php

namespace App\Jobs;

use app\Services\ParkingSpotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParkingSpotGroupCalculationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $groupId;
    private ParkingSpotService $parkingSpotService;

    /**
     * Create a new job instance.
     */
    public function __construct(int $groupId)
    {
        $this->groupId = $groupId;
        $this->parkingSpotService = resolve(ParkingSpotService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->parkingSpotService->calculateParkingSpotByGroupId($this->groupId);
    }
}
