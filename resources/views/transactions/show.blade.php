<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Transaction Details</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
            <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Customer</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->customer->full_name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Service Type</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->serviceType->service_name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Service Category</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->serviceType->serviceCategory->category_name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Staff</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->staff->full_name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Status</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->status->status_name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Weight</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">
                        {{ $transaction->weight_kg ? number_format((float) $transaction->weight_kg, 2).' kg' : '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Number of Loads</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->number_of_loads }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Base Service Price</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">
                        ₱{{ number_format((float) $transaction->serviceType->price, 2) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Total Amount</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">₱{{ number_format((float) $transaction->total_amount, 2) }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Payment Status</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">
                        {{ $transaction->payment?->payment_status ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-[#64748B]">Payment Method</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">
                        {{ $transaction->payment?->payment_method ?: '—' }}
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-[#64748B]">Remarks</dt>
                    <dd class="mt-1 text-sm text-[#1E293B]">{{ $transaction->remarks ?: '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-[#1E293B]">Extra Items</h3>

            @if ($transaction->transactionExtraItems->isNotEmpty())
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#D6DEE8]">
                        <thead class="bg-[#EEF4FA]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D6DEE8] bg-white">
                            @foreach ($transaction->transactionExtraItems as $transactionExtraItem)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-[#1E293B]">{{ $transactionExtraItem->extraItem->item_name }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transactionExtraItem->quantity }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format((float) $transactionExtraItem->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-4 text-sm text-[#64748B]">No extra items added.</p>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('transactions.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('transactions.edit', $transaction) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Transaction
            </a>
        </div>
    </div>
</x-app-layout>