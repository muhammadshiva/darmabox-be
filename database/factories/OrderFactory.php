<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['draft', 'dp', 'in_production', 'done', 'paid'];
        return [
            'customer_id' => 1,
            'user_id' => 1,
            'status' => $this->faker->randomElement($statuses),
            'dp_amount' => 0,
            'total_amount' => 0,
            'invoice_code' => strtoupper($this->faker->bothify('INV-########')),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
