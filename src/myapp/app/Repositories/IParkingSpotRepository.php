<?php

namespace app\Repositories;

interface IParkingSpotRepository
{
    public function getParkingSpotGroups(): array;

    public function getAvailSpotMapByGroupId(int $groupId): array;
}
