<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $types = ['ready', 'custom'];
        return [
            'sku' => strtoupper($this->faker->bothify('BOX-###??')),
            'name' => 'Box ' . $this->faker->word(),
            'type' => $this->faker->randomElement($types),
            'price' => $this->faker->numberBetween(2000, 50000),
            'description' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(0, 300),
            'created_at' => now(),
        ];
    }
}
