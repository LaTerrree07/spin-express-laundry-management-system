<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#1E293B]">Staff</h2>

            <a
                href="{{ route('staff.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
            >
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Add Staff</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('staff.index') }}" class="flex flex-col gap-3 sm:flex-row">
                <div class="relative w-full">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search staff name, contact number, or address"
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
                        href="{{ route('staff.index') }}"
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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Age</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Contact Number</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Address</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-[#0F213E]">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6DEE8] bg-white">
                        @forelse ($staffMembers as $staff)
                            <tr class="hover:bg-[#F8FBFF]">
                                <td class="px-4 py-4 text-sm font-medium text-[#1E293B]">{{ $staff->full_name }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->age }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->contact_number }}</td>
                                <td class="px-4 py-4 text-sm text-[#64748B]">{{ $staff->address }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('staff.show', $staff) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-[#D6DEE8] bg-white p-2 text-[#1E293B] transition hover:bg-slate-50"
                                            title="View"
                                            aria-label="View"
                                        >
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a
                                            href="{{ route('staff.edit', $staff) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-[#EAF4FF] p-2 text-[#0F213E] transition hover:bg-[#D9ECFF]"
                                            title="Edit"
                                            aria-label="Edit"
                                        >
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <x-delete-confirm-modal
                                            :id="'delete-staff-'.$staff->id"
                                            title="Delete Staff"
                                            message="Are you sure you want to delete this staff record? This action cannot be undone."
                                            :action="route('staff.destroy', $staff)"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-[#64748B]">
                                    No staff records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('layouts.partials.table-pagination', ['paginator' => $staffMembers])
        </div>
    </div>
</x-app-layout>