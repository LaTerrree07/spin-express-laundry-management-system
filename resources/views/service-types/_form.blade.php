<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="service_category_id" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Service Category
        </label>
        <select
            name="service_category_id"
            id="service_category_id"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
            <option value="">Select a category</option>
            @foreach ($serviceCategories as $serviceCategory)
                <option
                    value="{{ $serviceCategory->service_category_id }}"
                    @selected(old('service_category_id', $serviceType->service_category_id ?? '') == $serviceCategory->service_category_id)
                >
                    {{ $serviceCategory->category_name }}
                </option>
            @endforeach
        </select>
        @error('service_category_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="service_name" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Service Name
        </label>
        <input
            type="text"
            name="service_name"
            id="service_name"
            value="{{ old('service_name', $serviceType->service_name ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('service_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            Enter the actual service offered under the selected category, such as Wash, Dry, or Wash and Dry.
        </p>
    </div>

    <div>
        <label for="price" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Price
        </label>
        <input
            type="number"
            name="price"
            id="price"
            step="0.01"
            min="0.01"
            value="{{ old('price', $serviceType->price ?? '') }}"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            required
        >
        @error('price')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            This price will be used as the base amount in transaction computation.
        </p>
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block text-sm font-medium text-[#1E293B]">
            Description
        </label>
        <textarea
            name="description"
            id="description"
            rows="4"
            class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
        >{{ old('description', $serviceType->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-[#64748B]">
            Optional notes to clarify what is included in this service type.
        </p>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a
        href="{{ route('service-types.index') }}"
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