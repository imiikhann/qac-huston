<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'source' => fake()->randomElement(['website', 'ai-chat', 'referral', 'social-media', 'search', 'other']),
            'name' => fake()->optional()->name(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->numerify('###-###-####'),
            'service_interest' => fake()->optional()->randomElement(['Oil Change', 'Brake Repair', 'Engine Diagnostic', 'Electrical Repair', 'AC Service']),
            'message' => fake()->optional()->paragraph(),
            'status' => 'new',
        ];
    }
}
