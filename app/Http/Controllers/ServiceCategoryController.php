<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $serviceCategories = ServiceCategory::query()
            ->when($search, function ($query, $search) {
                $query->where('category_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('service-categories.index', compact('serviceCategories', 'search'));
    }

    public function create(): View
    {
        return view('service-categories.create');
    }

    public function store(StoreServiceCategoryRequest $request): RedirectResponse
    {
        ServiceCategory::create($request->validated());

        return redirect()
            ->route('service-categories.index')
            ->with('success', 'Service category added successfully.');
    }

    public function show(ServiceCategory $serviceCategory): View
    {
        return view('service-categories.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('service-categories.edit', compact('serviceCategory'));
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update($request->validated());

        return redirect()
            ->route('service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        if ($serviceCategory->serviceTypes()->exists()) {
            return redirect()
                ->route('service-categories.index')
                ->with('error', 'Service category cannot be deleted because it is already used by service types.');
        }

        $serviceCategory->delete();

        return redirect()
            ->route('service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }
}