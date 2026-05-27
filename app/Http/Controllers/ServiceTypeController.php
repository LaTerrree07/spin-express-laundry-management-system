<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceTypeRequest;
use App\Http\Requests\UpdateServiceTypeRequest;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $serviceTypes = ServiceType::query()
            ->with('serviceCategory')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('service_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('serviceCategory', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('category_name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('service-types.index', compact('serviceTypes', 'search'));
    }

    public function create(): View
    {
        $serviceCategories = ServiceCategory::orderBy('category_name')->get();

        return view('service-types.create', compact('serviceCategories'));
    }

    public function store(StoreServiceTypeRequest $request): RedirectResponse
    {
        ServiceType::create($request->validated());

        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service type added successfully.');
    }

    public function show(ServiceType $serviceType): View
    {
        $serviceType->load('serviceCategory');

        return view('service-types.show', compact('serviceType'));
    }

    public function edit(ServiceType $serviceType): View
    {
        $serviceCategories = ServiceCategory::orderBy('category_name')->get();

        return view('service-types.edit', compact('serviceType', 'serviceCategories'));
    }

    public function update(UpdateServiceTypeRequest $request, ServiceType $serviceType): RedirectResponse
    {
        $serviceType->update($request->validated());

        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        if ($serviceType->transactions()->exists()) {
            return redirect()
                ->route('service-types.index')
                ->with('error', 'Service type cannot be deleted because it is already used by transactions.');
        }

        $serviceType->delete();

        return redirect()
            ->route('service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }
}