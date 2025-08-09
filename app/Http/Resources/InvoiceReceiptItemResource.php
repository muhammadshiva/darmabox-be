<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceReceiptItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_item_id' => $this->po_item_id,
            'gr_item_id' => $this->gr_item_id,
            'qty_invoiced' => (float) $this->qty_invoiced,
            'unit_price' => (float) $this->unit_price,
            'uom' => $this->uom,
            'notes' => $this->notes,
        ];
    }
}
