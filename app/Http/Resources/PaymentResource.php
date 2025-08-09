<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'amount' => (float)$this->amount,
            'payment_type' => $this->payment_type,
            'payment_method' => $this->payment_method,
            'paid_at' => optional($this->paid_at)?->toDateTimeString(),
        ];
    }
}
