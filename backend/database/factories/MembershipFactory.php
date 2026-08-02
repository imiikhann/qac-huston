<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 12) . ' months');

        return [
            'customer_id' => Customer::factory(),
            'tier' => fake()->randomElement(['preventive', 'discounts', 'fleet', 'vip']),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'expired', 'cancelled']),
        ];
    }
}
