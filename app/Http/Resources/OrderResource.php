<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_code' => $this->invoice_code,
            'status' => $this->status,
            'dp_amount' => (float)$this->dp_amount,
            'total_amount' => (float)$this->total_amount,
            'customer' => [
                'id' => $this->customer_id,
                'name' => optional($this->customer)->name,
            ],
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => optional($this->created_at)?->toDateTimeString(),
        ];
    }
}
