<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['draft', 'sent', 'partially_received', 'received', 'closed'];
        return [
            'supplier_id' => 1,
            'created_by' => 1,
            'source' => 'cms',
            'status' => $this->faker->randomElement($statuses),
            'po_number' => strtoupper($this->faker->bothify('PO-########')),
            'expected_date' => now()->addDays(rand(3, 14))->toDateString(),
            'sent_at' => null,
            'notes' => $this->faker->sentence(),
            'created_at' => now(),
        ];
    }
}
