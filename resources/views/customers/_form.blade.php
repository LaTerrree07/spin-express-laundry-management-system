<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="cf_name" class="mb-2 block text-sm font-medium text-[#1E293B]">First Name</label>
        <input
            type="text"
            name="cf_name"
            id="cf_name"
            value="{{ old('cf_name', $customer->cf_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('cf_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cm_name" class="mb-2 block text-sm font-medium text-[#1E293B]">Middle Name</label>
        <input
            type="text"
            name="cm_name"
            id="cm_name"
            value="{{ old('cm_name', $customer->cm_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >
        @error('cm_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="cl_name" class="mb-2 block text-sm font-medium text-[#1E293B]">Last Name</label>
        <input
            type="text"
            name="cl_name"
            id="cl_name"
            value="{{ old('cl_name', $customer->cl_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('cl_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="contact_number" class="mb-2 block text-sm font-medium text-[#1E293B]">Contact Number</label>
        <input
            type="text"
            name="contact_number"
            id="contact_number"
            value="{{ old('contact_number', $customer->contact_number ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('contact_number')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a
        href="{{ route('customers.index') }}"
        class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] hover:bg-slate-50"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#16325C]"
    >
        {{ $buttonText }}
    </button>
</div>