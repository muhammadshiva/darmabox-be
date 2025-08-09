<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class PaymentCreated implements ShouldDispatchAfterCommit
{
    use InteractsWithSockets;

    public function __construct(public Payment $payment) {}
}
