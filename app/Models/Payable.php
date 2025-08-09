<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'po_id',
        'gr_id',
        'amount',
        'remaining_amount',
        'due_date',
        'status',
        'note'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'gr_id');
    }
}
