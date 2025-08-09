<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_id' => $this->po_id,
            'supplier_id' => $this->supplier_id,
            'ir_number' => $this->ir_number,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'total_amount' => (float) $this->total_amount,
            'notes' => $this->notes,
            'items' => InvoiceReceiptItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
