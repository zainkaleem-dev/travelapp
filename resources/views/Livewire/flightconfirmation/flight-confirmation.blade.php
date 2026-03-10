<div class="min-h-screen bg-gray-50 flex flex-col items-center py-12 px-4 sm:px-6">

    {{-- ── Celebration / Success Animation ── --}}
    <div class="mb-8 relative">
        <div class="absolute inset-0 bg-emerald-400 rounded-full blur-xl opacity-30 animate-pulse"></div>
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center relative shadow-2xl shadow-emerald-500/40">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
    </div>

    {{-- ── Main Message ── --}}
    <div class="text-center mb-10 max-w-lg">
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-3 tracking-tight">Booking Confirmed!</h1>
        <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
            Your flight has been successfully booked. A confirmation email has been sent to the contact address provided. You're all set for your journey.
        </p>
    </div>

    {{-- ── Boarding Pass Style Ticket ── --}}
    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative">
        
        {{-- Top decorative strip --}}
        <div class="h-2 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>

        <div class="p-8 sm:p-10">
            
            {{-- Header of ticket --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-10 pb-8 border-b border-dashed border-gray-200">
                <div>
                    <span class="block text-xs font-bold tracking-widest text-indigo-500 uppercase mb-1">Booking Reference (PNR)</span>
                    <span class="block text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">{{ $bookingReference }}</span>
                </div>
                
                {{-- Barcode visual (pure CSS) --}}
                <div class="flex flex-col items-end opacity-60 hidden sm:flex">
                    <div class="flex gap-1 h-12">
                        <div class="w-1 bg-gray-800"></div><div class="w-2 md:w-3 bg-gray-800"></div><div class="w-1 bg-gray-800"></div><div class="w-1.5 bg-gray-800"></div>
                        <div class="w-3 md:w-4 bg-gray-800"></div><div class="w-1 bg-gray-800"></div><div class="w-2 md:w-3 bg-gray-800"></div><div class="w-1 bg-gray-800"></div>
                        <div class="w-1.5 bg-gray-800"></div><div class="w-3 md:w-4 bg-gray-800"></div><div class="w-1 md:w-2 bg-gray-800"></div><div class="w-1.5 bg-gray-800"></div>
                        <div class="w-1 bg-gray-800"></div><div class="w-2 bg-gray-800"></div><div class="w-1.5 bg-gray-800"></div><div class="w-3 bg-gray-800"></div>
                    </div>
                </div>
            </div>

            {{-- Body of ticket --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                
                {{-- Amadeus Order ID --}}
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <span class="block text-[10px] font-bold tracking-wider text-gray-400 uppercase mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        Amadeus System ID
                    </span>
                    <span class="block text-sm font-semibold text-gray-800 break-all">{{ $bookingId }}</span>
                </div>
                
                {{-- Status --}}
                <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-bold tracking-wider text-emerald-600 uppercase mb-1">Order Status</span>
                        <span class="block text-lg font-black text-emerald-700">TICKETED</span>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- ── Actions ── --}}
    <div class="mt-10 flex gap-4">
        <a href="{{ route('flights.search') }}" class="group relative px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-2xl overflow-hidden shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center gap-2">
            <span class="relative z-10 flex items-center gap-2">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Book Another Flight
            </span>
            <div class="absolute inset-0 h-full w-full bg-indigo-700 scale-x-0 origin-left transition-transform group-hover:scale-x-100 z-0"></div>
        </a>
    </div>

</div>