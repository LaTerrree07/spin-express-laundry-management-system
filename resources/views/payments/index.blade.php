<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold text-[#1E293B]">Payments</h2>

            @if(($transactionsWithoutPayment ?? 0) > 0)
                <a
                    href="{{ route('payments.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F213E] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                >
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    <span>Add Payment</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="overflow-x-auto">
            <div class="flex min-w-[680px] gap-4">
                <div class="flex-1 min-w-0 rounded-2xl border border-[#BFDBFE] bg-[#F8FBFF] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#EAF4FF] text-[#0F213E]">
                                <i data-lucide="wallet" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#0F213E]">All Payments</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#0F213E]">{{ $summaryCounts['all'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#BBF7D0] bg-[#F0FDF4] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#DCFCE7] text-[#15803D]">
                                <i data-lucide="circle-check-big" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#15803D]">Paid</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#166534]">{{ $summaryCounts['paid'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#FECACA] bg-[#FEF2F2] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#DC2626]">
                                <i data-lucide="triangle-alert" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#DC2626]">Unpaid</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#B91C1C]">{{ $summaryCounts['unpaid'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4 shadow-sm">
            <div class="flex flex-wrap gap-2">
                @foreach (['all', 'paid', 'unpaid'] as $segment)
                    <a
                        href="{{ route('payments.index', array_filter(['status' => $segment !== 'all' ? $segment : null, 'search' => $search ?: null])) }}"
                        class="rounded-2xl px-4 py-2 text-sm font-medium transition {{ ($statusFilter ?: 'all') === $segment ? 'bg-[#0F213E] text-white' : 'bg-[#EAF4FF] text-[#0F213E] hover:bg-[#D9ECFF]' }}"
                    >
                        {{ ucfirst($segment) }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('payments.index') }}" class="mt-4 flex flex-col gap-3 lg:flex-row">
                <input type="hidden" name="status" value="{{ $statusFilter }}">

                <div class="relative flex-1">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search customer, service, payment method, or status"
                        class="w-full rounded-2xl border border-[#D6DEE8] py-3 pl-11 pr-4 text-sm text-[#1E293B] placeholder:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                    >
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F213E] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                    >
                        <i data-lucide="search" class="h-4 w-4"></i>
                        <span>Search</span>
                    </button>

                    <a
                        href="{{ route('payments.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#D6DEE8] bg-white px-4 py-3 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
                    >
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#D6DEE8] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#D6DEE8]">
                    <thead class="bg-[#EEF4FA]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Transaction</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Amount Due</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Method</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Paid At</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6DEE8] bg-white">
                        @forelse ($payments as $payment)
                            @php
                                $paymentStatus = strtolower(trim($payment->payment_status));
                                $paymentStatusClasses = match ($paymentStatus) {
                                    'paid' => 'border border-[#BBF7D0] bg-[#DCFCE7] text-[#15803D]',
                                    'unpaid' => 'border border-[#DC2626] bg-[#FEE2E2] text-[#B91C1C]',
                                    default => 'border border-slate-200 bg-slate-100 text-slate-600',
                                };

                                $user = auth()->user();
                                $canEdit = $payment->canBeEditedBy($user);
                                $canDelete = $payment->canBeDeletedBy($user);
                                $isAdminCorrection = $payment->allowsLimitedCorrection($user);
                            @endphp

                            <tr class="hover:bg-[#F8FBFF]">
                                <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">#{{ $payment->transaction->transaction_id }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $payment->transaction->customer->full_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format((float) $payment->payment_amount, 2) }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $payment->payment_method ?: '—' }}</td>
                                <td class="px-4 py-4 text-sm">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClasses }}">
                                        {{ $payment->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">
                                    {{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : '—' }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('payments.show', $payment) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-[#D6DEE8] bg-white p-2 text-[#1E293B] transition hover:bg-slate-50"
                                            title="View"
                                            aria-label="View"
                                        >
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        @if ($canEdit)
                                            <a
                                                href="{{ route('payments.edit', $payment) }}"
                                                class="inline-flex items-center justify-center rounded-xl {{ $isAdminCorrection ? 'bg-[#FFF7ED] text-[#C2410C] hover:bg-[#FFEDD5]' : ($paymentStatus === 'unpaid' ? 'bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]' : 'bg-[#EAF4FF] text-[#0F213E] hover:bg-[#D9ECFF]') }} p-2 transition"
                                                title="{{ $isAdminCorrection ? 'Correct Paid Payment' : ($paymentStatus === 'unpaid' ? 'Settle Payment' : 'Edit') }}"
                                                aria-label="{{ $isAdminCorrection ? 'Correct Paid Payment' : ($paymentStatus === 'unpaid' ? 'Settle Payment' : 'Edit') }}"
                                            >
                                                <i data-lucide="{{ $isAdminCorrection ? 'shield-check' : ($paymentStatus === 'unpaid' ? 'wallet' : 'pencil') }}" class="h-4 w-4"></i>
                                            </a>
                                        @endif

                                        @if ($canDelete)
                                            <x-delete-confirm-modal
                                                :id="'delete-payment-'.$payment->payment_id"
                                                title="Delete Payment"
                                                message="Are you sure you want to delete this payment? This action cannot be undone."
                                                :action="route('payments.destroy', $payment)"
                                            />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('layouts.partials.table-pagination', ['paginator' => $payments])
        </div>
    </div>
</x-app-layout>