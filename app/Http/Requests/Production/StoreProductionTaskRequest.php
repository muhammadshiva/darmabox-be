<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'production_id' => ['nullable', 'exists:productions,id'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['nullable', 'in:low,normal,high'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:not_started,waiting,in_progress,completed,blocked'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'materials' => ['sometimes', 'array'],
            'materials.*.material_id' => ['nullable', 'exists:materials,id'],
            'materials.*.material_name' => ['required', 'string'],
            'materials.*.quantity' => ['required', 'numeric', 'min:0'],
            'materials.*.unit' => ['nullable', 'string', 'max:16'],
            'materials.*.readiness' => ['nullable', 'in:not_ready,partial,ready,pending'],
            'team' => ['sometimes', 'array'],
            'team.*.user_id' => ['required', 'exists:users,id'],
            'team.*.role' => ['nullable', 'string', 'max:255'],
        ];
    }
}
