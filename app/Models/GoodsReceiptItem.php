<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'gr_id',
        'po_item_id',
        'qty_received',
        'uom',
        'notes'
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'gr_id');
    }

    public function poItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'po_item_id');
    }
}
