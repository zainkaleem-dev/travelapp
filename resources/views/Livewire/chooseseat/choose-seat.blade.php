<div class="bg-slate-100 min-h-screen text-gray-800 text-xs">

    {{-- ── Promo banner ──────────────────────────────────────────── --}}
    <div class="bg-indigo-600 text-white py-1.5 text-center text-xs relative">
        <span>🏷️ Up to 20% discount with early booking! Sign up now and benefit from the offer.</span>
        <div class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 items-center gap-4 text-white/80">
            <button class="flex items-center gap-1 hover:text-white">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>App
            </button>
            <button class="flex items-center gap-1 hover:text-white">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
                Support <svg class="w-2 h-2 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <button class="flex items-center gap-1 hover:text-white">
                English <svg class="w-2 h-2 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <button class="flex items-center gap-1 hover:text-white">
                USD <svg class="w-2 h-2 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    </div>

    {{-- ── Navbar ─────────────────────────────────────────────────── --}}
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
            <div class="flex-1 max-w-xs mx-2 relative hidden sm:block">
                <input type="text" placeholder="Search..." class="w-full pl-3 pr-8 py-1.5 border border-gray-200 rounded-full bg-gray-50 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200"/>
                <button class="absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-orange-400 flex items-center justify-center">
                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/></svg>
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

    {{-- ── Step progress ──────────────────────────────────────────── --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Flight Tickets / Choice Seat</div>
            <div class="flex items-center overflow-x-auto">
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap">
                    <span class="hidden sm:inline">Select Flight</span><span class="sm:hidden">Flight</span>
                </div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap">Passenger Details</div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden sm:block">Additional Services</div>
                <div class="flex items-center gap-1.5 px-3 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap" style="background:#2ab4c0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/></svg>
                    Choice Seat
                </div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap ml-auto">Payment</div>
            </div>
        </div>
    </div>

    {{-- ── Page body ──────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Seat selection ────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 border-b border-gray-100">
                        <div>
                            <h2 class="font-semibold text-gray-800 text-sm">Choose Seat 2/1</h2>
                            <p class="text-gray-400 mt-0.5" style="font-size:10px">Select a seat for this flight</p>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-white font-semibold flex-shrink-0" style="background:#2ab4c0; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            Outbound Flight
                        </div>
                    </div>

                    {{-- Flight route mini --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-red-500 flex items-center justify-center text-white font-bold flex-shrink-0" style="font-size:9px">TK</div>
                            <div class="flex-1 flex items-center gap-2">
                                <div class="flex-shrink-0">
                                    <p class="font-bold text-gray-800">17:30</p>
                                    <p class="text-gray-500" style="font-size:9px">Düsseldorf</p>
                                    <p class="text-gray-400 hidden sm:block" style="font-size:9px">Düsseldorf International Airport (DUS)</p>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                    <div class="relative w-full flight-line flex items-center justify-between">
                                        <div class="flight-dot"></div>
                                        <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">Direct</div>
                                        <div class="flight-dot"></div>
                                    </div>
                                    <p class="text-gray-400" style="font-size:9px">4h 28m</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-gray-800">23:58</p>
                                    <p class="text-gray-500" style="font-size:9px">Istanbul</p>
                                    <p class="text-gray-400 hidden sm:block" style="font-size:9px">Istanbul International Airport (IST)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Passenger tabs ──────────────────────────── --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($passengers as $index => $passenger)
                                @php $isActive = $activePassenger === $index; @endphp
                                <div
                                    wire:click="switchPassenger({{ $index }})"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl cursor-pointer transition-colors
                                        {{ $isActive
                                            ? 'bg-indigo-50 border border-indigo-200'
                                            : 'bg-gray-50 border border-gray-200 hover:border-indigo-200' }}"
                                >
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0
                                        {{ $isActive ? 'bg-indigo-600' : 'bg-gray-400' }}"
                                        style="font-size:9px">
                                        P{{ $index }}
                                    </div>
                                    <div>
                                        <p class="font-semibold {{ $isActive ? 'text-indigo-700' : 'text-gray-600' }}" style="font-size:10px">
                                            {{ $passenger['label'] }}
                                        </p>
                                        <p class="{{ $isActive ? 'text-indigo-500' : 'text-gray-400' }}" style="font-size:9px">
                                            Seat Number : {{ $passenger['seat'] ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Legend ──────────────────────────────────── --}}
                    <div class="px-4 py-2.5 border-b border-gray-100 flex flex-wrap gap-4">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded flex-shrink-0 seat-standard" style="border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Standard Seats</span>
                            <span class="font-semibold text-gray-800" style="font-size:10px">27.00 USD</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0" style="background:#a5f3fc;border:1px solid #67e8f9;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Extra Legroom</span>
                            <span class="font-semibold text-gray-800" style="font-size:10px">44.00 USD</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0" style="background:#cbd5e1;border:1px solid #94a3b8;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Occupied</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0" style="background:#6366f1;border:1px solid #4f46e5;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Selected</span>
                        </div>
                    </div>

                    {{-- ── Seat Map ─────────────────────────────────── --}}
                    <div class="px-4 py-4 overflow-x-auto">
                        <div class="min-w-max mx-auto" style="max-width:340px">

                            {{-- Column headers --}}
                            <div class="flex items-center mb-2 pl-7">
                                <div class="flex gap-1.5 mr-4">
                                    @foreach (['A','B','C'] as $col)
                                        <div class="w-[22px] text-center text-gray-400 font-semibold" style="font-size:9px">{{ $col }}</div>
                                    @endforeach
                                </div>
                                <div class="w-5"></div>
                                <div class="flex gap-1.5 ml-4">
                                    @foreach (['D','E','F'] as $col)
                                        <div class="w-[22px] text-center text-gray-400 font-semibold" style="font-size:9px">{{ $col }}</div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Rows --}}
                            <div class="space-y-1">
                                @for ($row = 1; $row <= $totalRows; $row++)
                                    @php $isExtra = $this->isExtraLegroom($row); @endphp

                                    {{-- Extra legroom divider (not before first row) --}}
                                    @if ($isExtra && $row !== 1)
                                        <div class="h-2 flex items-center">
                                            <div class="flex-1 border-t border-dashed border-cyan-300 mx-7"></div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-1">
                                        {{-- Row number --}}
                                        <div class="w-6 text-right text-gray-400 font-medium flex-shrink-0" style="font-size:9px">
                                            {{ $row }}
                                        </div>

                                        {{-- Left seats: A B C --}}
                                        <div class="flex gap-1.5">
                                            @foreach (['A','B','C'] as $col)
                                                @php
                                                    $seatId    = $row . $col;
                                                    $occupied  = in_array($seatId, $occupiedSeats);
                                                    $isSelected = $selectedSeat === $seatId;

                                                    $seatClass = match(true) {
                                                        $isSelected => 'seat-selected',
                                                        $occupied   => 'seat-occupied',
                                                        $isExtra    => 'seat-extra-legroom',
                                                        default     => 'seat-available',
                                                    };
                                                @endphp
                                                <button
                                                    class="seat {{ $seatClass }}"
                                                    @if ($occupied) disabled @endif
                                                    @if (!$occupied) wire:click="selectSeat('{{ $seatId }}')" @endif
                                                    title="{{ $seatId }}"
                                                >
                                                    @if ($isSelected) ✓ @endif
                                                </button>
                                            @endforeach
                                        </div>

                                        {{-- Aisle --}}
                                        <div class="w-5 flex-shrink-0"></div>

                                        {{-- Right seats: D E F --}}
                                        <div class="flex gap-1.5">
                                            @foreach (['D','E','F'] as $col)
                                                @php
                                                    $seatId    = $row . $col;
                                                    $occupied  = in_array($seatId, $occupiedSeats);
                                                    $isSelected = $selectedSeat === $seatId;

                                                    $seatClass = match(true) {
                                                        $isSelected => 'seat-selected',
                                                        $occupied   => 'seat-occupied',
                                                        $isExtra    => 'seat-extra-legroom',
                                                        default     => 'seat-available',
                                                    };
                                                @endphp
                                                <button
                                                    class="seat {{ $seatClass }}"
                                                    @if ($occupied) disabled @endif
                                                    @if (!$occupied) wire:click="selectSeat('{{ $seatId }}')" @endif
                                                    title="{{ $seatId }}"
                                                >
                                                    @if ($isSelected) ✓ @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            {{-- Exit row labels --}}
                            <div class="flex justify-between mt-2 px-1">
                                <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                                <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                            </div>
                        </div>
                    </div>
                    {{-- /Seat Map --}}

                </div>
            </div>
            {{-- /LEFT --}}

            {{-- ── RIGHT: Summary ──────────────────────────────────── --}}
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-16">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Summary</h2>
                        <span class="text-gray-500" style="font-size:10px">{{ count($passengers) }} Passenger</span>
                    </div>

                    <div class="px-4 py-3 space-y-2.5">

                        <div class="flex items-start justify-between gap-2">
                            <span class="text-gray-500" style="font-size:10px">Outbound only Flight ↗</span>
                            <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($outboundPrice, 2) }}</span>
                        </div>

                        <div class="flex items-start justify-between gap-2">
                            <span class="text-gray-500" style="font-size:10px">Return Flight All</span>
                            <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($returnPrice, 2) }}</span>
                        </div>

                        @if ($extraBaggage > 0)
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Extra Baggage 13kg</span>
                                    <button wire:click="removeExtraBaggage" class="block text-indigo-500 hover:underline mt-0.5" style="font-size:9px">Remove</button>
                                </div>
                                <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($extraBaggage, 2) }}</span>
                            </div>
                        @endif

                        @if ($travelInsurance > 0)
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Travel Insurance</span>
                                    <button wire:click="removeTravelInsurance" class="block text-indigo-500 hover:underline mt-0.5" style="font-size:9px">Remove</button>
                                </div>
                                <span class="font-semibold text-gray-800 flex-shrink-0">${{ number_format($travelInsurance, 2) }}</span>
                            </div>
                        @endif

                        @if ($seatTaxes > 0)
                            <div class="flex items-start justify-between gap-2 pb-2.5 border-b border-gray-100">
                                <div>
                                    <span class="text-gray-500" style="font-size:10px">Seat Taxes</span>
                                    <button wire:click="removeSeatTaxes" class="block text-indigo-500 hover:underline mt-0.5" style="font-size:9px">Remove</button>
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
