<?php

namespace App\Listeners\Finance;

use App\Events\PaymentCreated;
use App\Services\FinanceService;

class PostJournalOnPayment
{
    public function __construct(private readonly FinanceService $finance) {}

    public function handle(PaymentCreated $event): void
    {
        $payment = $event->payment->load('order');

        $lines = [];
        if ($payment->payment_type === 'dp') {
            // Debit Cash 1000, Credit Customer Advances 2200
            $lines = [
                ['account_code' => '1000', 'debit' => (float) $payment->amount, 'credit' => 0],
                ['account_code' => '2200', 'debit' => 0, 'credit' => (float) $payment->amount],
            ];
        } else {
            // Debit Cash 1000, Credit Sales Revenue 4000
            $lines = [
                ['account_code' => '1000', 'debit' => (float) $payment->amount, 'credit' => 0],
                ['account_code' => '4000', 'debit' => 0, 'credit' => (float) $payment->amount],
            ];
        }

        $desc = 'Payment for Order ' . optional($payment->order)->invoice_code;
        $date = optional($payment->paid_at)?->toDateString() ?: now()->toDateString();
        $createdBy = optional($payment->order)->user_id;

        $this->finance->postJournal($desc, $lines, $createdBy, $date);
    }
}
