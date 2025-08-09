<?php

namespace App\Events;

use App\Models\GoodsReceipt;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class GoodsReceiptCreated implements ShouldDispatchAfterCommit
{
    use InteractsWithSockets;

    public function __construct(public GoodsReceipt $goodsReceipt) {}
}
