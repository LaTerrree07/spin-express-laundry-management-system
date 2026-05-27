<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_category_id' => ['required', 'exists:service_categories,service_category_id'],
            'service_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('service_types', 'service_name')
                    ->where(fn ($query) => $query->where('service_category_id', $this->service_category_id))
                    ->ignore($this->route('service_type'), 'service_type_id'),
            ],
            'price' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_category_id.required' => 'Service category is required.',
            'service_category_id.exists' => 'Selected service category is invalid.',
            'service_name.required' => 'Service name is required.',
            'service_name.unique' => 'This service name already exists under the selected category.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.gt' => 'Price must be greater than 0.',
        ];
    }
}