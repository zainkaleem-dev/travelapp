<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm text-center">

        @if (session()->has('success'))
            <div class="mb-4 text-green-600 bg-green-50 p-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Booking Confirmed!</h1>
        <p class="text-gray-500 mb-8">Thank you for booking with FlightBook. Your reservation order is successfully
            created in Amadeus.</p>

        <div class="bg-gray-50 rounded-xl p-6 mb-8 max-w-md mx-auto border border-gray-200">
            <div class="grid grid-cols-2 gap-4 text-left">
                <div>
                    <span class="block text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">PNR /
                        Reference</span>
                    <span class="block text-xl font-bold text-indigo-700">{{ $bookingReference }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Amadeus Order
                        ID</span>
                    <span class="block text-xl font-bold text-gray-800">{{ $bookingId }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('flights.search') }}"
            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-xl transition-colors">
            Book Another Flight
        </a>
    </div>
</div>