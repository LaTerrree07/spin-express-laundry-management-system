<x-guest-layout>
    <div class="min-h-screen bg-[#F6F8FB] lg:grid lg:grid-cols-2">
        {{-- Visual side --}}
        <div class="relative hidden lg:flex">
            <img
                src="https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?auto=format&fit=crop&w=1400&q=80"
                alt="Laundry shop workspace"
                class="h-full w-full object-cover"
            >

            <div class="absolute inset-0 bg-[#0F213E]/75"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white">
                <div class="max-w-md">
                    <p class="mb-3 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium tracking-wide">
                        Spin Express
                    </p>

                    <h1 class="text-4xl font-bold leading-tight">
                        Laundry Management System
                    </h1>

                    <p class="mt-4 text-sm leading-6 text-[#DCE7F7]">
                        Organized transaction recording, payment tracking, status monitoring,
                        and cleaner daily operations for Spin Express Laundry Shop.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form side --}}
        <div class="flex items-center justify-center px-4 py-10 sm:px-6 lg:px-10">
            <div class="w-full max-w-md rounded-3xl border border-[#D6DEE8] bg-white p-8 shadow-xl sm:p-10">
                <div class="mb-8">
                    <div class="mb-6 flex items-center gap-4">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#D6DEE8] bg-white p-2 shadow-sm">
        <img
            src="{{ asset('images/branding/logo.png') }}"
            alt="Spin Express Logo"
            class="h-full w-full object-contain"
        >
    </div>

    <div>
        <p class="text-sm font-semibold text-[#0F213E]">Spin Express</p>
        <p class="text-xs text-[#64748B]">Laundry Management System</p>
    </div>
</div>
                    <h2 class="text-3xl font-bold text-[#1E293B]">Welcome back</h2>
                    <p class="mt-2 text-sm text-[#64748B]">
                        Sign in to continue to the Spin Express system.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="mb-2 text-sm font-medium text-[#1E293B]" />
                        <x-text-input
                            id="email"
                            class="block w-full rounded-2xl border-[#D6DEE8] bg-white px-4 py-3 text-sm text-[#1E293B] placeholder:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-[#1E293B]" />

                            @if (Route::has('password.request'))
                                <a class="text-xs font-medium text-[#1E4E8C] hover:text-[#16325C]" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <x-text-input
                            id="password"
                            class="block w-full rounded-2xl border-[#D6DEE8] bg-white px-4 py-3 text-sm text-[#1E293B] placeholder:text-slate-400 focus:border-[#1E4E8C] focus:ring-[#1E4E8C]"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label for="remember_me" class="flex items-center gap-3">
                        <input id="remember_me" type="checkbox" class="rounded border-[#D6DEE8] text-[#0F213E] focus:ring-[#1E4E8C]" name="remember">
                        <span class="text-sm text-[#64748B]">Remember me</span>
                    </label>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-[#0F213E] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#16325C]"
                    >
                        Log in
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>