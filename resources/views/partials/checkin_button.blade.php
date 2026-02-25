<!-- Check-in Button -->
<div class="flex flex-col items-center justify-center mt-4 mb-4">
    <form action="{{ route('checkins.store') }}" method="POST">
        @csrf
        @if ($userCheckin)
            <button type="submit"
                class="bg-red-500 disabled:opacity-40 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-2xl shadow-md transition duration-300 text-lg w-full max-w-xs">
                Fazer Check-in
            </button>
        @else
            <button type="submit" disabled
                class="bg-red-500 disabled:opacity-40 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-2xl shadow-md transition duration-300 text-lg w-full max-w-xs">
                Checkin Feito
            </button>
        @endif
    </form>
    <p class="text-sm text-red-600 mt-2">
        Você receberá <span class="font-bold">R$ 1,00</span> por
        check-in!
    </p>
</div>
