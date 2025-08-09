<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_type' => ['required', 'in:dp,final'],
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
