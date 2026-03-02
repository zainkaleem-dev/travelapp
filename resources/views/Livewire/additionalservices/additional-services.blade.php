
<div class="bg-slate-100 min-h-screen text-gray-800 text-xs">

    {{-- ── Navbar ──────────────────────────────────────────────────── --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">
            <div class="flex items-center gap-2 flex-shrink-0">
                <button class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
                </button>
                <button class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-indigo-200 text-indigo-600 font-medium hover:bg-indigo-50 transition-colors">
                    Next<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <button class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Find Reservation
                </button>
                <button class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden sm:inline">Login / Register</span>
                    <span class="sm:hidden">Login</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ── Step progress ───────────────────────────────────────────── --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Flight Tickets / Flight on Passenger Details</div>
            <div class="flex items-center overflow-x-auto">
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                    <span class="hidden sm:inline">Select Flight</span>
                </div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Passenger Details
                </div>
                <div class="flex items-center gap-1.5 px-3 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap" style="background:#2ab4c0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Additional Services
                </div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden sm:block">Choice Seat</div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap ml-auto hidden sm:block">Payment</div>
            </div>
        </div>
    </div>

    {{-- ── Page body ───────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT column ─────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">

                {{-- ── Travel Insurance card ───────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden toggle-card">

                    {{-- Card header --}}
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h2 class="font-semibold text-gray-800 text-sm">Travel Insurance</h2>
                    </div>

                    <div class="px-4 pt-3 pb-4">
                        <p class="text-gray-500 mb-3" style="font-size:10px">
                            Travel with peace of mind with protection that covers cancellations due to illness (including Covid-19) and injuries.
                        </p>

                        {{-- Feature list --}}
                        <ul class="space-y-2 mb-4">
                            @foreach ([
                                'If you or the people you are travelling with need to cancel the trip down.',
                                'If you or the people you are travelling with need to cancel the trip down.',
                                'Medical and dental: Covers emergency treatment expenses while abroad. Flight bookings and b.',
                                'Emergency assistance and transportation: Access to emergency medical assistance 24/7, transportation and more.',
                            ] as $feature)
                                <li class="flex items-start gap-2">
                                    <div class="w-4 h-4 rounded bg-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-600" style="font-size:10px">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <p class="font-semibold text-gray-700 mb-2">Details</p>

                        {{-- Option A: Add Insurance --}}
                        <div
                            wire:click="setInsurance('yes')"
                            class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer mb-2 transition-colors
                                {{ $insuranceOption === 'yes'
                                    ? 'border-indigo-500 bg-indigo-50 hover:bg-indigo-100'
                                    : 'border-gray-200 bg-white hover:bg-gray-50' }}"
                        >
                            <div class="flex items-center gap-2">
                                <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                    {{ $insuranceOption === 'yes' ? 'border-indigo-600' : 'border-gray-300' }}">
                                    @if ($insuranceOption === 'yes')
                                        <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-800" style="font-size:11px">Add Travel Assurance</span>
                                    <p class="text-gray-500" style="font-size:9px">Gain peace of mind for fewer than a coffee per person per holiday</p>
                                </div>
                            </div>
                            <span class="font-bold text-indigo-700 text-sm flex-shrink-0 ml-2">${{ number_format($insurancePrice, 0) }}</span>
                        </div>

                        {{-- Option B: No Insurance --}}
                        <div
                            wire:click="setInsurance('no')"
                            class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-colors
                                {{ $insuranceOption === 'no'
                                    ? 'border-indigo-500 bg-indigo-50 hover:bg-indigo-100'
                                    : 'border-gray-200 bg-white hover:bg-gray-50' }}"
                        >
                            <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                {{ $insuranceOption === 'no' ? 'border-indigo-600' : 'border-gray-300' }}">
                                @if ($insuranceOption === 'no')
                                    <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                                @endif
                            </div>
                            <span class="text-gray-500" style="font-size:10px">No thanks, continue without Insurance</span>
                        </div>

                        {{-- Fine print --}}
                        <p class="text-gray-400 mt-3 leading-relaxed" style="font-size:9px">
                            The insurance will be purchased independently by this site's
                            <span class="text-indigo-500 cursor-pointer hover:underline">Policy Conditions</span>.
                            Any questions about the insurance can be found in the insurance
                            <span class="text-indigo-500 cursor-pointer hover:underline">Information and FAQS</span>.
                            Questions about the booking or any other service on this website can be directed to
                            <span class="text-indigo-500 cursor-pointer hover:underline">Customer Support</span>.
                        </p>
                    </div>
                </div>
                {{-- /Travel Insurance card --}}

                {{-- ── "Need more time?" card ──────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden toggle-card">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4">

                        {{-- Illustration --}}
                        <div class="flex-shrink-0 w-20 h-20 bg-indigo-50 rounded-xl flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-300" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="8" y="16" width="48" height="34" rx="4"/>
                                <path d="M20 16V10a2 2 0 012-2h20a2 2 0 012 2v6"/>
                                <line x1="32" y1="24" x2="32" y2="40"/>
                                <line x1="24" y1="32" x2="40" y2="32"/>
                                <circle cx="48" cy="44" r="10" fill="#e0e7ff" stroke="#6366f1" stroke-width="1.5"/>
                                <path stroke="#6366f1" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M48 40v4l2 2"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Do you need more time to decide?</h3>
                            <p class="text-gray-500 mb-2" style="font-size:10px">Make your reservation now and hold the price.</p>
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                @foreach (['Get refund after booking', 'Hold ticket price for 3 days', 'Guaranteed your price'] as $perk)
                                    <span class="flex items-center gap-1 text-indigo-600 font-medium" style="font-size:10px">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                        {{ $perk }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <div>
                                    <span class="text-gray-400" style="font-size:9px">Offers starting from</span>
                                    <span class="font-bold text-gray-800 ml-1">47.00 USD</span>
                                </div>
                                <button class="flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors sm:ml-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Hold my price
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- /"Need more time?" card --}}

                {{-- ── Extra Checked Baggage card ───────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden toggle-card">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4">

                        {{-- Luggage icon --}}
                        <div class="flex-shrink-0 w-20 h-20 bg-slate-50 rounded-xl flex items-center justify-center">
                            <svg class="w-12 h-14 text-slate-400" viewBox="0 0 48 56" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="6" y="12" width="36" height="38" rx="4"/>
                                <path d="M16 12V8a2 2 0 012-2h12a2 2 0 012 2v4"/>
                                <line x1="24" y1="20" x2="24" y2="44"/>
                                <line x1="14" y1="32" x2="34" y2="32"/>
                                <rect x="10" y="48" width="6" height="4" rx="2" fill="currentColor" stroke="none" opacity="0.3"/>
                                <rect x="32" y="48" width="6" height="4" rx="2" fill="currentColor" stroke="none" opacity="0.3"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Add Extra Checked Baggage</h3>
                            <p class="text-gray-500 mb-3" style="font-size:10px">
                                This service currently allows you to purchase more baggage allowance on the flight.
                            </p>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex items-center gap-3">

                                    {{-- Qty label --}}
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                                        <span class="text-gray-600" style="font-size:10px">{{ $baggageQty }} Extra Baggage 13kg</span>
                                    </div>

                                    {{-- Stepper --}}
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button
                                            wire:click="decrementBaggage"
                                            class="px-2 py-1.5 text-gray-500 hover:bg-gray-100 transition-colors font-bold"
                                        >−</button>
                                        <span class="px-3 py-1.5 text-gray-800 font-semibold border-x border-gray-200">{{ $baggageQty }}</span>
                                        <button
                                            wire:click="incrementBaggage"
                                            class="px-2 py-1.5 text-gray-500 hover:bg-gray-100 transition-colors font-bold"
                                        >+</button>
                                    </div>
                                </div>

                                {{-- Price + toggle --}}
                                <div class="flex items-center gap-3 sm:ml-auto">
                                    <span class="font-bold text-gray-800 text-sm">
                                        {{ number_format($baggagePrice * $baggageQty, 2) }} USD
                                    </span>
                                    {{-- Custom toggle switch --}}
                                    <button
                                        wire:click="toggleBaggage"
                                        class="relative flex-shrink-0 focus:outline-none"
                                        role="switch"
                                        aria-checked="{{ $baggageEnabled ? 'true' : 'false' }}"
                                    >
                                        <div class="w-9 h-5 rounded-full transition-colors duration-200
                                            {{ $baggageEnabled ? 'bg-indigo-500' : 'bg-gray-200' }}">
                                        </div>
                                        <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200
                                            {{ $baggageEnabled ? 'translate-x-4' : 'translate-x-0' }}">
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- /Extra Baggage card --}}

            </div>
            {{-- /LEFT --}}

            {{-- ── RIGHT: Summary sidebar ───────────────────────────── --}}
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-16">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Summary</h2>
                        <span class="text-gray-500" style="font-size:10px">2 Passenger</span>
                    </div>

                    <div class="px-4 py-3 space-y-2.5">

                        <div class="flex items-start justify-between gap-2">
                            <span class="text-gray-500 leading-relaxed" style="font-size:10px">Outbound Only Flight ↗</span>
                            <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($outboundPrice, 2) }}</span>
                        </div>

                        <div class="flex items-start justify-between gap-2">
                            <span class="text-gray-500 leading-relaxed" style="font-size:10px">Return Flight All</span>
                            <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($returnPrice, 2) }}</span>
                        </div>

                        @if (! $extraBaggageFeeRemoved)
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Extra Baggage 13kg</span>
                                    <button wire:click="removeExtraBaggage" class="block text-indigo-500 hover:text-indigo-700 hover:underline mt-0.5" style="font-size:9px">Remove</button>
                                </div>
                                <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($extraBaggageFee, 2) }}</span>
                            </div>
                        @endif

                        @if (! $travelInsuranceFeeRemoved)
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Travel Insurance</span>
                                    <button wire:click="removeTravelInsurance" class="block text-indigo-500 hover:text-indigo-700 hover:underline mt-0.5" style="font-size:9px">Remove</button>
                                </div>
                                <span class="font-semibold text-gray-800 flex-shrink-0">$17.00</span>
                            </div>
                        @endif

                        @if (! $seatTaxesRemoved)
                            <div class="flex items-start justify-between gap-2 pb-2 border-b border-gray-100">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Seat Taxes</span>
                                    <button wire:click="removeSeatTaxes" class="block text-indigo-500 hover:text-indigo-700 hover:underline mt-0.5" style="font-size:9px">Remove</button>
                                </div>
                                <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($seatTaxes, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-1">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-gray-900 text-lg">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="px-4 pb-4 flex gap-2">
                        <button class="flex-1 py-2 border border-indigo-200 text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
                        </button>
                        <button class="flex-1 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-1">
                            Continue<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            {{-- /RIGHT --}}

        </div>
    </div>

</div>
