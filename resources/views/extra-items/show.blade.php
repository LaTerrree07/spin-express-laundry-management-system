<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Extra Item Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">Item Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $extraItem->item_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Price</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">₱{{ number_format((float) $extraItem->price, 2) }}</dd>
            </div>

            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-[#64748B]">Description</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $extraItem->description ?: '—' }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('extra-items.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('extra-items.edit', $extraItem) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Extra Item
            </a>
        </div>
    </div>
</x-app-layout>