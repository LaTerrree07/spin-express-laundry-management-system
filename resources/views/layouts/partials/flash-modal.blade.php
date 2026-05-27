@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/40 px-4"
        style="display: none;"
    >
        <div
            x-transition
            class="w-full max-w-md rounded-3xl border border-green-200 bg-white p-6 shadow-2xl"
        >
            <div class="flex flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <i data-lucide="circle-check-big" class="h-8 w-8"></i>
                </div>

                <h3 class="mt-4 text-lg font-semibold text-[#1E293B]">Success</h3>
                <p class="mt-2 text-sm text-[#64748B]">
                    {{ session('success') }}
                </p>

                <button
                    type="button"
                    @click="show = false"
                    class="mt-6 inline-flex items-center justify-center rounded-2xl bg-[#0F213E] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/40 px-4"
        style="display: none;"
    >
        <div
            x-transition
            class="w-full max-w-md rounded-3xl border border-red-200 bg-white p-6 shadow-2xl"
        >
            <div class="flex flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i data-lucide="triangle-alert" class="h-8 w-8"></i>
                </div>

                <h3 class="mt-4 text-lg font-semibold text-[#1E293B]">Notice</h3>
                <p class="mt-2 text-sm text-[#64748B]">
                    {{ session('error') }}
                </p>

                <button
                    type="button"
                    @click="show = false"
                    class="mt-6 inline-flex items-center justify-center rounded-2xl bg-[#0F213E] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
@endif