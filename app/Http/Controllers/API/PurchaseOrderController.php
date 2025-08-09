<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return PurchaseOrder::with(['supplier', 'items'])->orderByDesc('id')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'created_by' => ['required', 'exists:users,id'],
            'source' => ['nullable', 'in:pos,cms'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:material,product'],
            'items.*.item_id' => ['required', 'integer'],
            'items.*.qty_ordered' => ['required', 'numeric', 'min:0.001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.uom' => ['required', 'string', 'max:30'],
        ]);

        $po = PurchaseOrder::create([
            'supplier_id' => $data['supplier_id'],
            'created_by' => $data['created_by'],
            'source' => $data['source'] ?? 'cms',
            'status' => 'draft',
            'po_number' => strtoupper('PO-' . now()->format('YmdHis') . rand(100, 999)),
            'expected_date' => now()->addDays(7)->toDateString(),
            'notes' => $data['notes'] ?? null,
            'created_at' => now(),
        ]);

        foreach ($data['items'] as $it) {
            $po->items()->create([
                'item_type' => $it['item_type'],
                'item_id' => $it['item_id'],
                'qty_ordered' => $it['qty_ordered'],
                'unit_price' => $it['unit_price'],
                'uom' => $it['uom'],
                'notes' => $it['notes'] ?? null,
            ]);
        }

        return response()->json($po->load('items'), 201);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return $purchaseOrder->load(['supplier', 'items']);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validate([
            'status' => ['sometimes', 'in:draft,sent,partially_received,received,closed,cancelled'],
            'expected_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
        $purchaseOrder->update($data);
        return $purchaseOrder->load('items');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return response()->noContent();
    }
}
