<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#1E293B]">Service Types</h2>

            <a
                href="{{ route('service-types.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Add Service Type</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('service-types.index') }}" class="flex flex-col gap-3 sm:flex-row">
                <div class="relative w-full">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search service type or category"
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
                        href="{{ route('service-types.index') }}"
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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">
                                Service Category
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">
                                Service Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">
                                Price
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">
                                Description
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-[#0F213E]">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6DEE8] bg-white">
                        @forelse ($serviceTypes as $serviceType)
                            <tr class="hover:bg-[#F8FBFF]">
                                <td class="px-4 py-4 text-sm text-[#64748B]">
                                    {{ $serviceType->serviceCategory->category_name }}
                                </td>

                                <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">
                                    {{ $serviceType->service_name }}
                                </td>

                                <td class="px-4 py-4 text-sm text-[#64748B]">
                                    ₱{{ number_format((float) $serviceType->price, 2) }}
                                </td>

                                <td class="px-4 py-4 text-sm text-[#64748B]">
                                    {{ $serviceType->description ?: '—' }}
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('service-types.show', $serviceType) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-[#D6DEE8] bg-white p-2 text-[#1E293B] transition hover:bg-slate-50"
                                            title="View"
                                            aria-label="View"
                                        >
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a
                                            href="{{ route('service-types.edit', $serviceType) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-[#EAF4FF] p-2 text-[#0F213E] transition hover:bg-[#D9ECFF]"
                                            title="Edit"
                                            aria-label="Edit"
                                        >
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <x-delete-confirm-modal
                                            :id="'delete-service-type-'.$serviceType->service_type_id"
                                            title="Delete Service Type"
                                            message="Are you sure you want to delete this service type? This action cannot be undone."
                                            :action="route('service-types.destroy', $serviceType)"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                    No service types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('layouts.partials.table-pagination', ['paginator' => $serviceTypes])
        </div>
    </div>
</x-app-layout>