<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence(8) . '?',
            'answer' => fake()->paragraph(),
            'category' => fake()->randomElement(['general', 'services', 'pricing', 'scheduling', 'warranty']),
            'sort_order' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
