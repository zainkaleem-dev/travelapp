<div>
    {{-- ─── Page body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Seat selection ───────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">

                <!-- Header -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 border-b border-gray-100">
                        <div>
                            <h2 class="font-semibold text-gray-800 text-sm">Choose Seat</h2>
                            <p class="text-gray-400 mt-0.5" style="font-size:10px">Select a seat for this flight</p>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-white font-semibold flex-shrink-0"
                            style="background:#2ab4c0; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z">
                                </path>
                            </svg>
                            Flight Details
                        </div>
                    </div>

                    <!-- Flight route mini (dynamic) -->
                    <div class="px-4 py-3 border-b border-gray-100 space-y-4">
                        @foreach($flightInfo as $itin)
                            <div class="flex items-center gap-3 {{ !$loop->first ? 'pt-4 border-t border-gray-50' : '' }}">
                                <div class="flex flex-col items-center gap-1 flex-shrink-0">
                                    <div
                                        class="w-7 h-7 flex items-center justify-center flex-shrink-0 relative overflow-hidden">
                                        <img src="https://pics.avs.io/64/64/{{ $itin['airlineCode'] }}.png"
                                            class="w-full h-full object-contain">
                                    </div>
                                    <span class="font-bold text-gray-400 uppercase"
                                        style="font-size:8px">{{ $itin['type'] }}</span>
                                </div>
                                <div class="flex-1 flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $itin['departureTime'] ?? '—' }}</p>
                                        <p class="text-gray-500" style="font-size:10px">
                                            {{ $itin['originCity'] ?? $itin['origin'] ?? '—' }}
                                        </p>
                                        <p class="text-gray-400 hidden sm:block font-medium" style="font-size:9px">
                                            {{ $itin['origin'] ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                        <span class="font-bold text-gray-500 uppercase"
                                            style="font-size:8px">{{ $itin['flightNumber'] ?? '—' }}</span>
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500 font-bold"
                                                style="font-size:8px">
                                                {{ ($itin['stops'] ?? 0) == 0 ? 'Direct' : ($itin['stops'] . ' Stop') }}
                                            </div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-gray-400 font-medium" style="font-size:9px">
                                            {{ $itin['duration'] ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $itin['arrivalTime'] ?? '—' }}</p>
                                        <p class="text-gray-500" style="font-size:10px">
                                            {{ $itin['destCity'] ?? $itin['destination'] ?? '—' }}
                                        </p>
                                        <p class="text-gray-400 hidden sm:block font-medium" style="font-size:9px">
                                            {{ $itin['destination'] ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Passenger selector tabs (dynamic) -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            @for($p = 1; $p <= $passengerCount; $p++)
                                @php $isCurrent = ($currentPassengerIndex === ($p - 1)); @endphp
                                <div wire:click="selectPassenger({{ $p - 1 }})"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $isCurrent ? 'bg-indigo-50 border border-indigo-200 shadow-sm' : 'bg-gray-50 border border-gray-200 hover:border-indigo-200' }} cursor-pointer transition-all">
                                    <div class="w-5 h-5 rounded-full {{ $isCurrent ? 'bg-indigo-600' : 'bg-gray-400' }} flex items-center justify-center text-white font-bold"
                                        style="font-size:9px">P{{ $p }}</div>
                                    <div>
                                        <p class="font-semibold {{ $isCurrent ? 'text-indigo-700' : 'text-gray-600' }}"
                                            style="font-size:10px">Passenger {{ $p }} · {{ $flightInfo[0]['origin'] ?? '' }}
                                            →
                                            {{ $flightInfo[count($flightInfo) - 1]['destination'] ?? '' }}
                                        </p>
                                        <p class="{{ $isCurrent ? 'text-indigo-500' : 'text-gray-400' }}"
                                            style="font-size:9px">Seat: {{ $passengerSeats[$p - 1]['id'] ?? '—' }}</p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="px-4 py-2.5 border-b border-gray-100 flex flex-wrap gap-4">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 rounded bg-gray-100 border border-gray-300 flex-shrink-0"
                                style="border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Standard Seats</span>
                            <span class="font-semibold text-gray-800" style="font-size:10px">27.00 USD</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0"
                                style="background:#a5f3fc;border:1px solid #67e8f9;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Extra Legroom</span>
                            <span class="font-semibold text-gray-800" style="font-size:10px">44.00 USD</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0"
                                style="background:#cbd5e1;border:1px solid #94a3b8;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Occupied</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 flex-shrink-0"
                                style="background:#6366f1;border:1px solid #4f46e5;border-radius:3px 3px 2px 2px"></div>
                            <span class="text-gray-600" style="font-size:10px">Selected</span>
                        </div>
                    </div>

                    <!-- Seat Map (dynamic) -->
                    <div class="px-4 py-4 overflow-x-auto">
                        <div class="min-w-max mx-auto" style="max-width:340px">
                            @if(empty($rows))
                                <!-- Fallback: seatmap unavailable -->
                                <div class="text-center py-10">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium text-sm">Seat map is not available for this flight.
                                    </p>
                                    <p class="text-gray-400 mt-1" style="font-size:10px">You can proceed without selecting a
                                        seat.</p>
                                </div>
                            @else
                                <!-- Column headers -->
                                <div class="flex items-center mb-2 pl-7">
                                    <div class="flex gap-1.5 mr-4">
                                        @foreach($leftCols as $col)
                                            <div class="w-[22px] text-center text-gray-400 font-semibold" style="font-size:9px">
                                                {{ $col }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="w-5"></div>
                                    <div class="flex gap-1.5 ml-4">
                                        @foreach($rightCols as $col)
                                            <div class="w-[22px] text-center text-gray-400 font-semibold" style="font-size:9px">
                                                {{ $col }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Rows (dynamic from Amadeus seatmap API) -->
                                <div id="seat-map" class="space-y-1">
                                    @foreach($rows as $row)
                                        {{-- Extra legroom row divider (not before first row) --}}
                                        @if($row['isExtra'] && !$loop->first)
                                            <div class="h-2 flex items-center">
                                                <div class="flex-1 border-t border-dashed border-cyan-300 mx-7"></div>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-1">
                                            {{-- Row number --}}
                                            <div class="w-6 text-right text-gray-400 font-medium flex-shrink-0"
                                                style="font-size:9px">
                                                {{ $row['number'] }}
                                            </div>

                                            {{-- Left seat group --}}
                                            <div class="flex gap-1.5">
                                                @foreach($leftCols as $col)
                                                    @if(isset($row['seats'][$col]))
                                                        @php $seat = $row['seats'][$col]; @endphp
                                                        <button id="seat-{{ $seat['id'] }}"
                                                            wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                            @disabled($seat['state'] === 'occupied')
                                                            class="seat seat-{{ $seat['state'] === 'extra' ? 'extra-legroom' : $seat['state'] }}">
                                                            {{ $seat['state'] === 'selected' ? '✓' : '' }}
                                                        </button>
                                                    @else
                                                        <div class="w-[22px]"></div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            {{-- Aisle --}}
                                            <div class="w-5 flex-shrink-0"></div>

                                            {{-- Right seat group --}}
                                            <div class="flex gap-1.5">
                                                @foreach($rightCols as $col)
                                                    @if(isset($row['seats'][$col]))
                                                        @php $seat = $row['seats'][$col]; @endphp
                                                        <button id="seat-{{ $seat['id'] }}"
                                                            wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                            @disabled($seat['state'] === 'occupied')
                                                            class="seat seat-{{ $seat['state'] === 'extra' ? 'extra-legroom' : $seat['state'] }}">
                                                            {{ $seat['state'] === 'selected' ? '✓' : '' }}
                                                        </button>
                                                    @else
                                                        <div class="w-[22px]"></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Exit row labels -->
                                <div class="flex justify-between mt-2 px-1">
                                    <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                                    <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Summary ───────────────────────────────────────── --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div
                    class="bg-white rounded-[1rem] border border-gray-100 shadow-2xl shadow-gray-100/50 overflow-hidden sticky top-16">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900 text-xl">Summary</h2>
                        @php
                            $counts = session('search_params')['passengers'] ?? ['adults' => 1];
                            $passCount = ($counts['adults'] ?? 0) + ($counts['children'] ?? 0) + ($counts['infants'] ?? 0);
                        @endphp
                        <span class="text-gray-400 font-medium" style="font-size:11px">{{ $passCount ?: 1 }}
                            Passenger{{ $passCount > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        @php
                            $summaryItems = session('booking_summary', []);
                            $total = (float) session('booking_total', 0);
                        @endphp

                        @foreach($summaryItems as $item)
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-gray-400 font-medium leading-tight"
                                    style="font-size:11px">{{ $item['label'] }}</span>
                                <span class="font-bold text-gray-900 flex-shrink-0"
                                    style="font-size:13px">${{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach

                        {{-- Current selected seats info preview (live updates) --}}
                        @php $currentSeatsTotal = 0; @endphp
                        @foreach($passengerSeats as $idx => $seat)
                            @php $currentSeatsTotal += (float) ($seat['price'] ?? 0); @endphp
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex flex-col">
                                    <span class="text-indigo-600 font-bold leading-tight"
                                        style="font-size:11px">P{{ $idx + 1 }} Seat: {{ $seat['id'] }}</span>
                                    <span class="text-gray-400" style="font-size:9px">Selection in progress</span>
                                </div>
                                <span class="font-bold text-indigo-700 flex-shrink-0"
                                    style="font-size:13px">${{ number_format($seat['price'] ?? 0, 2) }}</span>
                            </div>
                        @endforeach

                        <div class="pt-4 mt-2 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-gray-900 font-bold text-base">Total</span>
                            <span
                                class="text-gray-900 font-bold text-2xl">${{ number_format($total + $currentSeatsTotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="px-6 pb-6 flex gap-3">
                        <button type="button" wire:click="back"
                            class="flex-1 py-3 border border-gray-200 text-indigo-600 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Back
                        </button>
                        <button type="button" wire:click="continue" wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 text-xs">
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-2">
                                Continue
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-2">
                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>