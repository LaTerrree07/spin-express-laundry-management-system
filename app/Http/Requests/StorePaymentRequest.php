<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => [
                'required',
                'exists:transactions,transaction_id',
                'unique:payments,transaction_id',
            ],
            'payment_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', Rule::in(['Cash', 'GCash'])],
            'payment_status' => ['required', Rule::in(['Paid', 'Unpaid'])],
            'paid_at' => ['nullable', 'date'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $status = $this->input('payment_status');
            $method = $this->input('payment_method');
            $paidAt = $this->input('paid_at');

            if ($status === 'Paid') {
                if (blank($method)) {
                    $validator->errors()->add('payment_method', 'Payment method is required when payment status is Paid.');
                }

                if (blank($paidAt)) {
                    $validator->errors()->add('paid_at', 'Paid at is required when payment status is Paid.');
                }
            }

            if ($status === 'Unpaid') {
                if (filled($method)) {
                    $validator->errors()->add('payment_method', 'Payment method must be empty when payment status is Unpaid.');
                }

                if (filled($paidAt)) {
                    $validator->errors()->add('paid_at', 'Paid at must be empty when payment status is Unpaid.');
                }
            }
        });
    }
}