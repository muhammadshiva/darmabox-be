<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionTaskResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'production_id' => $this->production_id,
            'order_id' => $this->order_id,
            'title' => $this->title,
            'priority' => $this->priority,
            'description' => $this->description,
            'start_date' => optional($this->start_date)->toDateString(),
            'due_date' => optional($this->due_date)->toDateString(),
            'estimated_hours' => $this->estimated_hours,
            'status' => $this->status,
            'progress' => $this->progress,
            'assigned_to' => $this->assigned_to,
            'assignee' => $this->whenLoaded('assignee', fn() => [
                'id' => $this->assignee?->id,
                'name' => $this->assignee?->name,
            ]),
            'order' => $this->whenLoaded('order', fn() => [
                'id' => $this->order?->id,
                'invoice_code' => $this->order?->invoice_code,
                'customer' => $this->order?->customer?->name,
            ]),
            'materials' => $this->whenLoaded('materials', fn() => $this->materials->map(function ($m) {
                return [
                    'id' => $m->id,
                    'material_id' => $m->material_id,
                    'material_name' => $m->material_name,
                    'quantity' => (float) $m->quantity,
                    'unit' => $m->unit,
                    'readiness' => $m->readiness,
                ];
            })),
            'team' => $this->whenLoaded('teamMembers', fn() => $this->teamMembers->map(function ($t) {
                return [
                    'id' => $t->id,
                    'user' => [
                        'id' => $t->user?->id,
                        'name' => $t->user?->name,
                    ],
                    'role' => $t->role,
                ];
            })),
            'attachments' => $this->whenLoaded('attachments', fn() => $this->attachments->map(function ($a) {
                return [
                    'id' => $a->id,
                    'url' => $a->path ? asset('storage/' . ltrim($a->path, '/')) : null,
                    'label' => $a->label,
                ];
            })),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
