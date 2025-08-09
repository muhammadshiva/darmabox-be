<?php

namespace App\Http\Requests\Purchasing;

use Illuminate\Foundation\Http\FormRequest;

class CreateGoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'po_id' => ['required', 'exists:purchase_orders,id'],
            'received_by' => ['required', 'exists:users,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.po_item_id' => ['required', 'exists:purchase_order_items,id'],
            'items.*.qty_received' => ['required', 'numeric', 'min:0.001'],
            'items.*.uom' => ['required', 'string', 'max:30'],
        ];
    }
}
