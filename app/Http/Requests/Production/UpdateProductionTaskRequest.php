<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionTaskRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255'],
            'priority' => ['sometimes', 'in:low,normal,high'],
            'description' => ['sometimes', 'nullable', 'string'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'estimated_hours' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'status' => ['sometimes', 'in:not_started,waiting,in_progress,completed,blocked'],
            'progress' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'assigned_to' => ['sometimes', 'nullable', 'exists:users,id'],
            'materials' => ['sometimes', 'array'],
            'materials.*.material_id' => ['nullable', 'exists:materials,id'],
            'materials.*.material_name' => ['required_with:materials', 'string'],
            'materials.*.quantity' => ['required_with:materials', 'numeric', 'min:0'],
            'materials.*.unit' => ['nullable', 'string', 'max:16'],
            'materials.*.readiness' => ['nullable', 'in:not_ready,partial,ready,pending'],
            'team' => ['sometimes', 'array'],
            'team.*.user_id' => ['required_with:team', 'exists:users,id'],
            'team.*.role' => ['nullable', 'string', 'max:255'],
        ];
    }
}
