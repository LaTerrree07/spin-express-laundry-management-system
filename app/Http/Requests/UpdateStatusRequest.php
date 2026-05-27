<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('statuses', 'status_name')->ignore($this->route('status')),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}