<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Spin Laundry Management System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F6F8FB] text-[#1E293B] antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        {{-- Mobile topbar --}}
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-[#D6DEE8] bg-white px-4 py-3 lg:hidden">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-[#D6DEE8] bg-white p-2 shadow-sm">
                    <img
                        src="{{ asset('images/branding/logo.png') }}"
                        alt="Spin Express Logo"
                        class="h-full w-full object-contain"
                    >
                </div>

                <div>
                    <h1 class="text-sm font-semibold text-[#0F213E]">Spin Express</h1>
                    <p class="text-xs text-slate-500">Laundry Management System</p>
                </div>
            </div>

            <button
                type="button"
                @click="sidebarOpen = true"
                class="inline-flex items-center justify-center rounded-xl border border-[#D6DEE8] bg-white p-2 text-[#0F213E] shadow-sm hover:bg-[#EAF4FF]"
                aria-label="Open menu"
            >
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>
        </div>

        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            @include('layouts.partials.sidebar')

            {{-- Main content --}}
            <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
                @isset($header)
                    <header class="border-b border-[#D6DEE8] bg-white">
                        <div class="px-4 py-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @include('layouts.partials.flash-modal')

                    @if (session('error'))
                        <div class="mb-4 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
                            <i data-lucide="triangle-alert" class="h-5 w-5"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>