<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => 1,
            'amount' => $this->faker->numberBetween(10000, 300000),
            'payment_type' => $this->faker->randomElement(['dp', 'final']),
            'payment_method' => $this->faker->randomElement(['cash', 'transfer', 'qris']),
            'paid_at' => now(),
        ];
    }
}
