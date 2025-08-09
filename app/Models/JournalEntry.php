<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'journal_id',
        'account_id',
        'debit',
        'credit'
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function ledgerEntry()
    {
        return $this->hasOne(LedgerEntry::class);
    }
}
