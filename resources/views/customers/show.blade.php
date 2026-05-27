<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Customer Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">First Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $customer->cf_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Middle Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $customer->cm_name ?: '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Last Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $customer->cl_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Contact Number</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $customer->contact_number }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('customers.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('customers.edit', $customer) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#16325C]"
            >
                Edit Customer
            </a>
        </div>
    </div>
</x-app-layout>