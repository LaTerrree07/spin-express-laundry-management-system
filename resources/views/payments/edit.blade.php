<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#1E293B]">
            {{ $payment->isPaid() ? 'Correct Paid Payment' : 'Edit Payment' }}
        </h2>
    </x-slot>

    <div class="rounded-2xl border border-[#D6DEE8] bg-white p-6 shadow-sm">
        @if ($payment->isPaid())
            <div class="mb-6 rounded-2xl border border-[#FED7AA] bg-[#FFF7ED] px-4 py-3 text-sm text-[#9A3412]">
                This paid payment is restricted. Only limited admin correction is allowed.
            </div>
        @endif

        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PUT')
            @include('payments._form', ['buttonText' => $payment->isPaid() ? 'Save Correction' : 'Save Changes'])
        </form>
    </div>
</x-app-layout>