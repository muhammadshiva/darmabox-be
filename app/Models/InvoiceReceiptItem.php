<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReceiptItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ir_id',
        'po_item_id',
        'gr_item_id',
        'qty_invoiced',
        'unit_price',
        'uom',
        'notes',
    ];

    public function invoiceReceipt()
    {
        return $this->belongsTo(InvoiceReceipt::class, 'ir_id');
    }

    public function poItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'po_item_id');
    }

    public function grItem()
    {
        return $this->belongsTo(GoodsReceiptItem::class, 'gr_item_id');
    }
}
