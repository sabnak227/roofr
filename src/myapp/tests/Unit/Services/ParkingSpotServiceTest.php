<?php

namespace tests\Unit\Services;

use App\Models\ParkingSpot;
use app\Repositories\ParkingSpotRepository;
use app\Services\ParkingSpotService;
use app\Services\Types\Car;
use app\Services\Types\Van;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Redis;
use Mockery\MockInterface;
use Tests\TestCase;

class ParkingSpotServiceTest extends TestCase
{

    use DatabaseTransactions;

    private ParkingSpotService $parkingSpotService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parkingSpotService = resolve(ParkingSpotService::class);
    }

    public function testParkingGroupWithLessThan3Spots()
    {
        $this->parkingSpotService->calculateParkingSpotByGroupId(2);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:2');
        $this->assertCount(2, $resultA);
        $this->assertEquals($resultA[0], 200);
        $this->assertEquals($resultA[1], 201);
        $resultB = Redis::smembers('{parking_slots:car}:2');
        $this->assertCount(2, $resultB);
        $this->assertEquals($resultB[0], 200);
        $this->assertEquals($resultB[1], 201);
        $resultC = Redis::smembers('{parking_slots:van}:2');
        $this->assertCount(0, $resultC);
    }

    public function testParkingGroupWithMoreThan3Spots()
    {
        $this->parkingSpotService->calculateParkingSpotByGroupId(3);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:3');
        $this->assertCount(3, $resultA);
        $this->assertEquals($resultA[0], 300);
        $this->assertEquals($resultA[1], 301);
        $this->assertEquals($resultA[2], 302);
        $resultB = Redis::smembers('{parking_slots:car}:3');
        $this->assertCount(3, $resultB);
        $this->assertEquals($resultB[0], 300);
        $this->assertEquals($resultB[1], 301);
        $this->assertEquals($resultB[2], 302);
        $resultC = Redis::smembers('{parking_slots:van}:3');
        $this->assertCount(1, $resultC);
        $this->assertEquals($resultC[0], 300);
    }

    public function testParkingUnParkingCar()
    {
        $spot = ParkingSpot::where('code', 300)->first();
        $this->parkingSpotService->park($spot, Car::VALUE);

        $this->parkingSpotService->calculateParkingSpotByGroupId(3);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:3');
        $this->assertCount(2, $resultA);
        $this->assertEquals($resultA[0], 301);
        $this->assertEquals($resultA[1], 302);
        $resultB = Redis::smembers('{parking_slots:car}:3');
        $this->assertCount(2, $resultB);
        $this->assertEquals($resultB[0], 301);
        $this->assertEquals($resultB[1], 302);
        $resultC = Redis::smembers('{parking_slots:van}:3');
        $this->assertCount(0, $resultC);

        $this->parkingSpotService->unpark($spot);

        $this->parkingSpotService->calculateParkingSpotByGroupId(3);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:3');
        $this->assertCount(3, $resultA);
        $this->assertEquals($resultA[0], 300);
        $this->assertEquals($resultA[1], 301);
        $this->assertEquals($resultA[2], 302);
        $resultB = Redis::smembers('{parking_slots:car}:3');
        $this->assertCount(3, $resultB);
        $this->assertEquals($resultB[0], 300);
        $this->assertEquals($resultB[1], 301);
        $this->assertEquals($resultB[2], 302);
        $resultC = Redis::smembers('{parking_slots:van}:3');
        $this->assertCount(1, $resultC);
        $this->assertEquals($resultC[0], 300);
    }


    public function testParkingUnParkingVan()
    {
        // expecting all parking spot to trigger an api call individually
        $spot1 = ParkingSpot::where('code', 300)->first();
        $this->parkingSpotService->park($spot1, Van::VALUE);

        $spot2 = ParkingSpot::where('code', 301)->first();
        $this->parkingSpotService->park($spot2, Van::VALUE);

        $spot3 = ParkingSpot::where('code', 302)->first();
        $this->parkingSpotService->park($spot3, Van::VALUE);

        $this->parkingSpotService->calculateParkingSpotByGroupId(3);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:3');
        $this->assertCount(0, $resultA);
        $resultB = Redis::smembers('{parking_slots:car}:3');
        $this->assertCount(0, $resultB);
        $resultC = Redis::smembers('{parking_slots:van}:3');
        $this->assertCount(0, $resultC);

        $overview = $this->parkingSpotService->getSlotOverview();
        $this->assertEquals(52, $overview['motorcycle']);
        $this->assertEquals(52, $overview['car']);
        $this->assertEquals(14, $overview['van']);

        $this->parkingSpotService->unpark($spot1);
        $this->parkingSpotService->unpark($spot2);
        $this->parkingSpotService->unpark($spot3);

        $this->parkingSpotService->calculateParkingSpotByGroupId(3);
        $resultA = Redis::smembers('{parking_slots:motorcycle}:3');
        $this->assertCount(3, $resultA);
        $this->assertEquals($resultA[0], 300);
        $this->assertEquals($resultA[1], 301);
        $this->assertEquals($resultA[2], 302);
        $resultB = Redis::smembers('{parking_slots:car}:3');
        $this->assertCount(3, $resultB);
        $this->assertEquals($resultB[0], 300);
        $this->assertEquals($resultB[1], 301);
        $this->assertEquals($resultB[2], 302);
        $resultC = Redis::smembers('{parking_slots:van}:3');
        $this->assertCount(1, $resultC);
        $this->assertEquals($resultC[0], 300);

        $overview = $this->parkingSpotService->getSlotOverview();
        $this->assertEquals(55, $overview['motorcycle']);
        $this->assertEquals(55, $overview['car']);
        $this->assertEquals(15, $overview['van']);
    }


    public function testParkingLotOverview()
    {
        $this->mock(ParkingSpotRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getParkingSpotGroups')->andReturn([1,2]);
            $mock->shouldReceive('getAvailSpotMapByGroupId')->with(1)->andReturn([
                100 => 'normal', 102 => 'motorcycle', 103 => 'normal', 105 => 'normal', 106 => 'normal', 107 => 'normal'
            ]);
            $mock->shouldReceive('getAvailSpotMapByGroupId')->with(2)->andReturn([
                200 => 'normal', 201 => 'motorcycle', 203 => 'normal', 205 => 'normal', 206 => 'normal', 207 => 'motorcycle'
            ]);
        });
        $this->parkingSpotService = resolve(ParkingSpotService::class);
        $overview = $this->parkingSpotService->getSlotOverview();
        $this->assertEquals(12, $overview['motorcycle']);
        $this->assertEquals(9, $overview['car']);
        $this->assertEquals(1, $overview['van']);
    }

}
