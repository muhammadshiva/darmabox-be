<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_type',
        'payment_method',
        'paid_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted(): void
    {
        static::created(function (Payment $payment) {
            event(new \App\Events\PaymentCreated($payment));
        });
    }
}
