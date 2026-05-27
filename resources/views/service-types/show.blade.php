<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Service Type Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">Service Category</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $serviceType->serviceCategory->category_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Service Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $serviceType->service_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Price</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">₱{{ number_format((float) $serviceType->price, 2) }}</dd>
            </div>

            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-[#64748B]">Description</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $serviceType->description ?: '—' }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('service-types.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('service-types.edit', $serviceType) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Service Type
            </a>
        </div>
    </div>
</x-app-layout>