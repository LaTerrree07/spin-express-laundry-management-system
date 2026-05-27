@props([
    'id',
    'title' => 'Delete Record',
    'message' => 'Are you sure you want to delete this record?',
    'action',
])

<div x-data="{ open: false }" class="inline-block">
    <button
        type="button"
        @click="open = true"
        class="inline-flex items-center justify-center rounded-xl bg-red-50 p-2 text-red-600 transition hover:bg-red-100"
        title="Delete"
        aria-label="Delete"
    >
        <i data-lucide="trash-2" class="h-4 w-4"></i>
    </button>

    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/50 px-4"
        style="display: none;"
    >
        <div
            x-show="open"
            x-transition
            class="w-full max-w-md rounded-3xl border border-red-100 bg-white p-6 shadow-2xl"
            @click.away="open = false"
        >
            <div class="flex flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i data-lucide="triangle-alert" class="h-8 w-8"></i>
                </div>

                <h3 class="mt-4 text-lg font-semibold text-[#1E293B]">{{ $title }}</h3>
                <p class="mt-2 text-sm text-[#64748B]">{{ $message }}</p>

                <div class="mt-6 flex w-full justify-center gap-3">
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
                    >
                        Cancel
                    </button>

                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>