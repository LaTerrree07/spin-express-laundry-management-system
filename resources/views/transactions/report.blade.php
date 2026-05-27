<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-[#1E293B]">Transaction Details Report</h2>
                
            </div>

            <a
                href="{{ route('transactions.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                <span>Back to Transactions</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('reports.transactions') }}" class="flex flex-col gap-3 sm:flex-row">
                <div class="relative w-full">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search customer, service, category, staff, or status"
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
                        href="{{ route('reports.transactions') }}"
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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Staff</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Loads</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6DEE8] bg-white">
                        @forelse ($transactionDetails as $row)
                            <tr class="hover:bg-[#F8FBFF]">
                                <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">#{{ $row->transaction_id }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->customer_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->service_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->service_category }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->staff_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->status_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $row->number_of_loads }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format((float) $row->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                    No transaction detail records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('layouts.partials.table-pagination', ['paginator' => $transactionDetails])
        </div>
    </div>
</x-app-layout>