<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $statuses = Status::query()
            ->when($search, function ($query, $search) {
                $query->where('status_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('statuses.index', compact('statuses', 'search'));
    }

    public function create(): View
    {
        return view('statuses.create');
    }

    public function store(StoreStatusRequest $request): RedirectResponse
    {
        Status::create($request->validated());

        return redirect()
            ->route('statuses.index')
            ->with('success', 'Status added successfully.');
    }

    public function show(Status $status): View
    {
        return view('statuses.show', compact('status'));
    }

    public function edit(Status $status): View
    {
        return view('statuses.edit', compact('status'));
    }

    public function update(UpdateStatusRequest $request, Status $status): RedirectResponse
    {
        $status->update($request->validated());

        return redirect()
            ->route('statuses.index')
            ->with('success', 'Status updated successfully.');
    }

    public function destroy(Status $status): RedirectResponse
    {
        if ($status->transactions()->exists()) {
            return redirect()
                ->route('statuses.index')
                ->with('error', 'Status cannot be deleted because it is already used by transactions.');
        }

        $status->delete();

        return redirect()
            ->route('statuses.index')
            ->with('success', 'Status deleted successfully.');
    }
}