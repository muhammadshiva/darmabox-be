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
}
