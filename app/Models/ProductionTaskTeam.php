<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTaskTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_task_id',
        'user_id',
        'role',
    ];

    public function task()
    {
        return $this->belongsTo(ProductionTask::class, 'production_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
