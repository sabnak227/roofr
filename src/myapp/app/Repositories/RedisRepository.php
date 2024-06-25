<?php

namespace app\Repositories;

use Illuminate\Support\Facades\Redis;

class RedisRepository implements IRedisRepository
{
    public function setAvailSlot(string $type, int $groupId, array $data): void
    {
        $key = "{parking_slots:$type}:$groupId";
        Redis::del($key);
        foreach ($data as $item) {
            Redis::sadd($key, $item);
        }
    }

    public function checkAvailSpotKeyExists(string $type, int $groupId): bool
    {
        $key = "{parking_slots:$type}:$groupId";
        return Redis::exists($key);
    }

    public function getAvailSpotCount(string $type, int $groupId): int
    {
        $key = "{parking_slots:$type}:$groupId";
        return Redis::scard($key);
    }
}
