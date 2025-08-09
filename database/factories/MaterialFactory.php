<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Kertas ' . $this->faker->colorName(),
            'unit' => $this->faker->randomElement(['pcs', 'gram', 'lembar']),
            'price' => $this->faker->numberBetween(100, 10000),
            'stock' => $this->faker->numberBetween(0, 5000),
            'minimum_stock' => $this->faker->numberBetween(0, 200),
        ];
    }
}
