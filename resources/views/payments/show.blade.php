<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Payment Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">Transaction</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">#{{ $payment->transaction->transaction_id }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Customer</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $payment->transaction->customer->full_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Service Type</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $payment->transaction->serviceType->service_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Payment Amount</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">₱{{ number_format((float) $payment->payment_amount, 2) }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Payment Method</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $payment->payment_method ?: '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Payment Status</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $payment->payment_status }}</dd>
            </div>

            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-[#64748B]">Paid At</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">
                    {{ $payment->paid_at ? $payment->paid_at->format('F d, Y h:i A') : '—' }}
                </dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('payments.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('payments.edit', $payment) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Payment
            </a>
        </div>
    </div>
</x-app-layout>