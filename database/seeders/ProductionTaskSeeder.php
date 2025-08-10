<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Production;
use App\Models\ProductionTask;
use Illuminate\Database\Seeder;

class ProductionTaskSeeder extends Seeder
{
    public function run(): void
    {
        // Create a task for up to 5 recent orders
        $orders = Order::with('customer')->latest('id')->take(5)->get();
        foreach ($orders as $index => $order) {
            $production = $order->production ?? Production::create([
                'order_id' => $order->id,
                'user_id' => 1,
                'status' => 'not_started',
            ]);

            $task = ProductionTask::create([
                'production_id' => $production->id,
                'order_id' => $order->id,
                'title' => 'Production for ' . ($order->invoice_code ?? ('Order #' . $order->id)),
                'priority' => $index % 2 === 0 ? 'high' : 'normal',
                'description' => 'Auto-generated seed task for production planning.',
                'status' => 'not_started',
                'progress' => 0,
                'assigned_to' => 1,
            ]);

            $task->materials()->createMany([
                [
                    'material_name' => 'Premium Fabric - Blue',
                    'quantity' => 8,
                    'unit' => 'm',
                    'readiness' => 'ready',
                ],
                [
                    'material_name' => 'Wood Frame - Pine',
                    'quantity' => 15,
                    'unit' => 'pcs',
                    'readiness' => 'ready',
                ],
                [
                    'material_name' => 'Glass Top',
                    'quantity' => 1,
                    'unit' => 'pcs',
                    'readiness' => 'pending',
                ],
            ]);
        }
    }
}
