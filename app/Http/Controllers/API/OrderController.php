<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\StoreOrderRequest;
use App\Http\Requests\Sales\RecordPaymentRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\SalesService;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product', 'payments'])->orderByDesc('id')->paginate(15);
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'user_id' => $data['user_id'],
                'status' => 'draft',
                'dp_amount' => (float)($data['dp_amount'] ?? 0),
                'total_amount' => 0,
                'invoice_code' => strtoupper('INV-' . now()->format('YmdHis') . rand(100, 999)),
            ]);

            $total = 0;
            foreach ($data['items'] as $it) {
                $linePrice = (float)($it['line_price'] ?? Product::findOrFail($it['product_id'])->price);
                $discountAmount = (float)($it['discount_amount'] ?? 0);
                $discountPct = (float)($it['discount_pct'] ?? 0);
                $priceOverride = $it['price_override'] ?? null;
                if ($priceOverride !== null) {
                    $linePrice = (float)$priceOverride;
                }
                if ($discountPct > 0) {
                    $discountAmount += ($linePrice * $it['quantity']) * ($discountPct / 100);
                }
                $final = ($linePrice * $it['quantity']) - $discountAmount;
                $total += $final;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'custom_note' => $it['custom_note'] ?? null,
                    'line_price' => $linePrice,
                    'discount_amount' => $discountAmount,
                    'discount_pct' => $discountPct,
                    'price_override' => $priceOverride,
                    'final_line_total' => $final,
                ]);
            }

            $order->update(['total_amount' => $total]);

            if (($data['dp_amount'] ?? 0) > 0) {
                $order->update(['status' => 'dp']);
            }

            return $order->load(['customer', 'items.product', 'payments']);
        });

        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'payments']);
        return new OrderResource($order);
    }

    public function recordPayment(Order $order, RecordPaymentRequest $request)
    {
        $data = $request->validated();
        $payment = DB::transaction(function () use ($order, $data) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $data['amount'],
                'payment_type' => $data['payment_type'],
                'payment_method' => $data['payment_method'],
                'paid_at' => $data['paid_at'] ?? now(),
            ]);

            return $payment;
        });

        return new PaymentResource($payment);
    }

    public function finalizeReady(Order $order, SalesService $sales, FinanceService $finance)
    {
        DB::transaction(function () use ($order, $sales, $finance) {
            $sales->finalizeReadySale($order->load(['items.product']));
            $order->update(['status' => 'paid']);

            $rate = (float) config('darmabox.cogs_rate', 0.6);
            $cogs = round(((float)$order->total_amount) * $rate, 2);
            $finance->templateCOGS($cogs);
        });

        return new OrderResource($order->fresh()->load(['customer', 'items.product', 'payments']));
    }
}
