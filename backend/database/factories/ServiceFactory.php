<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['electrical', 'mechanical', 'special']),
            'price_range' => '$' . fake()->numberBetween(50, 500) . ' - $' . fake()->numberBetween(500, 2000),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
