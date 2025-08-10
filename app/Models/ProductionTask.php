<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'order_id',
        'title',
        'priority',
        'description',
        'start_date',
        'due_date',
        'estimated_hours',
        'status',
        'progress',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(ProductionTaskMaterial::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(ProductionTaskTeam::class);
    }

    public function attachments()
    {
        return $this->hasMany(ProductionTaskAttachment::class);
    }
}
