<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'status',
        'dp_amount',
        'total_amount',
        'invoice_code'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function production()
    {
        return $this->hasOne(Production::class);
    }

    public function receivable()
    {
        return $this->hasOne(Receivable::class);
    }

    public function getPaymentStatusAttribute(): string
    {
        $total = (float) ($this->total_amount ?? 0);
        $paid = (float) ($this->payments()->sum('amount'));
        return $paid >= $total && $total > 0 ? 'paid' : 'pending';
    }
}
