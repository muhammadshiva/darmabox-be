<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReceipt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'po_id',
        'supplier_id',
        'ir_number',
        'invoice_number',
        'invoice_date',
        'due_date',
        'total_amount',
        'notes',
        'created_at',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceReceiptItem::class, 'ir_id');
    }
}
