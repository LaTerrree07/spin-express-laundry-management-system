<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    public function getAnalytics(Request $request): array
    {
        [$period, $startDate, $endDate] = $this->resolveFilters($request);

        return [
            'filters' => [
                'period' => $period,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'summary' => $this->buildSummary($startDate, $endDate),
            'salesTrend' => $this->buildSalesTrend($period, $startDate, $endDate),
            'paymentMethods' => $this->buildPaymentMethodSummary($startDate, $endDate),
            'staffProductivity' => $this->buildStaffProductivity($startDate, $endDate),
            'serviceProfitability' => $this->buildServiceProfitability($startDate, $endDate),
            'customerRevenue' => $this->buildCustomerRevenue($startDate, $endDate),
            'recentTransactions' => $this->buildRecentTransactions(),
            'recentPayments' => $this->buildRecentPayments(),
        ];
    }

   protected function resolveFilters(Request $request): array
{
    $period = $request->string('period')->toString() ?: 'daily';

    if (! in_array($period, ['daily', 'weekly', 'monthly'], true)) {
        $period = 'daily';
    }

    $latestPaymentDate = DB::table('payments')
        ->whereNull('deleted_at')
        ->whereRaw('LOWER(payment_status) = ?', ['paid'])
        ->max(DB::raw('COALESCE(paid_at, updated_at, created_at)'));

    $defaultEndDate = $latestPaymentDate
        ? Carbon::parse($latestPaymentDate)->endOfDay()
        : now()->endOfDay();

    $startDate = $request->filled('start_date')
        ? Carbon::parse($request->string('start_date')->toString())->startOfDay()
        : $defaultEndDate->copy()->subDays(29)->startOfDay();

    $endDate = $request->filled('end_date')
        ? Carbon::parse($request->string('end_date')->toString())->endOfDay()
        : $defaultEndDate;

    if ($startDate->greaterThan($endDate)) {
        [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
    }

    return [$period, $startDate, $endDate];
}

    protected function buildSummary(Carbon $startDate, Carbon $endDate): array
    {
        $paidRevenue = (float) $this->basePaidPaymentsQuery($startDate, $endDate)
            ->sum('payments.payment_amount');

        $transactionsCount = DB::table('transactions')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $completedOrders = DB::table('transactions')
            ->join('statuses', 'transactions.status_id', '=', 'statuses.status_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('statuses.deleted_at')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->whereRaw('LOWER(statuses.status_name) IN (?, ?)', ['finished', 'claimed'])
            ->count();

        $pendingTransactions = DB::table('transactions')
            ->join('statuses', 'transactions.status_id', '=', 'statuses.status_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('statuses.deleted_at')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->whereRaw('LOWER(statuses.status_name) = ?', ['pending'])
            ->count();

        $unpaidPayments = DB::table('payments')
            ->whereNull('deleted_at')
            ->whereRaw('LOWER(payment_status) = ?', ['unpaid'])
            ->count();

        return [
            'transactions_count' => $transactionsCount,
            'completed_orders' => $completedOrders,
            'pending_transactions' => $pendingTransactions,
            'unpaid_payments' => $unpaidPayments,
            'paid_revenue' => $paidRevenue,
        ];
    }

    protected function buildSalesTrend(string $period, Carbon $startDate, Carbon $endDate): array
    {
        $baseQuery = $this->basePaidPaymentsQuery($startDate, $endDate);

        if ($period === 'weekly') {
            $rows = (clone $baseQuery)
                ->selectRaw("
                    DATE(DATE_SUB(COALESCE(payments.paid_at, payments.updated_at, payments.created_at), INTERVAL WEEKDAY(COALESCE(payments.paid_at, payments.updated_at, payments.created_at)) DAY)) as bucket_date,
                    SUM(payments.payment_amount) as total_amount,
                    COUNT(payments.payment_id) as total_transactions
                ")
                ->groupByRaw("
                    DATE(DATE_SUB(COALESCE(payments.paid_at, payments.updated_at, payments.created_at), INTERVAL WEEKDAY(COALESCE(payments.paid_at, payments.updated_at, payments.created_at)) DAY))
                ")
                ->orderBy('bucket_date')
                ->get()
                ->keyBy(fn ($row) => Carbon::parse($row->bucket_date)->format('Y-m-d'));

            $periodRange = CarbonPeriod::create(
                $startDate->copy()->startOfWeek(),
                '1 week',
                $endDate->copy()->endOfWeek()
            );

            $points = collect();

            foreach ($periodRange as $date) {
                $key = $date->format('Y-m-d');
                $row = $rows->get($key);

                $points->push([
                    'key' => $key,
                    'label' => $date->format('M d'),
                    'sub_label' => $date->copy()->endOfWeek()->format('M d'),
                    'full_label' => $date->format('M d') . ' - ' . $date->copy()->endOfWeek()->format('M d, Y'),
                    'amount' => $row ? (float) $row->total_amount : 0,
                    'count' => $row ? (int) $row->total_transactions : 0,
                ]);
            }
        } elseif ($period === 'monthly') {
            $rows = (clone $baseQuery)
                ->selectRaw("
                    DATE_FORMAT(COALESCE(payments.paid_at, payments.updated_at, payments.created_at), '%Y-%m-01') as bucket_date,
                    SUM(payments.payment_amount) as total_amount,
                    COUNT(payments.payment_id) as total_transactions
                ")
                ->groupByRaw("
                    DATE_FORMAT(COALESCE(payments.paid_at, payments.updated_at, payments.created_at), '%Y-%m-01')
                ")
                ->orderBy('bucket_date')
                ->get()
                ->keyBy(fn ($row) => Carbon::parse($row->bucket_date)->format('Y-m-d'));

            $periodRange = CarbonPeriod::create(
                $startDate->copy()->startOfMonth(),
                '1 month',
                $endDate->copy()->startOfMonth()
            );

            $points = collect();

            foreach ($periodRange as $date) {
                $key = $date->format('Y-m-d');
                $row = $rows->get($key);

                $points->push([
                    'key' => $key,
                    'label' => $date->format('M Y'),
                    'sub_label' => '',
                    'full_label' => $date->format('F Y'),
                    'amount' => $row ? (float) $row->total_amount : 0,
                    'count' => $row ? (int) $row->total_transactions : 0,
                ]);
            }
        } else {
            $rows = (clone $baseQuery)
                ->selectRaw("
                    DATE(COALESCE(payments.paid_at, payments.updated_at, payments.created_at)) as bucket_date,
                    SUM(payments.payment_amount) as total_amount,
                    COUNT(payments.payment_id) as total_transactions
                ")
                ->groupByRaw("
                    DATE(COALESCE(payments.paid_at, payments.updated_at, payments.created_at))
                ")
                ->orderBy('bucket_date')
                ->get()
                ->keyBy(fn ($row) => Carbon::parse($row->bucket_date)->format('Y-m-d'));

            $periodRange = CarbonPeriod::create(
                $startDate->copy()->startOfDay(),
                '1 day',
                $endDate->copy()->startOfDay()
            );

            $points = collect();

            foreach ($periodRange as $date) {
                $key = $date->format('Y-m-d');
                $row = $rows->get($key);

                $points->push([
                    'key' => $key,
                    'label' => $date->format('M d'),
                    'sub_label' => '',
                    'full_label' => $date->format('M d, Y'),
                    'amount' => $row ? (float) $row->total_amount : 0,
                    'count' => $row ? (int) $row->total_transactions : 0,
                ]);
            }
        }

        return [
            'period' => $period,
            'points' => $points,
            'labels' => $points->pluck('label')->values(),
            'full_labels' => $points->pluck('full_label')->values(),
            'amounts' => $points->pluck('amount')->map(fn ($value) => round((float) $value, 2))->values(),
            'counts' => $points->pluck('count')->values(),
            'total_sales' => (float) $points->sum('amount'),
            'average_sales' => (float) ($points->count() ? $points->avg('amount') : 0),
        ];
    }

    protected function buildPaymentMethodSummary(Carbon $startDate, Carbon $endDate): array
    {
        $rows = $this->basePaidPaymentsQuery($startDate, $endDate)
            ->whereIn('payments.payment_method', ['Cash', 'GCash'])
            ->selectRaw("
                payments.payment_method,
                COUNT(payments.payment_id) as total_count,
                SUM(payments.payment_amount) as total_amount
            ")
            ->groupBy('payments.payment_method')
            ->get()
            ->keyBy('payment_method');

        $cashCount = (int) optional($rows->get('Cash'))->total_count;
        $cashAmount = (float) optional($rows->get('Cash'))->total_amount;
        $gcashCount = (int) optional($rows->get('GCash'))->total_count;
        $gcashAmount = (float) optional($rows->get('GCash'))->total_amount;

        return [
            'totals' => [
                'count' => $cashCount + $gcashCount,
                'amount' => $cashAmount + $gcashAmount,
            ],
            'methods' => [
                [
                    'name' => 'Cash',
                    'count' => $cashCount,
                    'amount' => $cashAmount,
                ],
                [
                    'name' => 'GCash',
                    'count' => $gcashCount,
                    'amount' => $gcashAmount,
                ],
            ],
        ];
    }

    protected function buildStaffProductivity(Carbon $startDate, Carbon $endDate): Collection
    {
        return DB::table('transactions')
            ->join('staff', 'transactions.staff_id', '=', 'staff.staff_id')
            ->join('statuses', 'transactions.status_id', '=', 'statuses.status_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('staff.deleted_at')
            ->whereNull('statuses.deleted_at')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->selectRaw("
                staff.staff_id,
                CONCAT_WS(' ', staff.sf_name, staff.sm_name, staff.sl_name) as staff_name,
                COUNT(transactions.transaction_id) as total_transactions,
                SUM(CASE WHEN LOWER(statuses.status_name) IN ('finished', 'claimed') THEN 1 ELSE 0 END) as completed_orders,
                COALESCE(SUM(transactions.number_of_loads), 0) as total_loads
            ")
            ->groupBy('staff.staff_id', 'staff.sf_name', 'staff.sm_name', 'staff.sl_name')
            ->orderByDesc('total_transactions')
            ->orderByDesc('completed_orders')
            ->orderByDesc('total_loads')
            ->limit(10)
            ->get();
    }

    protected function buildServiceProfitability(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->basePaidPaymentsQuery($startDate, $endDate)
            ->join('transactions', 'payments.transaction_id', '=', 'transactions.transaction_id')
            ->join('service_types', 'transactions.service_type_id', '=', 'service_types.service_type_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('service_types.deleted_at')
            ->selectRaw("
                service_types.service_type_id,
                service_types.service_name,
                COUNT(payments.payment_id) as total_transactions,
                SUM(payments.payment_amount) as total_revenue
            ")
            ->groupBy('service_types.service_type_id', 'service_types.service_name')
            ->orderByDesc('total_revenue')
            ->limit(8)
            ->get();
    }

    protected function buildCustomerRevenue(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->basePaidPaymentsQuery($startDate, $endDate)
            ->join('transactions', 'payments.transaction_id', '=', 'transactions.transaction_id')
            ->join('customers', 'transactions.customer_id', '=', 'customers.customer_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('customers.deleted_at')
            ->selectRaw("
                customers.customer_id,
                CONCAT_WS(' ', customers.cf_name, customers.cm_name, customers.cl_name) as customer_name,
                COUNT(payments.payment_id) as paid_transactions,
                SUM(payments.payment_amount) as total_revenue
            ")
            ->groupBy('customers.customer_id', 'customers.cf_name', 'customers.cm_name', 'customers.cl_name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
    }

    protected function buildRecentTransactions(): Collection
    {
        return DB::table('transactions')
            ->join('customers', 'transactions.customer_id', '=', 'customers.customer_id')
            ->join('service_types', 'transactions.service_type_id', '=', 'service_types.service_type_id')
            ->join('staff', 'transactions.staff_id', '=', 'staff.staff_id')
            ->join('statuses', 'transactions.status_id', '=', 'statuses.status_id')
            ->whereNull('transactions.deleted_at')
            ->whereNull('customers.deleted_at')
            ->whereNull('service_types.deleted_at')
            ->whereNull('staff.deleted_at')
            ->whereNull('statuses.deleted_at')
            ->selectRaw("
                transactions.transaction_id,
                CONCAT_WS(' ', customers.cf_name, customers.cm_name, customers.cl_name) as customer_name,
                service_types.service_name,
                CONCAT_WS(' ', staff.sf_name, staff.sm_name, staff.sl_name) as staff_name,
                statuses.status_name,
                transactions.total_amount,
                transactions.created_at
            ")
            ->orderByDesc('transactions.created_at')
            ->limit(5)
            ->get();
    }

    protected function buildRecentPayments(): Collection
    {
        return DB::table('payments')
            ->join('transactions', 'payments.transaction_id', '=', 'transactions.transaction_id')
            ->join('customers', 'transactions.customer_id', '=', 'customers.customer_id')
            ->whereNull('payments.deleted_at')
            ->whereNull('transactions.deleted_at')
            ->whereNull('customers.deleted_at')
            ->selectRaw("
                payments.payment_id,
                transactions.transaction_id,
                CONCAT_WS(' ', customers.cf_name, customers.cm_name, customers.cl_name) as customer_name,
                payments.payment_amount,
                payments.payment_method,
                payments.payment_status,
                payments.paid_at,
                payments.created_at
            ")
            ->orderByDesc('payments.created_at')
            ->limit(5)
            ->get();
    }

    protected function basePaidPaymentsQuery(Carbon $startDate, Carbon $endDate): Builder
    {
        return DB::table('payments')
            ->whereNull('payments.deleted_at')
            ->whereRaw('LOWER(payments.payment_status) = ?', ['paid'])
            ->whereBetween(
                DB::raw('COALESCE(payments.paid_at, payments.updated_at, payments.created_at)'),
                [$startDate, $endDate]
            );
    }
}