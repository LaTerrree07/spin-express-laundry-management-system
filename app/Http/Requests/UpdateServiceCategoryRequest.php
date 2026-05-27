<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('service_categories', 'category_name')->ignore($this->route('service_category')),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}