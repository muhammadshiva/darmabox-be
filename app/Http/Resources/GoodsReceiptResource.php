<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodsReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gr_number' => $this->gr_number,
            'po_id' => $this->po_id,
            'received_by' => $this->received_by,
            'received_at' => optional($this->received_at)?->toDateTimeString(),
            'items' => GoodsReceiptItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
