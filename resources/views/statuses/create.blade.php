<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Add Status</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('statuses.store') }}">
            @csrf

            @include('statuses._form', ['buttonText' => 'Save Status'])
        </form>
    </div>
</x-app-layout>