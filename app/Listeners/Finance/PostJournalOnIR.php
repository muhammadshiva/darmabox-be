<?php

namespace App\Listeners\Finance;

use App\Events\InvoiceReceiptCreated;
use App\Services\FinanceService;

class PostJournalOnIR
{
    public function __construct(private readonly FinanceService $finance) {}

    public function handle(InvoiceReceiptCreated $event): void
    {
        $ir = $event->invoiceReceipt->load('items.poItem');

        $amount = 0.0;
        foreach ($ir->items as $item) {
            $amount += (float) $item->unit_price * (float) $item->qty_invoiced;
        }

        if ($amount <= 0) {
            return;
        }

        // Debit GR/IR (2100), Credit AP (2000) for matched amount
        $lines = [
            ['account_code' => '2100', 'debit' => $amount, 'credit' => 0],
            ['account_code' => '2000', 'debit' => 0, 'credit' => $amount],
        ];

        $this->finance->postJournal('Invoice Receipt ' . $ir->ir_number, $lines, optional($ir->purchaseOrder)->created_by, optional($ir->invoice_date)?->toDateString());
    }
}
