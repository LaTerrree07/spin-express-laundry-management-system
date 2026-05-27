<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_name' => ['required', 'string', 'max:50', 'unique:statuses,status_name'],
            'description' => ['nullable', 'string'],
        ];
    }
}