<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => optional($this->product)->name,
            'quantity' => $this->quantity,
            'custom_note' => $this->custom_note,
            'line_price' => (float)$this->line_price,
            'discount_amount' => (float)$this->discount_amount,
            'discount_pct' => (float)$this->discount_pct,
            'price_override' => $this->price_override !== null ? (float)$this->price_override : null,
            'final_line_total' => (float)$this->final_line_total,
        ];
    }
}
