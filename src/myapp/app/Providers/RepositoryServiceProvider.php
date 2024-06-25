<?php

namespace App\Providers;

use app\Repositories\IParkingSpotRepository;
use app\Repositories\IRedisRepository;
use app\Repositories\ParkingSpotRepository;
use app\Repositories\RedisRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    // Register bindings, interface => class
    public $bindings = [
        IParkingSpotRepository::class => ParkingSpotRepository::class,
        IRedisRepository::class => RedisRepository::class
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IParkingSpotRepository::class, ParkingSpotRepository::class);
        $this->app->singleton(IRedisRepository::class, RedisRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
