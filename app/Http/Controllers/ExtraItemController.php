<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExtraItemRequest;
use App\Http\Requests\UpdateExtraItemRequest;
use App\Models\ExtraItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExtraItemController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $extraItems = ExtraItem::query()
            ->when($search, function ($query, $search) {
                $query->where('item_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('extra-items.index', compact('extraItems', 'search'));
    }

    public function create(): View
    {
        return view('extra-items.create');
    }

    public function store(StoreExtraItemRequest $request): RedirectResponse
    {
        ExtraItem::create($request->validated());

        return redirect()
            ->route('extra-items.index')
            ->with('success', 'Extra item added successfully.');
    }

    public function show(ExtraItem $extraItem): View
    {
        return view('extra-items.show', compact('extraItem'));
    }

    public function edit(ExtraItem $extraItem): View
    {
        return view('extra-items.edit', compact('extraItem'));
    }

    public function update(UpdateExtraItemRequest $request, ExtraItem $extraItem): RedirectResponse
    {
        $extraItem->update($request->validated());

        return redirect()
            ->route('extra-items.index')
            ->with('success', 'Extra item updated successfully.');
    }

    public function destroy(ExtraItem $extraItem): RedirectResponse
    {
        if ($extraItem->transactionExtraItems()->exists()) {
            return redirect()
                ->route('extra-items.index')
                ->with('error', 'Extra item cannot be deleted because it is already used in transactions.');
        }

        $extraItem->delete();

        return redirect()
            ->route('extra-items.index')
            ->with('success', 'Extra item deleted successfully.');
    }
}