<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type',
        'source',
        'reference',
        'amount',
        'date',
        'note'
    ];
}
