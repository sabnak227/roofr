<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Redis;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::flushDB();
    }

    protected function tearDown(): void
    {
        Redis::flushDB();
        parent::tearDown();
    }
}
