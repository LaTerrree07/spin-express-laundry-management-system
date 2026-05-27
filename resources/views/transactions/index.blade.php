<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-semibold text-[#1E293B]">Transactions</h2>

            <a
                href="{{ route('transactions.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F213E] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Add Transaction</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary cards --}}
        <div class="overflow-x-auto">
            <div class="flex min-w-[1100px] gap-4">
                <div class="flex-1 min-w-0 rounded-2xl border border-[#BFDBFE] bg-[#F8FBFF] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#EAF4FF] text-[#0F213E]">
                                <i data-lucide="receipt-text" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#0F213E]">All Transactions</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#0F213E]">{{ $summaryCounts['all'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#F6E7A8] bg-[#FFFBEA] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#FFF4CC] text-[#A16207]">
                                <i data-lucide="clock-3" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#A16207]">Pending</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#92400E]">{{ $summaryCounts['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#BFDBFE] bg-[#EFF6FF] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#DBEAFE] text-[#1D4ED8]">
                                <i data-lucide="loader-circle" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#1D4ED8]">Ongoing</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#1E40AF]">{{ $summaryCounts['ongoing'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#BBF7D0] bg-[#F0FDF4] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#DCFCE7] text-[#15803D]">
                                <i data-lucide="circle-check-big" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#15803D]">Finished</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#166534]">{{ $summaryCounts['finished'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#E2E8F0] text-[#475569]">
                                <i data-lucide="package-check" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#475569]">Claimed</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#334155]">{{ $summaryCounts['claimed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters and search --}}
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4 shadow-sm">
            <div class="flex flex-wrap gap-2">
                @foreach (['all', 'pending', 'ongoing', 'finished', 'claimed'] as $segment)
                    <a
                        href="{{ route('transactions.index', array_filter(['status' => $segment !== 'all' ? $segment : null, 'search' => $search ?: null])) }}"
                        class="rounded-2xl px-4 py-2 text-sm font-medium transition {{ ($statusFilter ?: 'all') === $segment ? 'bg-[#0F213E] text-white' : 'bg-[#EAF4FF] text-[#0F213E] hover:bg-[#D9ECFF]' }}"
                    >
                        {{ ucfirst($segment) }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('transactions.index') }}" class="mt-4 flex flex-col gap-3 lg:flex-row">
                <input type="hidden" name="status" value="{{ $statusFilter }}">

                <div class="relative flex-1">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search customer, service type, or status"
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
                        href="{{ route('transactions.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#D6DEE8] bg-white px-4 py-3 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
                    >
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-[#D6DEE8] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#D6DEE8]">
                    <thead class="bg-[#EEF4FA]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Service Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Staff</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Weight</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Loads</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6DEE8] bg-white">
                        @forelse ($transactions as $transaction)
                            @php
                                $statusName = strtolower(trim($transaction->status->status_name));
                                $statusClasses = match ($statusName) {
                                    'pending' => 'border border-[#F6E7A8] bg-[#FFF4CC] text-[#A16207]',
                                    'ongoing' => 'border border-[#BFDBFE] bg-[#DBEAFE] text-[#1D4ED8]',
                                    'finished' => 'border border-[#BBF7D0] bg-[#DCFCE7] text-[#15803D]',
                                    'claimed' => 'border border-[#CBD5E1] bg-[#E2E8F0] text-[#475569]',
                                    default => 'border border-slate-200 bg-slate-100 text-slate-600',
                                };
                            @endphp

                            <tr class="hover:bg-[#F8FBFF]">
                                <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">{{ $transaction->customer->full_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transaction->serviceType->service_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transaction->staff->full_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">
                                    {{ $transaction->weight_kg ? number_format((float) $transaction->weight_kg, 2).' kg' : '—' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transaction->number_of_loads }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format((float) $transaction->total_amount, 2) }}</td>
                                <td class="px-4 py-4 text-sm">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ $transaction->status->status_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('transactions.show', $transaction) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-[#D6DEE8] bg-white p-2 text-[#1E293B] transition hover:bg-slate-50"
                                            title="View"
                                            aria-label="View"
                                        >
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a
                                            href="{{ route('transactions.edit', $transaction) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-[#EAF4FF] p-2 text-[#0F213E] transition hover:bg-[#D9ECFF]"
                                            title="Edit"
                                            aria-label="Edit"
                                        >
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <x-delete-confirm-modal
                                                :id="'delete-transaction-'.$transaction->transaction_id"
                                                title="Delete Transaction"
                                                message="Are you sure you want to delete this transaction? This action cannot be undone."
                                                :action="route('transactions.destroy', $transaction)"
                                            />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('layouts.partials.table-pagination', ['paginator' => $transactions])
        </div>
    </div>
</x-app-layout>