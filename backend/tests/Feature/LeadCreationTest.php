<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_lead()
    {
        $response = $this->postJson('/api/v1/leads', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-0100',
            'message' => 'I need brake service.',
            'source' => 'website',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    public function test_lead_accepts_minimal_data()
    {
        $response = $this->postJson('/api/v1/leads', [
            'source' => 'website',
        ]);

        $response->assertStatus(201);
    }
}
