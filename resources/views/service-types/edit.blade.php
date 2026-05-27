<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">Edit Service Type</h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('service-types.update', $serviceType) }}">
            @csrf
            @method('PUT')

            @include('service-types._form', ['buttonText' => 'Save Changes'])
        </form>
    </div>
</x-app-layout>