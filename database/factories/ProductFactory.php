<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => 'P' . fake()->numberBetween(1, 200),
            'name' => fake()->name(),
            'price' => fake()->numberBetween(100, 1000),
            'min_quantity' => fake()->numberBetween(1, 50),
            'discount_rate' => fake()->numberBetween(1, 100),
        ];
    }
}
