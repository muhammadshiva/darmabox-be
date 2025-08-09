<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodsReceiptItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_item_id' => $this->po_item_id,
            'qty_received' => (float)$this->qty_received,
            'uom' => $this->uom,
        ];
    }
}
