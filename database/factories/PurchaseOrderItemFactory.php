<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderItemFactory extends Factory
{
    public function definition(): array
    {
        $itemType = $this->faker->randomElement(['material', 'product']);
        return [
            'po_id' => 1,
            'item_type' => $itemType,
            'item_id' => 1,
            'qty_ordered' => $this->faker->randomFloat(3, 10, 200),
            'unit_price' => $this->faker->numberBetween(500, 50000),
            'uom' => $itemType === 'material' ? 'lembar' : 'pcs',
            'notes' => $this->faker->boolean(20) ? 'Urgent' : null,
        ];
    }
}
