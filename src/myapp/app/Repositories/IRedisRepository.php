<?php

namespace app\Repositories;

use Illuminate\Support\Facades\Redis;

interface IRedisRepository
{
    public function setAvailSlot(string $type, int $groupId, array $data): void;

    public function checkAvailSpotKeyExists(string $type, int $groupId): bool;

    public function getAvailSpotCount(string $type, int $groupId): int;
}
