<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $makes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz', 'Hyundai', 'Kia', 'Lexus'];
        $models = ['Camry', 'Civic', 'F-150', 'Silverado', 'Altima', '3 Series', 'C-Class', 'Elantra', 'Forte', 'RX'];

        return [
            'customer_id' => Customer::factory(),
            'make' => fake()->randomElement($makes),
            'model' => fake()->randomElement($models),
            'year' => fake()->numberBetween(2005, 2025),
            'vin' => fake()->unique()->numerify('#################'),
        ];
    }
}
