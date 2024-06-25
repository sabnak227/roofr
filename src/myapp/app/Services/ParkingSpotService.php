<?php

namespace app\Services;

use App\Jobs\ParkingSpotGroupCalculationJob;
use App\Models\ParkingSpot;
use app\Repositories\IParkingSpotRepository;
use app\Repositories\IRedisRepository;
use app\Services\Types\Car;
use app\Services\Types\IParkingSlotType;
use app\Services\Types\MotorCycle;
use app\Services\Types\Van;

class ParkingSpotService
{
    private IParkingSpotRepository $parkingSpotRepository;
    private IRedisRepository $redisRepository;
    public function __construct()
    {
        $this->parkingSpotRepository = resolve(IParkingSpotRepository::class);
        $this->redisRepository = resolve(IRedisRepository::class);
    }

    const VEHICLE_TYPES = [
        Car::VALUE,
        MotorCycle::VALUE,
        Van::VALUE,
    ];

    /**
     * Get slot overview by adding the each parking slot group's redis list size
     *
     * @return array
     */
    public function getSlotOverview()
    {
        $groupIds = $this->parkingSpotRepository->getParkingSpotGroups();

        $result = [];

        $parkingSlotTypes = app()->tagged('parkingSlotType');
        foreach ($groupIds as $groupId) {
            foreach ($parkingSlotTypes as $parkingSlotType) {
                /** @var IParkingSlotType $parkingSlotType */
                $type = $parkingSlotType->getType();
                if(!$this->redisRepository->checkAvailSpotKeyExists($type, $groupId)) {
                    $this->calculateParkingSpotByGroupId($groupId);
                }
                $cnt = $this->redisRepository->getAvailSpotCount($type, $groupId);
                if(!isset($result[$type])) {
                    $result[$type] = 0;
                }
                $result[$type] += $cnt;
            }
        }

        return $result;
    }

    /**
     * Park a vehicle, update db status and trigger parking slot group recalculation
     *
     * @param ParkingSpot $parkingSpot
     * @param string $vehicleType
     * @return void
     */
    public function park(ParkingSpot $parkingSpot, string $vehicleType)
    {
        $parkingSpot->vehicle_type = $vehicleType;
        $parkingSpot->save();

        // recalculate parking spot group status
        ParkingSpotGroupCalculationJob::dispatch($parkingSpot->group_id);
    }

    /**
     * Unpark a vehicle, update db status and trigger parking slot group recalculation
     *
     * @param ParkingSpot $parkingSpot
     * @return void
     */
    public function unpark(ParkingSpot $parkingSpot)
    {
        $parkingSpot->vehicle_type = null;
        $parkingSpot->save();

        // recalculate parking spot group status
        ParkingSpotGroupCalculationJob::dispatch($parkingSpot->group_id);
    }

    /**
     * Calculate how many available spots within a parking group
     *
     * @param int $groupId
     * @return void
     */
    public function calculateParkingSpotByGroupId(int $groupId)
    {
        // get the available parking spot codes;
        $availSpots = $this->parkingSpotRepository
            ->getAvailSpotMapByGroupId($groupId);

        $parkingSlotTypes = app()->tagged('parkingSlotType');
        foreach ($parkingSlotTypes as $parkingSlotType) {
            /** @var IParkingSlotType $parkingSlotType */
            $slots = $parkingSlotType->calcSpotByCodeAndType($availSpots);
            $this->redisRepository->setAvailSlot($parkingSlotType->getType(), $groupId, $slots);
        }
    }

}
