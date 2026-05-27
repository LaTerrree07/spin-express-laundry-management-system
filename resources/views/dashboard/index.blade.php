<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-[#1E293B]">Dashboard</h2>
                <p class="text-sm text-[#64748B]">
                    Clean operational and reporting overview for Spin Express Laundry Management System.
                </p>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-3 rounded-2xl border border-[#D6DEE8] bg-white p-3 shadow-sm sm:flex-row sm:items-end">
                <div>
                    <label for="period" class="mb-1 block text-xs font-medium text-[#64748B]">View</label>
                    <select
                        name="period"
                        id="period"
                        class="rounded-xl border border-[#D6DEE8] px-3 py-2 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                    >
                        <option value="daily" @selected($filters['period'] === 'daily')>Daily</option>
                        <option value="weekly" @selected($filters['period'] === 'weekly')>Weekly</option>
                        <option value="monthly" @selected($filters['period'] === 'monthly')>Monthly</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="mb-1 block text-xs font-medium text-[#64748B]">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        id="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="rounded-xl border border-[#D6DEE8] px-3 py-2 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                    >
                </div>

                <div>
                    <label for="end_date" class="mb-1 block text-xs font-medium text-[#64748B]">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        id="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="rounded-xl border border-[#D6DEE8] px-3 py-2 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                    >
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0F213E] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                    >
                        <i data-lucide="filter" class="h-4 w-4"></i>
                        <span>Apply</span>
                    </button>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#D6DEE8] bg-white px-4 py-2 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
                    >
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary cards: limit to 5 --}}
        <div class="overflow-x-auto">
            <div class="flex min-w-[1100px] gap-4">
                <div class="flex-1 min-w-0 rounded-2xl border border-[#BFDBFE] bg-[#F8FBFF] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#EAF4FF] text-[#0F213E]">
                                <i data-lucide="receipt-text" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#0F213E]">Transactions</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#0F213E]">
                                {{ $summary['transactions_count'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#BBF7D0] bg-[#F0FDF4] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#DCFCE7] text-[#15803D]">
                                <i data-lucide="circle-check-big" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#15803D]">Completed Orders</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#166534]">
                                {{ $summary['completed_orders'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#F6E7A8] bg-[#FFFBEA] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#FFF4CC] text-[#A16207]">
                                <i data-lucide="clock-3" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#A16207]">Pending Transactions</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#92400E]">
                                {{ $summary['pending_transactions'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#FECACA] bg-[#FEF2F2] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#DC2626]">
                                <i data-lucide="triangle-alert" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#DC2626]">Unpaid Payments</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#B91C1C]">
                                {{ $summary['unpaid_payments'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 rounded-2xl border border-[#BBF7D0] bg-[#F0FDF4] px-4 py-4 shadow-sm">
                    <div class="flex h-[110px] flex-col justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#DCFCE7] text-[#15803D]">
                                <i data-lucide="banknote" class="h-5 w-5"></i>
                            </div>
                            <p class="text-sm font-medium text-[#15803D]">Paid Revenue</p>
                        </div>

                        <div class="flex justify-end">
                            <p class="text-3xl font-semibold leading-none text-[#166534]">
                                ₱{{ number_format($summary['paid_revenue'], 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales analytics --}}
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Sales Breakdown</h3>
                    <p class="text-sm text-[#64748B]">
                        Revenue trend for the selected {{ $filters['period'] }} view.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-[#64748B]">Total Sales</p>
                        <p class="mt-1 text-lg font-semibold text-[#0F213E]">
                            ₱{{ number_format($salesTrend['total_sales'], 2) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-[#64748B]">
                            Average per {{ $filters['period'] === 'daily' ? 'day' : ($filters['period'] === 'weekly' ? 'week' : 'month') }}
                        </p>
                        <p class="mt-1 text-lg font-semibold text-[#0F213E]">
                            ₱{{ number_format($salesTrend['average_sales'], 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] p-4">
                <div class="h-[340px] w-full">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment tracking + employee productivity --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-[#D6DEE8] bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Payment Tracking</h3>
                    <p class="text-sm text-[#64748B]">Cash and GCash distribution based on paid transactions.</p>
                </div>


                <div class="mt-5 space-y-4">
                    @foreach ($paymentMethods['methods'] as $method)
                       

                        <div class="rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#1E293B]">{{ $method['name'] }}</p>
                                    <p class="mt-1 text-xs text-[#64748B]">{{ $method['count'] }} transaction(s)</p>
                                </div>

                                <p class="text-sm font-semibold text-[#0F213E]">
                                    ₱{{ number_format($method['amount'], 2) }}
                                </p>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
    <div class="rounded-xl border border-[#D6DEE8] bg-white px-3 py-3">
        <p class="text-[11px] font-medium uppercase tracking-wide text-[#64748B]">Amount</p>
        <p class="mt-1 text-sm font-semibold text-[#0F213E]">₱{{ number_format($method['amount'], 2) }}</p>
    </div>

    <div class="rounded-xl border border-[#D6DEE8] bg-white px-3 py-3">
        <p class="text-[11px] font-medium uppercase tracking-wide text-[#64748B]">Transactions</p>
        <p class="mt-1 text-sm font-semibold text-[#0F213E]">{{ $method['count'] }}</p>
    </div>
</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-[#D6DEE8] bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Employee Productivity</h3>
                    <p class="text-sm text-[#64748B]">Staff activity ranking based on transactions, completions, and processed loads.</p>
                </div>

                

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#D6DEE8]">
                        <thead class="bg-[#EEF4FA]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Staff</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Transactions</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Completed</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Loads</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#D6DEE8] bg-white">
                            @forelse ($staffProductivity as $staff)
                              

                           <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">
    {{ $staff->staff_name }}
</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->total_transactions }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->completed_orders }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->total_loads }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                        No staff productivity data found for the selected range.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Profitability --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-[#D6DEE8] bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Service Profitability</h3>
                    <p class="text-sm text-[#64748B]">Top-performing services based on paid revenue.</p>
                </div>

                @php
                    $maxServiceRevenue = max(1, (float) $serviceProfitability->max('total_revenue'));
                @endphp

                <div class="mt-5 space-y-4">
                    @forelse ($serviceProfitability as $service)
                        @php
                            $serviceWidth = ($service->total_revenue / $maxServiceRevenue) * 100;
                        @endphp

                        <div class="rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-[#1E293B]">{{ $service->service_name }}</p>
                                    <p class="mt-1 text-xs text-[#64748B]">{{ $service->total_transactions }} paid transaction(s)</p>
                                </div>

                                <p class="text-sm font-semibold text-[#0F213E]">
                                    ₱{{ number_format($service->total_revenue, 2) }}
                                </p>
                            </div>

                            <div class="mt-3 h-2 rounded-full bg-[#EAF4FF]">
                                <div class="h-2 rounded-full bg-[#1E4E8C]" style="width: {{ $serviceWidth }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-[#64748B]">
                            No service profitability data found for the selected range.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-[#D6DEE8] bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Top Customers by Revenue</h3>
                    <p class="text-sm text-[#64748B]">Customers generating the highest paid revenue in the selected range.</p>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#D6DEE8]">
                        <thead class="bg-[#EEF4FA]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Paid Transactions</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Revenue</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#D6DEE8] bg-white">
                            @forelse ($customerRevenue as $customer)
                                <tr class="hover:bg-[#F8FBFF]">
                                    <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">{{ $customer->customer_name }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $customer->paid_transactions }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format($customer->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                        No customer revenue data found for the selected range.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent activity --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-[#D6DEE8] bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-[#D6DEE8] px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-[#1E293B]">Recent Transactions</h3>
                        <p class="text-sm text-[#64748B]">Latest operational activity.</p>
                    </div>

                    <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-[#0F213E] transition hover:text-[#16325C]">
                        View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#D6DEE8]">
                        <thead class="bg-[#EEF4FA]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Staff</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#D6DEE8] bg-white">
                            @forelse ($recentTransactions as $transaction)
                                @php
                                    $recentStatusName = strtolower(trim($transaction->status_name));
                                    $recentStatusClasses = match ($recentStatusName) {
                                        'pending' => 'border border-[#F6E7A8] bg-[#FFF4CC] text-[#A16207]',
                                        'ongoing' => 'border border-[#BFDBFE] bg-[#DBEAFE] text-[#1D4ED8]',
                                        'finished' => 'border border-[#BBF7D0] bg-[#DCFCE7] text-[#15803D]',
                                        'claimed' => 'border border-[#CBD5E1] bg-[#E2E8F0] text-[#475569]',
                                        default => 'border border-slate-200 bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <tr class="hover:bg-[#F8FBFF]">
                                    <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">{{ $transaction->customer_name }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transaction->service_name }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $transaction->staff_name }}</td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $recentStatusClasses }}">
                                            {{ $transaction->status_name }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                        No recent transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-[#D6DEE8] bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-[#D6DEE8] px-5 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-[#1E293B]">Recent Payments</h3>
                        <p class="text-sm text-[#64748B]">Latest collection activity.</p>
                    </div>

                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-[#0F213E] transition hover:text-[#16325C]">
                        View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#D6DEE8]">
                        <thead class="bg-[#EEF4FA]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Transaction</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#D6DEE8] bg-white">
                            @forelse ($recentPayments as $payment)
                                @php
                                    $recentPaymentStatus = strtolower(trim($payment->payment_status));
                                    $recentPaymentClasses = match ($recentPaymentStatus) {
                                        'paid' => 'border border-[#BBF7D0] bg-[#DCFCE7] text-[#15803D]',
                                        'unpaid' => 'border border-[#DC2626] bg-[#FEE2E2] text-[#B91C1C]',
                                        default => 'border border-slate-200 bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <tr class="hover:bg-[#F8FBFF]">
                                    <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">#{{ $payment->transaction_id }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">{{ $payment->customer_name }}</td>
                                    <td class="px-4 py-4 text-sm text-[#64748B]">₱{{ number_format((float) $payment->payment_amount, 2) }}</td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $recentPaymentClasses }}">
                                            {{ $payment->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                        No recent payments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endonce

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('salesTrendChart');

        if (!canvas) {
            console.log('salesTrendChart canvas not found');
            return;
        }

        const labels = @json($salesTrend['labels']);
        const fullLabels = @json($salesTrend['full_labels']);
        const amounts = @json($salesTrend['amounts']);
        const period = @json($salesTrend['period']);

        const currencyFormatter = new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
        });

        const maxTicksLimit = period === 'daily' ? 8 : (period === 'weekly' ? 10 : 12);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: amounts,
                    borderColor: '#1E4E8C',
                    backgroundColor: 'rgba(30, 78, 140, 0.10)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: amounts.length > 20 ? 2 : 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#1E4E8C',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#0F213E',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return fullLabels[context[0].dataIndex] ?? context[0].label;
                            },
                            label: function(context) {
                                return 'Revenue: ' + currencyFormatter.format(context.parsed.y || 0);
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#64748B',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: maxTicksLimit,
                            font: {
                                size: 11,
                            },
                        },
                        border: {
                            color: '#D6DEE8',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#EAF0F6',
                        },
                        ticks: {
                            color: '#64748B',
                            callback: function(value) {
                                return '₱' + Number(value).toLocaleString('en-PH');
                            },
                            font: {
                                size: 11,
                            },
                        },
                        border: {
                            color: '#D6DEE8',
                        },
                    },
                },
            },
        });
    });
</script>