<?php

namespace App\Events;

use App\Models\InvoiceReceipt;

class InvoiceReceiptCreated
{
    public function __construct(public readonly InvoiceReceipt $invoiceReceipt) {}
}
