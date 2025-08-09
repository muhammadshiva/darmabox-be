<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchasing\CreateGoodsReceiptRequest;
use App\Http\Resources\GoodsReceiptResource;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;

class GoodsReceiptController extends Controller
{
    public function index()
    {
        $list = GoodsReceipt::with('items')->orderByDesc('id')->paginate(15);
        return GoodsReceiptResource::collection($list);
    }

    public function store(CreateGoodsReceiptRequest $request, PurchaseService $purchase)
    {
        $data = $request->validated();
        $gr = DB::transaction(function () use ($data, $purchase) {
            $gr = GoodsReceipt::create([
                'po_id' => $data['po_id'],
                'received_by' => $data['received_by'],
                'gr_number' => strtoupper('GR-' . now()->format('YmdHis') . rand(100, 999)),
                'delivery_note_no' => $data['delivery_note_no'] ?? null,
                'received_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $it) {
                GoodsReceiptItem::create([
                    'gr_id' => $gr->id,
                    'po_item_id' => $it['po_item_id'],
                    'qty_received' => $it['qty_received'],
                    'uom' => $it['uom'],
                    'notes' => $it['notes'] ?? null,
                ]);
            }

            $gr->load('items.poItem');
            $purchase->receiveGoods($gr);

            return $gr->load('items');
        });

        return (new GoodsReceiptResource($gr))->response()->setStatusCode(201);
    }
}
