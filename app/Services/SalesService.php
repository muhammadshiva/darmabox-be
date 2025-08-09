<?php

namespace App\Services;

use App\Models\Order;

class SalesService
{
    public function __construct(
        protected InventoryService $inventory,
        protected FinanceService $finance,
    ) {}

    public function finalizeReadySale(Order $order): void
    {
        foreach ($order->items as $item) {
            if (optional($item->product)->type === 'ready') {
                $this->inventory->postProductOut($item->product_id, (int)$item->quantity, 'sales', $order->id, "Order {$order->invoice_code}");
            }
        }

        // Placeholder for journal postings
        // $this->finance->templateSaleCash((float)$order->total_amount);
        // $this->finance->templateCOGS($estimatedCOGS);
    }
}
