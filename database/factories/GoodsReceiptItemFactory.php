<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsReceiptItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gr_id' => 1,
            'po_item_id' => 1,
            'qty_received' => $this->faker->randomFloat(3, 5, 120),
            'uom' => 'lembar',
            'notes' => null,
        ];
    }
}
