<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTaskMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_task_id',
        'material_id',
        'material_name',
        'quantity',
        'unit',
        'readiness',
    ];

    public function task()
    {
        return $this->belongsTo(ProductionTask::class, 'production_task_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
