<div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Main Form ─────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">

                {{-- Flight Details --}}
                @php 
                    $firstCabin = !empty($availableFares) ? strtolower(array_key_first($availableFares)) : 'economy';
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ fareOpen: false, cabin: '{{ $firstCabin }}' }">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-800 text-sm">Flight Details</h2>
                        <button type="button" @click="fareOpen = !fareOpen" class="text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1 text-xs font-medium">
                            <span x-text="fareOpen ? 'Hide Fares' : 'Show Fares'"></span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="fareOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    {{-- Outbound --}}
                    @if(isset($selectedFlight['rawOffer']['itineraries'][0]))
                    <div class="px-4 py-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-semibold mb-3" style="background:#2ab4c0; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            Outbound Flight
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-7 h-7 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold flex-shrink-0 relative overflow-hidden">
                                <img src="https://pics.avs.io/64/64/{{ $selectedFlight['airlineCode'] ?? '??' }}.png" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $selectedFlight['dep'] ?? '00:00' }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $selectedFlight['depAirport'] ?? '---' }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">{{ $selectedFlight['stops'] ?? 'Direct' }}</div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-gray-400" style="font-size:9px">{{ $selectedFlight['duration'] ?? '--' }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $selectedFlight['arr'] ?? '00:00' }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $selectedFlight['arrAirport'] ?? '---' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($selectedFlight['rawOffer']['itineraries'][1]))
                    <div class="mx-4 border-t border-dashed border-gray-200"></div>

                    {{-- Return --}}
                    <div class="px-4 py-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-semibold mb-3" style="background:#6366f1; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            Return Flight
                        </div>
                        @php
                            $retItin = $selectedFlight['rawOffer']['itineraries'][1];
                            $retFirst = $retItin['segments'][0];
                            $retLast = end($retItin['segments']);
                            $retDep = date('H:i', strtotime($retFirst['departure']['at']));
                            $retArr = date('H:i', strtotime($retLast['arrival']['at']));
                            $retDuration = str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $retItin['duration']);
                            $retStopsCount = count($retItin['segments']) - 1;
                            $retStops = $retStopsCount === 0 ? 'Direct' : $retStopsCount . ' Stop' . ($retStopsCount > 1 ? 's' : '');
                        @endphp
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-7 h-7 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold flex-shrink-0 relative overflow-hidden">
                                <img src="https://pics.avs.io/64/64/{{ $retFirst['carrierCode'] ?? '??' }}.png" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $retDep }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $retFirst['departure']['iataCode'] }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">{{ $retStops }}</div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-gray-400" style="font-size:9px">{{ $retDuration }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $retArr }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $retLast['arrival']['iataCode'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Collapsible Fare Panel --}}
                    <div x-show="fareOpen" x-collapse>
                        <div class="border-t border-gray-100 pt-3 px-4 pb-4">
                            @if(empty($availableFares))
                                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 text-center">
                                    <svg class="w-10 h-10 text-indigo-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <h3 class="text-sm font-bold text-indigo-900 mb-1">No Additional Packages Available</h3>
                                    <p class="text-xs text-indigo-700">This specific flight does not offer any branded upgrade packages via Amadeus. The standard basic fare applies.</p>
                                </div>
                            @else
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-2 border-b border-gray-100">
                                    <div class="flex items-center gap-4">
                                        @foreach($availableFares as $cabinCode => $fares)
                                        @php $lowerCode = strtolower($cabinCode); @endphp
                                        <button type="button" class="pb-2 font-medium"
                                                @click="cabin='{{ $lowerCode }}'"
                                                :class="cabin==='{{ $lowerCode }}' ? 'text-gray-900 border-b-2 border-emerald-700' : 'text-gray-400'">
                                            {{ ucfirst($lowerCode) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            @foreach($availableFares as $cabinCode => $fares)
                                @php 
                                    $lowerCode = strtolower($cabinCode); 
                                    $bgClass = match($lowerCode) {
                                        'economy' => 'bg-emerald-800',
                                        'premium_economy' => 'bg-emerald-900',
                                        'business' => 'bg-indigo-700',
                                        'first' => 'bg-red-800',
                                        default => 'bg-gray-800'
                                    };
                                @endphp
                                <div x-show="cabin === '{{ $lowerCode }}'" class="bg-gray-50 rounded-2xl p-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-[{{ count($fares) <= 3 ? count($fares) : 3 }}] gap-4">
                                        @foreach($fares as $fare)
                                            <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm flex flex-col cursor-pointer hover:border-indigo-400 transition-colors">
                                                <div class="{{ $bgClass }} text-white px-4 py-3 flex justify-between items-center">
                                                    <div>
                                                        <p class="text-xs font-semibold uppercase">{{ $fare['name'] }}</p>
                                                        <p class="text-base font-bold">${{ $fare['price'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="px-4 py-3 text-[10px] text-gray-600 space-y-1 flex-1">
                                                    <p>• {{ $fare['bags'] }}</p>
                                                    @foreach($fare['amenities'] as $amenity)
                                                        <p>• {{ $amenity }}</p>
                                                    @endforeach
                                                </div>
                                                <div class="px-4 pb-4">
                                                    <button type="button" class="w-full py-1.5 border border-indigo-200 text-indigo-700 text-xs font-semibold rounded hover:bg-indigo-50 transition-colors">Select Fare</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Baggage Info ─────────────────────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Cabin --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Cabin Baggage and Hand Bag</h3>
                        <p class="text-gray-500 mb-3" style="font-size:10px">Includes items carried in your own bag and items allowed under the rules set by the contracted airline.</p>
                        <div class="flex items-end gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-teal-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="10" y="12" width="28" height="30" rx="3"/>
                                    <path stroke-linecap="round" d="M18 12V8a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                    <line x1="24" y1="18" x2="24" y2="36"/>
                                    <line x1="16" y1="27" x2="32" y2="27"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Cabin Bag</span>
                                <span class="text-gray-400" style="font-size:9px">Cabin Bag (7 kg / 15lbs)</span>
                            </div>
                            <div class="flex flex-col items-center gap-1 ml-2">
                                <svg class="w-8 h-8 text-indigo-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="8" y="16" width="32" height="26" rx="3"/>
                                    <path d="M17 16V12a2 2 0 012-2h10a2 2 0 012 2v4"/>
                                    <line x1="24" y1="22" x2="24" y2="36"/>
                                    <line x1="16" y1="29" x2="32" y2="29"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Personal Item</span>
                                <span class="text-gray-400" style="font-size:9px">Under Seat (4kg)</span>
                            </div>
                        </div>
                    </div>
                    {{-- Checked --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Checked Baggage Included In Your Flight</h3>
                        <p class="text-gray-500 mb-3" style="font-size:10px">These are the luggage delivered to the airline that will be carried in the aircraft's hold compartment.</p>
                        <div class="flex items-end gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-teal-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="10" y="10" width="28" height="32" rx="3"/>
                                    <path d="M18 10V6a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                    <line x1="24" y1="18" x2="24" y2="34"/>
                                    <line x1="16" y1="26" x2="32" y2="26"/>
                                    <line x1="16" y1="38" x2="32" y2="38"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Checked Baggage</span>
                                <span class="text-gray-400" style="font-size:9px">1 Checked Baggage (23kg)</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end LEFT --}}

            {{-- ── RIGHT: Summary Sidebar ───────────────────────────────────── --}}
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-16">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Summary</h2>
                        <span class="text-gray-500" style="font-size:10px">{{ $passengerCount }} Passenger{{ $passengerCount > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="px-4 py-3 space-y-2">
                        @foreach ($summaryItems as $i => $item)
                            <div class="flex items-center justify-between {{ $loop->last ? 'pt-1' : '' }} {{ $loop->index === count($summaryItems) - 2 ? 'pb-2 border-b border-gray-100' : '' }}">
                                <div class="flex items-center gap-1">
                                    <span class="text-gray-500" style="font-size:10px">{{ $item['label'] }}</span>
                                    @if ($item['removable'])
                                        <button
                                            wire:click="removeItem({{ $i }})"
                                            class="ml-1 text-indigo-500 hover:underline"
                                            style="font-size:10px"
                                        >Remove</button>
                                    @endif
                                </div>
                                <span class="{{ $loop->last ? 'font-bold text-gray-900 text-lg' : 'font-semibold text-gray-800' }}">${{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-between pt-1 border-t border-gray-100">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-gray-900 text-lg">${{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>
                    <div class="px-4 pb-4 flex gap-2">
                        <button
                            wire:click="back"
                            class="flex-1 py-2 border border-indigo-200 text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition-colors flex items-center justify-center gap-1"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
                        </button>
                        <button
                            wire:click="continue"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-1"
                        >
                            <span wire:loading.remove wire:target="continue">
                                Continue<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-1">
                                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Saving…
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
