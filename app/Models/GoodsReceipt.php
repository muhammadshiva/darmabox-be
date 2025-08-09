<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'po_id',
        'received_by',
        'gr_number',
        'delivery_note_no',
        'received_at',
        'notes'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiptItem::class, 'gr_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    protected static function booted(): void
    {
        static::created(function (GoodsReceipt $gr) {
            event(new \App\Events\GoodsReceiptCreated($gr));
        });
    }
}
