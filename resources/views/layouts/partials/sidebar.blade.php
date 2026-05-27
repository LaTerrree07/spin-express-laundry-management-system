@php
    $isAdmin = auth()->user()?->role === 'admin';
    $isStaff = auth()->user()?->role === 'staff';

    $reportsActive = request()->routeIs('reports.customer-transactions')
    || request()->routeIs('reports.service-category-records')
    || request()->routeIs('reports.transaction-extra-items');
@endphp

<aside
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-white/10 bg-[#0F213E] text-white lg:translate-x-0"
    style="display: none;"
>
    <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl p-2">
                <img
                    src="{{ asset('images/branding/logo.png') }}"
                    alt="Spin Express Logo"
                    class="h-full w-full object-contain"
                >
            </div>

            <div class="min-w-0">
                <h1 class="truncate text-sm font-semibold tracking-wide text-white">
                    Spin Express
                </h1>
                <p class="truncate text-xs text-[#C7D6EA]">
                    Laundry Management System
                </p>
            </div>
        </div>

        <button
            type="button"
            class="rounded-lg p-2 text-white transition hover:bg-white/10 lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Close menu"
        >
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
        >
            <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('customers.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
        >
            <i data-lucide="users" class="h-5 w-5"></i>
            <span>Customers</span>
        </a>

        <a
            href="{{ route('transactions.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('transactions.*') && !request()->routeIs('reports.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
        >
            <i data-lucide="receipt-text" class="h-5 w-5"></i>
            <span>Transactions</span>
        </a>

        <a
            href="{{ route('payments.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('payments.*') && !request()->routeIs('reports.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
        >
            <i data-lucide="wallet" class="h-5 w-5"></i>
            <span>Payments</span>
        </a>

        {{-- Reports dropdown --}}
       <div x-data="{ open: {{ $reportsActive ? 'true' : 'false' }} }" class="space-y-1">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-sm font-medium transition {{ $reportsActive ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
    >
        <span class="flex items-center gap-3">
            <i data-lucide="file-bar-chart-2" class="h-5 w-5"></i>
            <span>Reports</span>
        </span>

        <i
            data-lucide="chevron-down"
            class="h-4 w-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
        ></i>
    </button>

    <div x-show="open" x-collapse class="space-y-1 pl-4">
        <a
            href="{{ route('reports.customer-transactions') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('reports.customer-transactions') ? 'bg-[#1E4E8C] text-white' : 'text-[#C7D6EA] hover:bg-[#16325C] hover:text-white' }}"
        >
            <i data-lucide="users-round" class="h-4 w-4"></i>
            <span>Customer Transactions</span>
        </a>



        <a
            href="{{ route('reports.transaction-extra-items') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('reports.transaction-extra-items') ? 'bg-[#1E4E8C] text-white' : 'text-[#C7D6EA] hover:bg-[#16325C] hover:text-white' }}"
        >
            <i data-lucide="package-search" class="h-4 w-4"></i>
            <span>Transaction Extra Items</span>
        </a>


                <a
            href="{{ route('reports.service-category-records') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ request()->routeIs('reports.service-category-records') ? 'bg-[#1E4E8C] text-white' : 'text-[#C7D6EA] hover:bg-[#16325C] hover:text-white' }}"
        >
            <i data-lucide="layers-3" class="h-4 w-4"></i>
            <span>Service Category Records</span>
        </a>
        
    </div>
</div>

        @if ($isAdmin)
            <div class="my-3 border-t border-white/10"></div>

            <a
                href="{{ route('service-categories.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('service-categories.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
            >
                <i data-lucide="layers-3" class="h-5 w-5"></i>
                <span>Service Categories</span>
            </a>

            <a
                href="{{ route('service-types.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('service-types.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
            >
                <i data-lucide="washing-machine" class="h-5 w-5"></i>
                <span>Service Types</span>
            </a>

            <a
                href="{{ route('staff.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('staff.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
            >
                <i data-lucide="user-cog" class="h-5 w-5"></i>
                <span>Staff</span>
            </a>

            <a
                href="{{ route('statuses.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('statuses.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
            >
                <i data-lucide="badge-check" class="h-5 w-5"></i>
                <span>Statuses</span>
            </a>

            <a
                href="{{ route('extra-items.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('extra-items.*') ? 'bg-[#16325C] text-white' : 'text-[#C7D6EA] hover:bg-[#1E4E8C] hover:text-white' }}"
            >
                <i data-lucide="package-plus" class="h-5 w-5"></i>
                <span>Extra Items</span>
            </a>
        @endif
    </nav>

    <div x-data="{ openProfile: false }" class="border-t border-white/10 p-3">
        <button
            type="button"
            @click="openProfile = !openProfile"
            class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left transition hover:bg-white/10"
        >
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10">
                    <i data-lucide="circle-user-round" class="h-5 w-5"></i>
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs capitalize text-[#C7D6EA]">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <i data-lucide="chevrons-up-down" class="h-4 w-4 text-[#C7D6EA]"></i>
        </button>

        <div
            x-show="openProfile"
            x-transition
            class="mt-2 space-y-1 rounded-xl bg-white/5 p-2"
            style="display: none;"
        >
            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-[#C7D6EA] transition hover:bg-white/10 hover:text-white"
            >
                <i data-lucide="settings" class="h-4 w-4 shrink-0"></i>
                <span>Settings</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-[#C7D6EA] transition hover:bg-white/10 hover:text-white"
                >
                    <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>