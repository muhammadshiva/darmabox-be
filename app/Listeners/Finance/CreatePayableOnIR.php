<?php

namespace App\Listeners\Finance;

use App\Events\InvoiceReceiptCreated;
use App\Models\Payable;

class CreatePayableOnIR
{
    public function handle(InvoiceReceiptCreated $event): void
    {
        $ir = $event->invoiceReceipt;
        if ($ir->total_amount <= 0) {
            return;
        }

        Payable::create([
            'supplier_id' => $ir->supplier_id,
            'po_id' => $ir->po_id,
            'gr_id' => null,
            'ir_id' => $ir->id,
            'amount' => $ir->total_amount,
            'remaining_amount' => $ir->total_amount,
            'due_date' => $ir->due_date,
            'status' => 'unpaid',
            'note' => 'From IR ' . $ir->ir_number,
        ]);
    }
}
