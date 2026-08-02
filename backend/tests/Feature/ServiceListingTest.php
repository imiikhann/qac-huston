<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_services()
    {
        Service::factory(3)->create();

        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_can_get_single_service()
    {
        $service = Service::factory()->create();

        $response = $this->getJson("/api/v1/services/{$service->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $service->id]]);
    }
}
