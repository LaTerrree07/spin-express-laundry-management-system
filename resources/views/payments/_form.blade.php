@php
    $isPaidRecord = isset($payment) && $payment->isPaid();
    $isAdmin = auth()->user()?->role === 'admin';
    $isLimitedCorrection = $isPaidRecord && $isAdmin;
@endphp

<div x-data="paymentForm()" x-init="init()" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div class="lg:col-span-2">
        <label for="transaction_id" class="mb-2 block text-sm font-medium text-[#1E293B]">Transaction</label>
        <select
            name="transaction_id"
            id="transaction_id"
            x-model="selectedTransactionId"
            @change="syncAmount()"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C] {{ $isLimitedCorrection ? 'bg-slate-50 text-slate-500' : '' }}"
            {{ $isLimitedCorrection ? 'disabled' : '' }}
            required
        >
            <option value="">Select transaction</option>
            @foreach ($transactions as $transaction)
                <option
                    value="{{ $transaction->transaction_id }}"
                    data-amount="{{ (float) $transaction->total_amount }}"
                    @selected(old('transaction_id', $payment->transaction_id ?? '') == $transaction->transaction_id)
                >
                    #{{ $transaction->transaction_id }} — {{ $transaction->customer->full_name }} — {{ $transaction->serviceType->service_name }} — ₱{{ number_format((float) $transaction->total_amount, 2) }}
                </option>
            @endforeach
        </select>

        @if ($isLimitedCorrection)
            <input type="hidden" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}">
        @endif

        @error('transaction_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="payment_amount" class="mb-2 block text-sm font-medium text-[#1E293B]">Payment Amount</label>
        <input
            type="number"
            name="payment_amount"
            id="payment_amount"
            step="0.01"
            min="0"
            x-model="paymentAmount"
            readonly
            class="w-full rounded-2xl border border-[#D6DEE8] bg-slate-50 px-4 py-3 text-sm text-[#1E293B]"
        >

        @if ($isLimitedCorrection)
            <input type="hidden" name="payment_amount" value="{{ old('payment_amount', $payment->payment_amount) }}">
        @endif

        @error('payment_amount')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            Auto-filled from the selected transaction. This always matches the transaction total amount.
        </p>
    </div>

    <div>
        <label for="payment_status" class="mb-2 block text-sm font-medium text-[#1E293B]">Payment Status</label>
        <select
            name="payment_status"
            id="payment_status"
            x-model="paymentStatus"
            @change="syncPaymentFields()"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C] {{ $isLimitedCorrection ? 'bg-slate-50 text-slate-500' : '' }}"
            {{ $isLimitedCorrection ? 'disabled' : '' }}
            required
        >
            <option value="">Select payment status</option>
            <option value="Paid" @selected(old('payment_status', $payment->payment_status ?? '') === 'Paid')>Paid</option>
            <option value="Unpaid" @selected(old('payment_status', $payment->payment_status ?? '') === 'Unpaid')>Unpaid</option>
        </select>

        @if ($isLimitedCorrection)
            <input type="hidden" name="payment_status" value="Paid">
        @endif

        @error('payment_status')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="payment_method" class="mb-2 block text-sm font-medium text-[#1E293B]">Payment Method</label>
        <select
            name="payment_method"
            id="payment_method"
            x-model="paymentMethod"
            :disabled="paymentStatus !== 'Paid'"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] disabled:bg-slate-50 disabled:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >
            <option value="">Select payment method</option>
            <option value="Cash" @selected(old('payment_method', $payment->payment_method ?? '') === 'Cash')>Cash</option>
            <option value="GCash" @selected(old('payment_method', $payment->payment_method ?? '') === 'GCash')>GCash</option>
        </select>
        @error('payment_method')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            {{ $isLimitedCorrection ? 'Admin may correct only the payment method and paid date for a paid payment.' : 'Required only when the payment is marked as Paid.' }}
        </p>
    </div>

    <div>
        <label for="paid_at" class="mb-2 block text-sm font-medium text-[#1E293B]">Paid At</label>
        <input
            type="datetime-local"
            name="paid_at"
            id="paid_at"
            x-model="paidAt"
            :disabled="paymentStatus !== 'Paid'"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] disabled:bg-slate-50 disabled:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >
        @error('paid_at')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            {{ $isLimitedCorrection ? 'Admin may correct the settlement date and time if needed.' : 'Leave empty for unpaid transactions. Required only when the payment is settled.' }}
        </p>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a
        href="{{ route('payments.index') }}"
        class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
    >
        {{ $buttonText }}
    </button>
</div>

<script>
    function paymentForm() {
        return {
            selectedTransactionId: @json(old('transaction_id', $payment->transaction_id ?? '')),
            paymentAmount: @json(old('payment_amount', $payment->payment_amount ?? '')),
            paymentStatus: @json(old('payment_status', $payment->payment_status ?? '')),
            paymentMethod: @json(old('payment_method', $payment->payment_method ?? '')),
            paidAt: @json(
                old(
                    'paid_at',
                    isset($payment) && $payment->paid_at
                        ? $payment->paid_at->format('Y-m-d\\TH:i')
                        : ''
                )
            ),

            init() {
                this.syncAmount();
                this.syncPaymentFields();
            },

            syncAmount() {
                const select = document.getElementById('transaction_id');
                const option = select.options[select.selectedIndex];

                if (option && option.dataset.amount) {
                    this.paymentAmount = parseFloat(option.dataset.amount).toFixed(2);
                } else {
                    this.paymentAmount = this.paymentAmount || '';
                }
            },

            syncPaymentFields() {
                if (this.paymentStatus !== 'Paid') {
                    this.paymentMethod = '';
                    this.paidAt = '';
                    return;
                }

                if (!this.paidAt) {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');

                    this.paidAt = `${year}-${month}-${day}T${hours}:${minutes}`;
                }
            }
        }
    }
</script>