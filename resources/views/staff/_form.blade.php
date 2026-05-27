<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="sf_name" class="mb-2 block text-sm font-medium text-[#1E293B]">First Name</label>
        <input
            type="text"
            name="sf_name"
            id="sf_name"
            value="{{ old('sf_name', $staff->sf_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('sf_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sm_name" class="mb-2 block text-sm font-medium text-[#1E293B]">Middle Name</label>
        <input
            type="text"
            name="sm_name"
            id="sm_name"
            value="{{ old('sm_name', $staff->sm_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >
        @error('sm_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sl_name" class="mb-2 block text-sm font-medium text-[#1E293B]">Last Name</label>
        <input
            type="text"
            name="sl_name"
            id="sl_name"
            value="{{ old('sl_name', $staff->sl_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('sl_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="date_of_birth" class="mb-2 block text-sm font-medium text-[#1E293B]">Date of Birth</label>
        <input
            type="date"
            name="date_of_birth"
            id="date_of_birth"
            value="{{ old('date_of_birth', isset($staff) && $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('date_of_birth')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="age" class="mb-2 block text-sm font-medium text-[#1E293B]">Age</label>
        <input
            type="number"
            name="age"
            id="age"
            min="1"
            value="{{ old('age', $staff->age ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('age')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="contact_number" class="mb-2 block text-sm font-medium text-[#1E293B]">Contact Number</label>
        <input
            type="text"
            name="contact_number"
            id="contact_number"
            value="{{ old('contact_number', $staff->contact_number ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('contact_number')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="address" class="mb-2 block text-sm font-medium text-[#1E293B]">Address</label>
        <textarea
            name="address"
            id="address"
            rows="4"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >{{ old('address', $staff->address ?? '') }}</textarea>
        @error('address')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a
        href="{{ route('staff.index') }}"
        class="rounded-2xl border border-[#D6DEE8] bg-white px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-slate-50"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="rounded-2xl bg-[#0F213E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#16325C]"
    >
        {{ $buttonText }}
    </button>
</div>