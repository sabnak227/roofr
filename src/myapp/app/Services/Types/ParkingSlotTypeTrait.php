<?php

namespace app\Services\Types;

trait ParkingSlotTypeTrait
{
    /**
     * Get vehicle type
     * @return string
     */
    public function getType(): string
    {
        return self::VALUE;
    }

    /**
     * Calculate available slots of the current vehile type based on
     * the available slots in a parking spot group
     *
     * @param array $codeTypeMap a map of parking slot code and type
     * @return array
     */
    public function calcSpotByCodeAndType(array $codeTypeMap): array
    {
        $codes = array_keys($codeTypeMap);
        $types = array_values($codeTypeMap);
        $results = [];
        $lookupSize = $this->getSlotSizeByVehicleType();
        for($i = 0; $i < count($codes); $i++) {
            $cnt = 0;

            for ($j = 0; $j < $lookupSize; $j++) {
                if(!isset($codes[$i + $j])) {
                    // since the input code array is sorted
                    // if no further items are found, means that
                    // we already hits the end of the list
                    break;
                }

                if($codes[$i + $j] - $codes[$i] != $j) {
                    // check if the codes are consecutively adjacent
                    // to each other
                    break;
                }

                if(!$this->canPark($types[$i + $j])) {
                    // check if the vehicle type is eligible to park here
                    break;
                }

                $cnt++;
            }

            if($cnt == $lookupSize) {
                // attach the left most value as the final result
                $results[] = $codes[$i];
                // skip the consecutively adjacent slots
                $i = $i + $lookupSize - 1;
            }
        }
        return $results;

    }

    /**
     * Get the vehicle slot size configuration
     *
     * @return int
     */
    protected function getSlotSizeByVehicleType(): int
    {
        return config("parking.slot_size." . self::VALUE);
    }
}
