<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Add Payment</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            @include('payments._form', ['buttonText' => 'Save Payment'])
        </form>
    </div>
</x-app-layout>