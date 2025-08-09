<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 50);
        $price = $this->faker->numberBetween(2000, 50000);
        $discount = $this->faker->boolean(30) ? $this->faker->numberBetween(0, 5000) : 0;
        $final = ($qty * $price) - $discount;

        return [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => $qty,
            'custom_note' => $this->faker->boolean(20) ? 'Ukuran ' . $this->faker->randomElement(['S', 'M', 'L']) . ' / Logo' : null,
            'line_price' => $price,
            'discount_amount' => $discount,
            'discount_pct' => 0,
            'price_override' => null,
            'final_line_total' => $final,
        ];
    }
}
