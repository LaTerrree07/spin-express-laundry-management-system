<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $staffMembers = Staff::query()
            ->when($search, function ($query, $search) {
                $query->where('sf_name', 'like', "%{$search}%")
                    ->orWhere('sm_name', 'like', "%{$search}%")
                    ->orWhere('sl_name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('staff.index', compact('staffMembers', 'search'));
    }

    public function create(): View
    {
        return view('staff.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        Staff::create($request->validated());

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff record added successfully.');
    }

    public function show(Staff $staff): View
    {
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff): View
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update($request->validated());

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff record updated successfully.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        if ($staff->transactions()->exists()) {
            return redirect()
                ->route('staff.index')
                ->with('error', 'Staff record cannot be deleted because it is already used by transactions.');
        }

        $staff->delete();

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff record deleted successfully.');
    }
}