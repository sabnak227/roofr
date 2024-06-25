<?php

namespace tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ParkingLotTest extends TestCase
{
    use DatabaseTransactions;

    public function testApi() {
        $response = $this->getJson('/api/parking-lot');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.motorcycle', 55)
            ->assertJsonPath('data.car', 55)
            ->assertJsonPath('data.van', 15);


        $response = $this->postJson('/api/parking-spot/1/park', ['vehicle_type' => 'car']);
        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $response = $this->getJson('/api/parking-lot');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.motorcycle', 54)
            ->assertJsonPath('data.car', 54)
            ->assertJsonPath('data.van', 15);


        $response = $this->postJson('/api/parking-spot/1/unpark');
        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $response = $this->getJson('/api/parking-lot');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.motorcycle', 55)
            ->assertJsonPath('data.car', 55)
            ->assertJsonPath('data.van', 15);



    }

}
