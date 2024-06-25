<?php

namespace App\Providers;

use app\Services\Types\Car;
use app\Services\Types\IParkingSlotType;
use app\Services\Types\MotorCycle;
use app\Services\Types\Van;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ParkingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $implementations = [
            MotorCycle::class,
            Car::class,
            Van::class,
        ];
        foreach ($implementations as $implementation) {
            $this->app->singleton(IParkingSlotType::class, function ($app) use ($implementation) {
                return $app->make($implementation);
            });

            $this->app->tag($implementation, 'parkingSlotType');
        }

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
