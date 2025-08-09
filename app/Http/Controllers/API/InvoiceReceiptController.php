<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchasing\CreateInvoiceReceiptRequest;
use App\Http\Resources\InvoiceReceiptResource;
use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItem;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class InvoiceReceiptController extends Controller
{
    public function index()
    {
        $list = InvoiceReceipt::with('items')->orderByDesc('id')->paginate(15);
        return InvoiceReceiptResource::collection($list);
    }

    public function store(CreateInvoiceReceiptRequest $request)
    {
        $data = $request->validated();
        $ir = DB::transaction(function () use ($data) {
            // Ensure supplier matches PO supplier
            /** @var PurchaseOrder $po */
            $po = PurchaseOrder::with('items')->findOrFail($data['po_id']);
            if ((int) $po->supplier_id !== (int) $data['supplier_id']) {
                abort(422, 'Supplier does not match the Purchase Order supplier.');
            }

            // Validate quantities: cannot invoice more than received per PO item
            $incomingByPoItem = [];
            foreach ($data['items'] as $it) {
                $incomingByPoItem[$it['po_item_id']] = ($incomingByPoItem[$it['po_item_id']] ?? 0) + (float) $it['qty_invoiced'];
            }
            if (! empty($incomingByPoItem)) {
                $poItemIds = array_keys($incomingByPoItem);
                $receivedByPoItem = GoodsReceiptItem::whereIn('po_item_id', $poItemIds)
                    ->select('po_item_id', DB::raw('SUM(qty_received) as qty'))
                    ->groupBy('po_item_id')
                    ->pluck('qty', 'po_item_id');
                $invoicedSoFarByPoItem = InvoiceReceiptItem::whereIn('po_item_id', $poItemIds)
                    ->select('po_item_id', DB::raw('SUM(qty_invoiced) as qty'))
                    ->groupBy('po_item_id')
                    ->pluck('qty', 'po_item_id');

                foreach ($incomingByPoItem as $poItemId => $addQty) {
                    $received = (float) ($receivedByPoItem[$poItemId] ?? 0);
                    $alreadyInvoiced = (float) ($invoicedSoFarByPoItem[$poItemId] ?? 0);
                    if ($alreadyInvoiced + $addQty - $received > 1e-6) {
                        abort(422, 'Invoiced quantity exceeds received quantity for PO item ID ' . $poItemId . '.');
                    }
                }
            }

            $ir = InvoiceReceipt::create([
                'po_id' => $data['po_id'],
                'supplier_id' => $data['supplier_id'],
                'ir_number' => strtoupper('IR-' . now()->format('YmdHis') . rand(100, 999)),
                'invoice_number' => $data['invoice_number'] ?? null,
                'invoice_date' => $data['invoice_date'] ?? now()->toDateString(),
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_at' => now(),
            ]);

            $total = 0.0;
            foreach ($data['items'] as $it) {
                $lineAmount = (float) $it['qty_invoiced'] * (float) $it['unit_price'];
                $total += $lineAmount;

                InvoiceReceiptItem::create([
                    'ir_id' => $ir->id,
                    'po_item_id' => $it['po_item_id'],
                    'gr_item_id' => $it['gr_item_id'] ?? null,
                    'qty_invoiced' => $it['qty_invoiced'],
                    'unit_price' => $it['unit_price'],
                    'uom' => $it['uom'],
                    'notes' => $it['notes'] ?? null,
                ]);
            }

            $ir->update(['total_amount' => $total]);

            return $ir->load('items');
        });

        // Fire event for posting journals and creating payables
        event(new \App\Events\InvoiceReceiptCreated($ir));

        return (new InvoiceReceiptResource($ir))->response()->setStatusCode(201);
    }
}
