<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Staff Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">Full Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $staff->full_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Date of Birth</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $staff->date_of_birth?->format('F d, Y') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Age</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $staff->age }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Contact Number</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $staff->contact_number }}</dd>
            </div>

            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-[#64748B]">Address</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $staff->address }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('staff.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('staff.edit', $staff) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Staff
            </a>
        </div>
    </div>
</x-app-layout>