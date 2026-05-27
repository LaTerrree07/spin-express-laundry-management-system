<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $statusFilter = $request->string('status')->toString();

        $payments = Payment::query()
            ->with(['transaction.customer', 'transaction.serviceType'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payment_method', 'like', "%{$search}%")
                        ->orWhere('payment_status', 'like', "%{$search}%")
                        ->orWhereHas('transaction.customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('cf_name', 'like', "%{$search}%")
                                ->orWhere('cm_name', 'like', "%{$search}%")
                                ->orWhere('cl_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('transaction.serviceType', function ($serviceTypeQuery) use ($search) {
                            $serviceTypeQuery->where('service_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->whereRaw('LOWER(payment_status) = ?', [strtolower($statusFilter)]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summaryCounts = [
            'all' => Payment::count(),
            'paid' => Payment::whereRaw('LOWER(payment_status) = ?', ['paid'])->count(),
            'unpaid' => Payment::whereRaw('LOWER(payment_status) = ?', ['unpaid'])->count(),
        ];

        $transactionsWithoutPayment = Transaction::doesntHave('payment')->count();

        return view('payments.index', compact(
            'payments',
            'search',
            'statusFilter',
            'summaryCounts',
            'transactionsWithoutPayment'
        ));
    }

    public function create(): View
    {
        $transactions = Transaction::with(['customer', 'serviceType'])
            ->doesntHave('payment')
            ->latest()
            ->get();

        return view('payments.create', compact('transactions'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $transaction = Transaction::findOrFail($data['transaction_id']);

            $insertData = [
                'transaction_id' => $transaction->transaction_id,
                'payment_amount' => null,
                'payment_method' => ($data['payment_status'] ?? null) === 'Paid'
                    ? ($data['payment_method'] ?? null)
                    : null,
                'payment_status' => $data['payment_status'],
                'paid_at' => ($data['payment_status'] ?? null) === 'Paid'
                    ? ($data['paid_at'] ?? null)
                    : null,
            ];

            Payment::create($insertData);

            return redirect()
                ->route('payments.index')
                ->with('success', 'Payment added successfully.');
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->with('error', 'Database rule violation: '.$e->getMessage());
        }
    }

    public function show(Payment $payment): View
    {
        $payment->load(['transaction.customer', 'transaction.serviceType']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): RedirectResponse|View
    {
        $user = auth()->user();

        if (! $payment->canBeEditedBy($user)) {
            return redirect()
                ->route('payments.index')
                ->with('error', 'Paid payments can no longer be edited.');
        }

        if ($payment->isPaid() && $user->role !== 'admin') {
            return redirect()
                ->route('payments.index')
                ->with('error', 'Only admins can correct paid payment records.');
        }

        $transactions = Transaction::with(['customer', 'serviceType'])
            ->where(function ($query) use ($payment) {
                $query->whereDoesntHave('payment')
                    ->orWhere('transaction_id', $payment->transaction_id);
            })
            ->latest()
            ->get()
            ->unique('transaction_id')
            ->values();

        return view('payments.edit', compact('payment', 'transactions'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        try {
            $transaction = $payment->transaction;

            if (! $transaction) {
                return back()
                    ->withInput()
                    ->with('error', 'The linked transaction could not be found.');
            }

            $data = $request->validated();
            $user = auth()->user();

            if ($payment->allowsLimitedCorrection($user)) {
                $data = $this->normalizePaidCorrectionData($data, $payment, $transaction);
                $payment->update($data);

                return redirect()
                    ->route('payments.index')
                    ->with('success', 'Paid payment correction saved successfully.');
            }

            $transaction = Transaction::findOrFail($data['transaction_id']);
            $data = $this->normalizePaymentData($data, $transaction);

            $payment->update($data);

            return redirect()
                ->route('payments.index')
                ->with('success', 'Payment updated successfully.');
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $user = auth()->user();

        if (! $payment->canBeDeletedBy($user)) {
            return redirect()
                ->route('payments.index')
                ->with('error', 'Paid payments cannot be deleted.');
        }

        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    protected function normalizePaymentData(array $data, Transaction $transaction): array
    {
        $data['payment_amount'] = (float) $transaction->total_amount;

        if (($data['payment_status'] ?? null) === 'Unpaid') {
            $data['payment_method'] = null;
            $data['paid_at'] = null;
        }

        if (($data['payment_status'] ?? null) === 'Paid') {
            if (blank($data['payment_method'] ?? null)) {
                throw new InvalidArgumentException('Payment method is required when payment status is Paid.');
            }

            if (blank($data['paid_at'] ?? null)) {
                $data['paid_at'] = now();
            }
        }

        return $data;
    }

    protected function normalizePaidCorrectionData(array $data, Payment $payment, Transaction $transaction): array
    {
        return [
            'transaction_id' => $payment->transaction_id,
            'payment_amount' => (float) $transaction->total_amount,
            'payment_status' => 'Paid',
            'payment_method' => $data['payment_method'] ?? $payment->payment_method,
            'paid_at' => filled($data['paid_at'] ?? null)
                ? $data['paid_at']
                : ($payment->paid_at ?? now()),
        ];
    }

    public function report(Request $request): View
    {
        $search = $request->string('search')->toString();

        $paymentDetails = DB::table('vw_payment_details')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('service_name', 'like', "%{$search}%")
                        ->orWhere('payment_method', 'like', "%{$search}%")
                        ->orWhere('payment_status', 'like', "%{$search}%")
                        ->orWhere('transaction_id', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('payment_id')
            ->paginate(10)
            ->withQueryString();

        return view('payments.report', compact('paymentDetails', 'search'));
    }
}