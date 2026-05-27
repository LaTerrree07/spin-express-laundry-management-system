<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Add Customer</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf

            @include('customers._form', ['buttonText' => 'Save Customer'])
        </form>
    </div>
</x-app-layout>
