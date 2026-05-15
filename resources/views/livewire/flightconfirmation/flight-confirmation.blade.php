<div class="max-w-7xl mx-auto px-4 py-8 pb-16">

    {{-- ── Success icon (teal) ── --}}
    <div class="flex justify-center mb-8">
        <div class="relative">
            <div class="absolute inset-0 bg-[#2ab4c0] rounded-full blur-xl opacity-25"></div>
            <div class="relative w-24 h-24 bg-[#2ab4c0] rounded-full flex items-center justify-center shadow-xl shadow-[#2ab4c0]/30">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- ── Main message ── --}}
    <div class="text-center mb-10">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2 tracking-tight">Booking Confirmed!</h1>
        <p class="text-gray-500 text-sm sm:text-base max-w-lg mx-auto leading-relaxed">
            Your flight has been successfully booked. A confirmation email has been sent to the contact address provided. You're all set for your journey.
        </p>
    </div>

    {{-- ── Ticket card (match site cards) ── --}}
    <div class="w-full max-w-2xl mx-auto bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-200/40 overflow-hidden">

        {{-- Teal top strip ── --}}
        <div class="h-1.5 w-full bg-[#2ab4c0]"></div>

        <div class="p-6 sm:p-8">

            {{-- PNR & barcode row ── --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-8 pb-6 border-b border-gray-100">
                <div>
                    <span class="block text-[10px] font-bold tracking-widest text-[#2ab4c0] uppercase mb-1">Booking Reference (PNR)</span>
                    <span class="block text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">{{ $bookingReference }}</span>
                </div>
                <div class="flex flex-col items-end opacity-50 hidden sm:flex">
                    <div class="flex gap-0.5 h-10">
                        <div class="w-1 bg-gray-700 rounded-sm"></div><div class="w-2 bg-gray-700 rounded-sm"></div><div class="w-1 bg-gray-700 rounded-sm"></div><div class="w-1.5 bg-gray-700 rounded-sm"></div>
                        <div class="w-3 bg-gray-700 rounded-sm"></div><div class="w-1 bg-gray-700 rounded-sm"></div><div class="w-2 bg-gray-700 rounded-sm"></div><div class="w-1 bg-gray-700 rounded-sm"></div>
                        <div class="w-1.5 bg-gray-700 rounded-sm"></div><div class="w-3 bg-gray-700 rounded-sm"></div><div class="w-1 bg-gray-700 rounded-sm"></div><div class="w-1.5 bg-gray-700 rounded-sm"></div>
                        <div class="w-1 bg-gray-700 rounded-sm"></div><div class="w-2 bg-gray-700 rounded-sm"></div><div class="w-1.5 bg-gray-700 rounded-sm"></div><div class="w-3 bg-gray-700 rounded-sm"></div>
                    </div>
                </div>
            </div>

            {{-- Order ID & Status ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0] flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </span>
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-0.5">Amadeus System ID</span>
                        <span class="block text-sm font-bold text-gray-800 break-all">{{ $bookingId }}</span>
                    </div>
                </div>

                <div class="bg-[#2ab4c0]/5 rounded-xl p-4 border border-[#2ab4c0]/20 flex items-center justify-between gap-3">
                    <div>
                        <span class="block text-[10px] font-bold tracking-wider text-[#2399a3] uppercase mb-0.5">Order Status</span>
                        <span class="block text-base font-black text-[#2ab4c0]">TICKETED</span>
                    </div>
                    <div class="w-10 h-10 bg-[#2ab4c0]/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2ab4c0]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Actions (match site buttons) ── --}}
    <div class="mt-10 flex justify-center">
        <a href="{{ route('flights.search') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#2ab4c0] text-white text-sm font-black rounded-2xl shadow-lg shadow-[#2ab4c0]/30 hover:bg-[#2399a3] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Book Another Flight
        </a>
    </div>

</div>
