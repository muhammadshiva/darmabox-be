<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsReceiptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'po_id' => 1,
            'received_by' => 1,
            'gr_number' => strtoupper($this->faker->bothify('GR-########')),
            'delivery_note_no' => strtoupper($this->faker->bothify('SJ-####/##')),
            'received_at' => now(),
            'notes' => $this->faker->boolean(10) ? 'Partial receive' : null,
        ];
    }
}
