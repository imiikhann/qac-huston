<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'content' => fake()->paragraphs(2, true),
            'rating' => fake()->optional(80)->numberBetween(1, 5),
            'is_approved' => fake()->boolean(70),
        ];
    }
}
