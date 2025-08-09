<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockMovement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'ref_type',
        'ref_id',
        'notes',
        'created_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
