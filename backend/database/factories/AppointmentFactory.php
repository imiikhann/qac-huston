<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'service_id' => Service::factory(),
            'requested_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'requested_time' => fake()->randomElement(['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
