<?php

namespace app\Services\Types;

interface IParkingSlotType
{
    public function getType(): string;

    public function calcSpotByCodeAndType(array $codes): array;

    public function canPark(string $spotType): bool;
}
