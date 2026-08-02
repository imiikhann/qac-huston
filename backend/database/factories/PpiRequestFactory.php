<?php

namespace Database\Factories;

use App\Models\PpiRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PpiRequestFactory extends Factory
{
    protected $model = PpiRequest::class;

    public function definition(): array
    {
        $makes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz'];
        $models = ['Camry', 'Civic', 'F-150', 'Silverado', 'Altima', '3 Series', 'C-Class'];

        return [
            'customer_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('###-###-####'),
            'vehicle_make' => fake()->randomElement($makes),
            'vehicle_model' => fake()->randomElement($models),
            'vehicle_year' => fake()->numberBetween(2005, 2025),
            'preferred_date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'preferred_time' => fake()->randomElement(['08:00', '09:00', '10:00', '11:00', '13:00', '14:00']),
            'tier' => fake()->randomElement(['basic', 'standard', 'comprehensive']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
