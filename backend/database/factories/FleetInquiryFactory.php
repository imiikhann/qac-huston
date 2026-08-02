<?php

namespace Database\Factories;

use App\Models\FleetInquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

class FleetInquiryFactory extends Factory
{
    protected $model = FleetInquiry::class;

    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'contact_name' => fake()->name(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->numerify('###-###-####'),
            'fleet_size' => fake()->numberBetween(2, 200),
            'message' => fake()->paragraph(),
        ];
    }
}
