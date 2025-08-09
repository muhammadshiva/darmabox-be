<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Production;
use App\Models\StockMovement;
use App\Models\ProductStockMovement;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Receivable;
use App\Models\Payable;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $admin = User::firstOrCreate(
                ['email' => 'admin@darmabox.test'],
                ['name' => 'Admin', 'password' => Hash::make('admin123'), 'role' => 'admin']
            );
            $kasir = User::firstOrCreate(
                ['email' => 'kasir@darmabox.test'],
                ['name' => 'Kasir', 'password' => Hash::make('kasir123'), 'role' => 'kasir']
            );
            $wh = User::firstOrCreate(
                ['email' => 'warehouse@darmabox.test'],
                ['name' => 'Warehouse', 'password' => Hash::make('warehouse123'), 'role' => 'warehouse']
            );
            $planner = User::firstOrCreate(
                ['email' => 'planner@darmabox.test'],
                ['name' => 'Planner', 'password' => Hash::make('planner123'), 'role' => 'planner']
            );

            $customer = Customer::factory()->create();
            $supplier = Supplier::factory()->create();

            $ready = Product::factory()->create(['type' => 'ready', 'stock' => 50, 'price' => 20000]);
            $custom = Product::factory()->create(['type' => 'custom', 'stock' => 0, 'price' => 25000]);

            $paper = Material::factory()->create(['name' => 'Kertas Duplex 230gr', 'unit' => 'lembar', 'stock' => 500, 'minimum_stock' => 100, 'price' => 500]);
            $ink = Material::factory()->create(['name' => 'Tinta Hitam', 'unit' => 'ml', 'stock' => 2000, 'minimum_stock' => 200, 'price' => 50]);

            $order1 = Order::factory()->create([
                'customer_id' => $customer->id,
                'user_id'     => $kasir->id,
                'status'      => 'paid',
                'dp_amount'   => 0,
                'total_amount' => 380000,
            ]);

            OrderItem::factory()->create([
                'order_id' => $order1->id,
                'product_id' => $ready->id,
                'quantity' => 20,
                'line_price' => 20000,
                'discount_amount' => 20000,
                'final_line_total' => 380000,
            ]);

            ProductStockMovement::create([
                'product_id' => $ready->id,
                'type' => 'out',
                'quantity' => 20,
                'ref_type' => 'sales',
                'ref_id' => $order1->id,
                'notes' => 'Ready sale',
                'created_at' => now(),
            ]);
            $ready->decrement('stock', 20);

            Payment::factory()->create([
                'order_id' => $order1->id,
                'amount' => 380000,
                'payment_type' => 'final',
                'payment_method' => 'cash',
            ]);

            $order2 = Order::factory()->create([
                'customer_id' => $customer->id,
                'user_id'     => $kasir->id,
                'status'      => 'in_production',
                'dp_amount'   => 200000,
                'total_amount' => 1000000,
            ]);

            OrderItem::factory()->create([
                'order_id' => $order2->id,
                'product_id' => $custom->id,
                'quantity' => 100,
                'line_price' => 10000,
                'final_line_total' => 1000000,
                'custom_note' => 'Ukuran 20x10x5 + logo',
            ]);

            Payment::factory()->create([
                'order_id' => $order2->id,
                'amount' => 200000,
                'payment_type' => 'dp',
                'payment_method' => 'transfer',
            ]);

            $prod = Production::create([
                'order_id' => $order2->id,
                'user_id' => $planner->id,
                'start_date' => now()->toDateString(),
                'status' => 'in_progress',
                'notes' => 'Batch 1',
            ]);

            StockMovement::create([
                'material_id' => $paper->id,
                'user_id' => $wh->id,
                'quantity' => -300,
                'type' => 'out',
                'description' => 'Production for order ' . $order2->id,
                'created_at' => now(),
            ]);
            $paper->decrement('stock', 300);

            StockMovement::create([
                'material_id' => $ink->id,
                'user_id' => $wh->id,
                'quantity' => -200,
                'type' => 'out',
                'description' => 'Production for order ' . $order2->id,
                'created_at' => now(),
            ]);
            $ink->decrement('stock', 200);

            $prod->update(['status' => 'done', 'end_date' => now()->addDays(2)->toDateString()]);
            $order2->update(['status' => 'done']);

            Receivable::create([
                'customer_id' => $customer->id,
                'order_id' => $order2->id,
                'amount' => 1000000,
                'remaining_amount' => 800000,
                'due_date' => now()->addDays(7)->toDateString(),
                'status' => 'partial',
                'note' => 'Awaiting final payment',
            ]);

            Payment::factory()->create([
                'order_id' => $order2->id,
                'amount' => 800000,
                'payment_type' => 'final',
                'payment_method' => 'qris',
            ]);
            $order2->update(['status' => 'paid']);
            $ar = $order2->receivable;
            if ($ar) $ar->update(['remaining_amount' => 0, 'status' => 'paid']);

            $po = PurchaseOrder::factory()->create([
                'supplier_id' => $supplier->id,
                'created_by'  => $admin->id,
                'status' => 'sent',
            ]);

            $poi1 = PurchaseOrderItem::factory()->create([
                'po_id' => $po->id,
                'item_type' => 'material',
                'item_id' => $paper->id,
                'qty_ordered' => 1000,
                'unit_price' => 450,
                'uom' => 'lembar'
            ]);
            $poi2 = PurchaseOrderItem::factory()->create([
                'po_id' => $po->id,
                'item_type' => 'material',
                'item_id' => $ink->id,
                'qty_ordered' => 1000,
                'unit_price' => 45,
                'uom' => 'ml'
            ]);

            $gr1 = GoodsReceipt::factory()->create([
                'po_id' => $po->id,
                'received_by' => $wh->id,
            ]);

            GoodsReceiptItem::factory()->create([
                'gr_id' => $gr1->id,
                'po_item_id' => $poi1->id,
                'qty_received' => 600,
                'uom' => 'lembar'
            ]);
            GoodsReceiptItem::factory()->create([
                'gr_id' => $gr1->id,
                'po_item_id' => $poi2->id,
                'qty_received' => 700,
                'uom' => 'ml'
            ]);

            $paper->increment('stock', 600);
            $ink->increment('stock', 700);
            StockMovement::create([
                'material_id' => $paper->id,
                'user_id' => $wh->id,
                'quantity' => 600,
                'type' => 'in',
                'description' => 'GR ' . $gr1->gr_number,
                'created_at' => now()
            ]);
            StockMovement::create([
                'material_id' => $ink->id,
                'user_id' => $wh->id,
                'quantity' => 700,
                'type' => 'in',
                'description' => 'GR ' . $gr1->gr_number,
                'created_at' => now()
            ]);
        });
    }
}
