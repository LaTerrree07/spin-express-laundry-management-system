<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'service_type_id' => ['required', 'exists:service_types,service_type_id'],
            'staff_id' => ['required', 'exists:staff,staff_id'],
            'status_id' => ['required', 'exists:statuses,status_id'],
            'weight_kg' => ['nullable', 'numeric', 'min:0.01'],
            'number_of_loads' => ['nullable', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string'],

            'extra_items' => ['nullable', 'array'],
            'extra_items.*.extra_item_id' => ['nullable', 'exists:extra_items,extra_item_id'],
            'extra_items.*.quantity' => ['nullable', 'integer', 'min:0'],
        ];
    }
}