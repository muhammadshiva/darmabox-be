<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'created_at'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function receivables()
    {
        return $this->hasMany(Receivable::class);
    }
}
