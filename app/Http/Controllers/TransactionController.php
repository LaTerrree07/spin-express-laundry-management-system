<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Customer;
use App\Models\ExtraItem;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Status;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $statusFilter = $request->string('status')->toString();

        $transactions = Transaction::query()
            ->with(['customer', 'serviceType', 'staff', 'status'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('cf_name', 'like', "%{$search}%")
                            ->orWhere('cm_name', 'like', "%{$search}%")
                            ->orWhere('cl_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('serviceType', function ($serviceTypeQuery) use ($search) {
                        $serviceTypeQuery->where('service_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('status', function ($statusQuery) use ($search) {
                        $statusQuery->where('status_name', 'like', "%{$search}%");
                    });
                });
            })
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->whereHas('status', function ($statusQuery) use ($statusFilter) {
                    $statusQuery->whereRaw('LOWER(status_name) = ?', [strtolower($statusFilter)]);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summaryCounts = [
            'all' => Transaction::count(),
            'pending' => Transaction::whereHas('status', fn ($q) => $q->whereRaw('LOWER(status_name) = ?', ['pending']))->count(),
            'ongoing' => Transaction::whereHas('status', fn ($q) => $q->whereRaw('LOWER(status_name) = ?', ['ongoing']))->count(),
            'finished' => Transaction::whereHas('status', fn ($q) => $q->whereRaw('LOWER(status_name) = ?', ['finished']))->count(),
            'claimed' => Transaction::whereHas('status', fn ($q) => $q->whereRaw('LOWER(status_name) = ?', ['claimed']))->count(),
        ];

        return view('transactions.index', compact('transactions', 'search', 'statusFilter', 'summaryCounts'));
    }

    public function create(): View
    {
        return view('transactions.create', $this->formData());
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        try {
            $this->transactionService->store($request->validated());

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction added successfully.');
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

    public function show(Transaction $transaction): View
    {
        $transaction->load([
            'customer',
            'serviceType',
            'staff',
            'status',
            'transactionExtraItems.extraItem',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction): View
    {
        $transaction->load('transactionExtraItems');

        return view('transactions.edit', array_merge(
            $this->formData(),
            compact('transaction')
        ));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        try {
            $this->transactionService->update($transaction, $request->validated());

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction updated successfully.');
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

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    

  public function extraItemsReport(Request $request): View
{
    $search = $request->string('search')->toString();

    $extraItemDetails = DB::table('vw_transaction_extra_items')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('transaction_extra_item_id')
        ->paginate(10)
        ->withQueryString();

    return view('reports.transaction-extra-items', compact('extraItemDetails', 'search'));
}
    protected function formData(): array
    {
        return [
            'customers' => Customer::orderBy('cf_name')->get(),
            'serviceTypes' => ServiceType::with('serviceCategory')->orderBy('service_name')->get(),
            'staffMembers' => Staff::orderBy('sf_name')->get(),
            'statuses' => Status::orderBy('status_name')->get(),
            'extraItems' => ExtraItem::orderBy('item_name')->get(),
        ];
    }

public function customerTransactionsReport(Request $request): View
{
    $search = $request->string('search')->toString();

    $customerTransactions = DB::table('vw_customer_transaction_records')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere('service_name', 'like', "%{$search}%")
                    ->orWhere('status_name', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('transaction_id')
        ->paginate(10)
        ->withQueryString();

    return view('reports.customer-transactions', compact('customerTransactions', 'search'));
}

public function serviceCategoryRecordsReport(Request $request): View
{
    $search = $request->string('search')->toString();

    
    $serviceCategoryRecords = DB::table('vw_service_category_records')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                    ->orWhere('service_name', 'like', "%{$search}%")
                    ->orWhere('category_description', 'like', "%{$search}%")
                    ->orWhere('service_description', 'like', "%{$search}%");
            });
        })
        ->orderBy('category_name')
        ->orderBy('service_name')
        ->paginate(10)
        ->withQueryString();

    return view('reports.service-category-records', compact('serviceCategoryRecords', 'search'));
}
}