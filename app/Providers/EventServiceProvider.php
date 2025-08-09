<?php

namespace App\Providers;

use App\Events\GoodsReceiptCreated;
use App\Events\PaymentCreated;
use App\Listeners\Finance\PostJournalOnGR;
use App\Listeners\Finance\PostJournalOnPayment;
use App\Listeners\Inventory\PostStockOnGR;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentCreated::class => [
            PostJournalOnPayment::class,
        ],
        GoodsReceiptCreated::class => [
            PostStockOnGR::class,
            PostJournalOnGR::class,
        ],
    ];
}
