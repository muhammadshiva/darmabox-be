<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'po_id' => $this->po_id,
            'gr_id' => $this->gr_id,
            'amount' => (float) $this->amount,
            'remaining_amount' => (float) $this->remaining_amount,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
