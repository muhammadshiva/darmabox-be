<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTaskAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_task_id',
        'path',
        'label',
    ];

    public function task()
    {
        return $this->belongsTo(ProductionTask::class, 'production_task_id');
    }
}
