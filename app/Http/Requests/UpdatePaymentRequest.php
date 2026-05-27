<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Payment $payment */
        $payment = $this->route('payment');

        return $payment && $payment->canBeEditedBy($this->user());
    }

    public function rules(): array
    {
        /** @var Payment $payment */
        $payment = $this->route('payment');

        $isPaid = $payment?->isPaid();
        $isAdmin = $this->user()?->role === 'admin';

        if ($isPaid && $isAdmin) {
            return [
                'transaction_id' => ['required'],
                'payment_amount' => ['nullable'],
                'payment_method' => ['nullable', Rule::in(['Cash', 'GCash'])],
                'payment_status' => ['required', Rule::in(['Paid'])],
                'paid_at' => ['nullable', 'date'],
            ];
        }

        return [
            'transaction_id' => [
                'required',
                'exists:transactions,transaction_id',
                Rule::unique('payments', 'transaction_id')->ignore(
                    $payment?->payment_id,
                    'payment_id'
                ),
            ],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', Rule::in(['Cash', 'GCash'])],
            'payment_status' => ['required', Rule::in(['Paid', 'Unpaid'])],
            'paid_at' => ['nullable', 'date'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var Payment $payment */
            $payment = $this->route('payment');

            $status = $this->input('payment_status');
            $method = $this->input('payment_method');
            $paidAt = $this->input('paid_at');
            $isAdmin = $this->user()?->role === 'admin';

            if ($payment?->isPaid()) {
                if (! $isAdmin) {
                    $validator->errors()->add('payment', 'Only admins can correct paid payment records.');
                    return;
                }

                if ($this->input('transaction_id') != $payment->transaction_id) {
                    $validator->errors()->add('transaction_id', 'Transaction cannot be changed for a paid payment.');
                }

                if ($status !== 'Paid') {
                    $validator->errors()->add('payment_status', 'Paid payment status cannot be changed.');
                }

                return;
            }

            if ($status === 'Paid') {
                if (blank($method)) {
                    $validator->errors()->add('payment_method', 'Payment method is required when payment status is Paid.');
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