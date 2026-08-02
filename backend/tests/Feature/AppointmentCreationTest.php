<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_appointment()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);
        $service = Service::factory()->create();

        $response = $this->postJson('/api/v1/appointments', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_id' => $service->id,
            'requested_date' => now()->addDays(3)->format('Y-m-d'),
            'requested_time' => '10:00',
            'notes' => 'Test appointment',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data', 'message']);
    }

    public function test_appointment_requires_valid_data()
    {
        $response = $this->postJson('/api/v1/appointments', []);

        $response->assertStatus(422);
    }
}
