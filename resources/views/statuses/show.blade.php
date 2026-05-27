<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Status Details</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <dl class="grid grid-cols-1 gap-5">
            <div>
                <dt class="text-sm font-medium text-[#64748B]">Status Name</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $status->status_name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-[#64748B]">Description</dt>
                <dd class="mt-1 text-sm text-[#1E293B]">{{ $status->description ?: '—' }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex justify-end gap-3">
            <a
                href="{{ route('statuses.index') }}"
                class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('statuses.edit', $status) }}"
                class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                Edit Status
            </a>
        </div>
    </div>
</x-app-layout>