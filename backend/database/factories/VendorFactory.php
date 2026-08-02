<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo' => fake()->optional()->imageUrl(200, 200, 'company'),
            'website' => fake()->optional()->url(),
            'category' => fake()->randomElement(['parts', 'tools', 'equipment', 'software', 'supplies']),
            'description' => fake()->optional()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
