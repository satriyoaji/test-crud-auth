<?php

namespace Tests\Feature;

use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ProvinceControllerTest extends TestCase
{
    use RefreshDatabase;  // It will refresh the database after each test

    public function testSearchWithMissingDataSource()
    {
        $response = $this->getJson('/search/provinces?id=1');

        $response->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJson(['message' => 'DATA_SOURCE is must be set first']);
    }

    public function testSearchWithInvalidDataSource()
    {
        config(['DATA_SOURCE' => 'INVALID']);

        $response = $this->getJson('/search/provinces?id=1');

        $response->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJson(['message' => 'DATA_SOURCE is must be set either `REMOTE` or `DATABASE` value']);
    }

    public function testSearchDatabaseNotFound()
    {
        config(['DATA_SOURCE' => 'DATABASE']);

        $response = $this->getJson('/search/provinces?id=999');

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Data Province by id not found']);
    }

    public function testSearchDatabaseSuccess()
    {
        config(['DATA_SOURCE' => 'DATABASE']);

        $province = Province::factory()->create(['id' => 1, 'name' => 'Some Province']);

        $response = $this->getJson('/search/provinces?id=1');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(['data' => ['id' => 1, 'name' => 'Some Province']]);
    }

    public function testSearchRemoteNotFound()
    {
        // Mocking HTTP calls here
    }

    public function testSearchRemoteSuccess()
    {
        // Mocking HTTP calls here
    }
}
