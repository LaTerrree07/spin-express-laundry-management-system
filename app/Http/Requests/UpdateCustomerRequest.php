<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cf_name' => ['required', 'string', 'max:50'],
            'cm_name' => ['nullable', 'string', 'max:50'],
            'cl_name' => ['required', 'string', 'max:50'],
            'contact_number' => ['required', 'string', 'max:20'],
        ];
    }
}