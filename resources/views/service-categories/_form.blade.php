<div class="grid grid-cols-1 gap-5">
    <div>
        <label for="category_name" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Category Name
        </label>
        <input
            type="text"
            name="category_name"
            id="category_name"
            value="{{ old('category_name', $serviceCategory->category_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('category_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Description
        </label>
        <textarea
            name="description"
            id="description"
            rows="4"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >{{ old('description', $serviceCategory->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a
        href="{{ route('service-categories.index') }}"
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