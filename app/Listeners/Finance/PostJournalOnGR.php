<?php

namespace App\Listeners\Finance;

use App\Events\GoodsReceiptCreated;
use App\Services\FinanceService;

class PostJournalOnGR
{
    public function __construct(private readonly FinanceService $finance) {}

    public function handle(GoodsReceiptCreated $event): void
    {
        $gr = $event->goodsReceipt;

        // For simplicity: Debit Inventory (1300/1310) and Credit GR/IR (2100) by total item value
        $gr->load('items.poItem');
        $materialTotal = 0.0;
        $productTotal = 0.0;
        foreach ($gr->items as $item) {
            $poi = $item->poItem;
            if (!$poi) {
                continue;
            }
            $line = (float) $poi->unit_price * (float) $item->qty_received;
            if ($poi->item_type === 'material') {
                $materialTotal += $line;
            } else {
                $productTotal += $line;
            }
        }

        $total = $materialTotal + $productTotal;
        if ($total <= 0) {
            return;
        }

        $lines = [];
        if ($materialTotal > 0) {
            $lines[] = ['account_code' => '1300', 'debit' => $materialTotal, 'credit' => 0];
        }
        if ($productTotal > 0) {
            $lines[] = ['account_code' => '1310', 'debit' => $productTotal, 'credit' => 0];
        }
        $lines[] = ['account_code' => '2100', 'debit' => 0, 'credit' => $total];

        $this->finance->postJournal('Goods Receipt ' . $gr->gr_number, $lines, $gr->received_by, optional($gr->received_at)?->toDateString());
    }
}
