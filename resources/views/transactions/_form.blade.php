@php
    $currentTransaction = $transaction ?? null;

    $previewExtraItems = $extraItems->values()->map(function ($extraItem, $index) use ($currentTransaction) {
        $quantity = old(
            "extra_items.$index.quantity",
            $currentTransaction
                ? optional($currentTransaction->transactionExtraItems->firstWhere('extra_item_id', $extraItem->extra_item_id))->quantity
                : 0
        );

        return [
            'id' => $extraItem->extra_item_id,
            'name' => $extraItem->item_name,
            'price' => (float) $extraItem->price,
            'quantity' => (int) $quantity,
        ];
    })->values();
@endphp

<div
    x-data="transactionForm()"
    x-init="init()"
    class="space-y-8"
>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div x-data="customerAutocomplete()" class="lg:col-span-2">
            <label for="customer_search" class="mb-2 block text-sm font-medium text-[#1E293B]">Customer</label>

            <div class="flex gap-2">
                <div class="w-full">
                    <input
                        type="text"
                        id="customer_search"
                        list="customer-options"
                        x-model="search"
                        @input="syncCustomerId()"
                        @change="syncCustomerId()"
                        placeholder="Type customer name to search existing records"
                        class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] placeholder:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                        required
                    >

                    <datalist id="customer-options">
                        @foreach ($customers as $customer)
                            <option
                                value="{{ $customer->full_name }}"
                                data-id="{{ $customer->customer_id }}"
                            >
                                {{ $customer->contact_number }}
                            </option>
                        @endforeach
                    </datalist>

                    <input type="hidden" name="customer_id" x-model="customerId">

                    @error('customer_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-[#64748B]">
                        Start typing the customer’s name to select an existing record. If the customer is not yet in the system, use the add button.
                    </p>
                </div>

                <a
                    href="{{ route('customers.create') }}"
                    class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-[#0F213E] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                    title="Add Customer"
                >
                    <i data-lucide="plus" class="h-4 w-4"></i>
                </a>
            </div>
        </div>

        <div>
            <label for="service_type_id" class="mb-2 block text-sm font-medium text-[#1E293B]">Service Type</label>
            <select
                name="service_type_id"
                id="service_type_id"
                x-model="serviceTypeId"
                @change="syncServiceType()"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                required
            >
                <option value="">Select service type</option>
                @foreach ($serviceTypes as $serviceType)
                    <option
                        value="{{ $serviceType->service_type_id }}"
                        data-name="{{ $serviceType->service_name }}"
                        data-price="{{ (float) $serviceType->price }}"
                        @selected(old('service_type_id', $currentTransaction->service_type_id ?? '') == $serviceType->service_type_id)
                    >
                        {{ $serviceType->service_name }} — {{ $serviceType->serviceCategory->category_name }} — ₱{{ number_format((float) $serviceType->price, 2) }}
                    </option>
                @endforeach
            </select>
            @error('service_type_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-[#64748B]">
                Select the actual service offered. Its price will be used as the base amount in transaction computation.
            </p>
        </div>

        <div>
            <label for="staff_id" class="mb-2 block text-sm font-medium text-[#1E293B]">Monitored By Staff</label>
            <select
                name="staff_id"
                id="staff_id"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                required
            >
                <option value="">Select staff</option>
                @foreach ($staffMembers as $staff)
                    <option value="{{ $staff->staff_id }}" @selected(old('staff_id', $currentTransaction->staff_id ?? '') == $staff->staff_id)>
                        {{ $staff->full_name }}
                    </option>
                @endforeach
            </select>
            @error('staff_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status_id" class="mb-2 block text-sm font-medium text-[#1E293B]">Status</label>
            <select
                name="status_id"
                id="status_id"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                required
            >
                <option value="">Select status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->status_id }}" @selected(old('status_id', $currentTransaction->status_id ?? '') == $status->status_id)>
                        {{ $status->status_name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-[#64748B]">
                Use the operational status of the laundry order, such as Pending, Ongoing, Finished, or Claimed.
            </p>
        </div>

        <div>
            <label for="weight_kg" class="mb-2 block text-sm font-medium text-[#1E293B]">Weight (kg)</label>
            <input
                type="number"
                name="weight_kg"
                id="weight_kg"
                step="0.01"
                min="0"
                x-model.number="weightKg"
                @input="syncComputedLoads()"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            >
            @error('weight_kg')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-[#64748B]">
                Required for wash-based services. The live preview automatically computes loads using the 6 kg maximum per load rule.
            </p>
        </div>

        <div>
            <label for="number_of_loads" class="mb-2 block text-sm font-medium text-[#1E293B]">Number of Loads</label>
            <input
                type="number"
                name="number_of_loads"
                id="number_of_loads"
                min="1"
                x-model.number="numberOfLoads"
                @input="syncComputedLoads()"
                :readonly="isWashBased"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                :class="isWashBased ? 'bg-slate-50 text-slate-500' : ''"
            >
            @error('number_of_loads')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

           

           
        </div>

        <div class="lg:col-span-2">
            <label for="remarks" class="mb-2 block text-sm font-medium text-[#1E293B]">Remarks</label>
            <textarea
                name="remarks"
                id="remarks"
                rows="4"
                class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
            >{{ old('remarks', $currentTransaction->remarks ?? '') }}</textarea>
            @error('remarks')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] p-5 xl:col-span-2">
            <h3 class="text-sm font-semibold text-[#1E293B]">Extra Items</h3>
            <p class="mt-1 text-xs text-[#64748B]">
                Add quantity only for optional extra items you want to include. Their subtotals are previewed live before saving.
            </p>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($extraItems as $index => $extraItem)
                    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-4">
                        <input
                            type="hidden"
                            name="extra_items[{{ $index }}][extra_item_id]"
                            value="{{ $extraItem->extra_item_id }}"
                        >

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-semibold text-[#1E293B]">{{ $extraItem->item_name }}</h4>
                                <p class="mt-1 text-xs text-[#64748B]">{{ $extraItem->description ?: 'No description' }}</p>
                            </div>

                            <span class="text-sm font-semibold text-[#0F213E]">
                                ₱{{ number_format((float) $extraItem->price, 2) }}
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-[#1E293B]">Quantity</label>
                                <input
                                    type="number"
                                    min="0"
                                    name="extra_items[{{ $index }}][quantity]"
                                    x-model.number="extraItems[{{ $index }}].quantity"
                                    class="w-full rounded-2xl border border-[#D6DEE8] px-4 py-3 text-sm text-[#1E293B] focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-[#1E293B]">Subtotal</label>
                                <div class="flex min-h-[46px] items-center rounded-2xl border border-[#D6DEE8] bg-[#F8FBFF] px-4 py-3 text-sm font-semibold text-[#0F213E]">
                                    <span x-text="formatCurrency(extraItems[{{ $index }}].price * normalizedQuantity(extraItems[{{ $index }}].quantity))"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-[#BFDBFE] bg-white p-5 shadow-sm xl:sticky xl:top-6 xl:self-start">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#EAF4FF] text-[#0F213E]">
                    <i data-lucide="calculator" class="h-5 w-5"></i>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-[#1E293B]">Amount Preview</h3>
                    <p class="text-xs text-[#64748B]">Live estimate before saving</p>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between rounded-xl border border-[#D6DEE8] bg-[#F8FBFF] px-4 py-3">
                    <span class="text-sm text-[#64748B]">Base Service Total</span>
                    <span class="text-sm font-semibold text-[#1E293B]" x-text="formatCurrency(baseServiceTotal)"></span>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-[#D6DEE8] bg-[#F8FBFF] px-4 py-3">
                    <span class="text-sm text-[#64748B]">Extra Items Total</span>
                    <span class="text-sm font-semibold text-[#1E293B]" x-text="formatCurrency(extraItemsTotal)"></span>
                </div>

                <div class="rounded-2xl border border-[#BFDBFE] bg-[#EAF4FF] px-4 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-semibold text-[#0F213E]">Grand Total</span>
                        <span class="text-2xl font-bold text-[#0F213E]" x-text="formatCurrency(grandTotal)"></span>
                    </div>
                </div>

                <div class="rounded-xl bg-[#F8FBFF] px-3 py-3 text-xs leading-relaxed text-[#64748B]">
                    <p>
                        Computed loads:
                        <span class="font-semibold text-[#0F213E]" x-text="computedLoads"></span>
                    </p>
                   
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('transactions.index') }}"
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
</div>

<script>
    function customerAutocomplete() {
        return {
            search: @json(
                old(
                    'customer_search',
                    isset($currentTransaction) && $currentTransaction && isset($currentTransaction->customer)
                        ? $currentTransaction->customer->full_name
                        : (
                            isset($customers)
                                ? optional($customers->firstWhere('customer_id', old('customer_id')))->full_name
                                : ''
                        )
                )
            ),
            customerId: @json(old('customer_id', $currentTransaction->customer_id ?? '')),

            syncCustomerId() {
                const options = document.querySelectorAll('#customer-options option');
                let matchedId = '';

                options.forEach(option => {
                    if (option.value.trim().toLowerCase() === String(this.search).trim().toLowerCase()) {
                        matchedId = option.dataset.id;
                    }
                });

                this.customerId = matchedId;
            }
        }
    }

    function transactionForm() {
        return {
            serviceTypeId: @json(old('service_type_id', $currentTransaction->service_type_id ?? '')),
            serviceName: '',
            servicePrice: 0,
            weightKg: Number(@json(old('weight_kg', $currentTransaction->weight_kg ?? 0))) || 0,
            numberOfLoads: Number(@json(old('number_of_loads', $currentTransaction->number_of_loads ?? 1))) || 1,

          extraItems: @json($previewExtraItems),

            init() {
                this.syncServiceType();
                this.syncComputedLoads();
            },

            syncServiceType() {
                const select = document.getElementById('service_type_id');
                const option = select.options[select.selectedIndex];

                this.serviceName = option?.dataset?.name || '';
                this.servicePrice = Number(option?.dataset?.price || 0);

                this.syncComputedLoads();
            },

            syncComputedLoads() {
                if (this.isWashBased) {
                    this.numberOfLoads = this.weightKg > 0 ? Math.ceil(this.weightKg / 6) : 0;
                    return;
                }

                if (!this.numberOfLoads || this.numberOfLoads < 1) {
                    this.numberOfLoads = 1;
                }
            },

            normalizedQuantity(quantity) {
                const value = Number(quantity || 0);
                return value > 0 ? value : 0;
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                }).format(Number(value || 0));
            },

            get isWashBased() {
                return String(this.serviceName || '').toLowerCase().includes('wash');
            },

            get computedLoads() {
                const loads = Number(this.numberOfLoads || 0);
                return loads > 0 ? loads : 0;
            },

            get baseServiceTotal() {
                return this.servicePrice * this.computedLoads;
            },

            get extraItemsTotal() {
                return this.extraItems.reduce((total, item) => {
                    return total + (Number(item.price || 0) * this.normalizedQuantity(item.quantity));
                }, 0);
            },

            get grandTotal() {
                return this.baseServiceTotal + this.extraItemsTotal;
            },
        }
    }
</script>