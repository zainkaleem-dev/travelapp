<div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto flex flex-row gap-4 px-4 py-4">
<div class="flex flex-col w-full max-w-[790px] lg:flex-row gap-4">
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
                            <span x-show="!fareOpen">Show Fares</span>
                            <span x-show="fareOpen" style="display: none;">Hide Fares</span>
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
                    <div x-show="fareOpen" style="display: none;">
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

                {{-- ── Dynamic Baggage Info ─────────────────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Cabin --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Cabin Baggage</h3>
                        <p class="text-gray-500 mb-3" style="font-size:10px">Standard carry-on allowance as per airline policy.</p>
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-teal-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="10" y="12" width="28" height="30" rx="3"/>
                                    <path stroke-linecap="round" d="M18 12V8a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                    <line x1="24" y1="18" x2="24" y2="36"/>
                                    <line x1="16" y1="27" x2="32" y2="27"/>
                                </svg>
                                <span class="text-gray-600 font-medium" style="font-size:10px">1 Cabin Bag</span>
                                <span class="text-gray-400" style="font-size:9px">Airline standard</span>
                            </div>
                            <div class="ml-2">
                                <p class="text-gray-500" style="font-size:9px">Weight/size limits<br>set by the airline.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Checked --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Checked Baggage Included</h3>
                        @if(empty($includedBaggage))
                            <p class="text-red-500 font-medium" style="font-size:10px">No checked baggage included in this fare.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($includedBaggage as $bag)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8 text-teal-500" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="10" y="10" width="28" height="32" rx="3"/>
                                            <path d="M18 10V6a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                        </svg>
                                        <div>
                                            <p class="text-gray-800 font-bold" style="font-size:10px">
                                                {{ $bag['quantity'] ?? '1' }} Bag(s)
                                            </p>
                                            <p class="text-gray-400" style="font-size:9px">
                                                Max {{ $bag['weight'] ?? '23' }}{{ $bag['weightUnit'] ?? 'KG' }} each
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Extra Checked Baggage ────────────────────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4">
                        <div class="flex-shrink-0 w-20 h-20 bg-slate-50 rounded-xl flex items-center justify-center">
                            <svg class="w-12 h-14 text-slate-400" viewBox="0 0 48 56" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="6" y="12" width="36" height="38" rx="4"/>
                                <path d="M16 12V8a2 2 0 012-2h12a2 2 0 012 2v4"/>
                                <line x1="24" y1="20" x2="24" y2="44"/><line x1="14" y1="32" x2="34" y2="32"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Add Extra Checked Baggage</h3>
                            <p class="text-gray-500 mb-3" style="font-size:10px">Purchase additional baggage allowance for your trip.</p>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-600" style="font-size:10px">Extra Bag (23kg)</span>
                                    </div>
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition-colors font-bold" wire:click="decrementBaggage">−</button>
                                        <span class="px-3 py-1 text-gray-800 font-semibold border-x border-gray-200" style="font-size:11px">{{ $baggageQty }}</span>
                                        <button class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition-colors font-bold" wire:click="incrementBaggage">+</button>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:ml-auto">
                                    <span class="font-bold text-gray-800 text-sm">${{ number_format($baggagePrice, 2) }} / bag</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Dynamic Amenities / Ancillaries ─────────────────────────── --}}
                @if(!empty($availableAncillaries))
                <div class="space-y-3">
                    <h3 class="font-bold text-gray-800 text-sm px-1">Enhance Your Experience</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($availableAncillaries as $anc)
                            <div class="bg-white rounded-xl border {{ isset($selectedAncillaries[$anc['code']]) ? 'border-indigo-500 bg-indigo-50/30' : 'border-gray-200' }} p-4 transition-all hover:shadow-md cursor-pointer" 
                                 wire:click="toggleAncillary('{{ $anc['code'] }}')">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        @if(strpos(strtolower($anc['description']), 'lounge') !== false)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        @elseif(strpos(strtolower($anc['description']), 'wifi') !== false || strpos(strtolower($anc['description']), 'internet') !== false)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a9.9 9.9 0 0114.142 0M2.828 9.9a15 15 0 0121.214 0"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <p class="font-bold text-gray-800 text-sm capitalize">{{ ucwords(strtolower($anc['description'])) }}</p>
                                            @if($anc['isChargeable'])
                                                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full px-2 py-0.5">Chargeable</span>
                                            @else
                                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 rounded-full px-2 py-0.5">Included</span>
                                            @endif
                                        </div>
                                        @php
                                            $ancType = strtolower($anc['amenityType'] ?? '');
                                            $ancSubtitle = match(true) {
                                                str_contains($ancType, 'lounge') => 'Airport lounge access during your journey.',
                                                str_contains($ancType, 'baggage') => 'Baggage allowance service.',
                                                str_contains($ancType, 'meal') => 'In-flight meal service.',
                                                str_contains($ancType, 'wifi') || str_contains($ancType, 'internet') => 'High-speed in-flight internet.',
                                                str_contains($ancType, 'seat') => 'Advance seat selection.',
                                                str_contains($ancType, 'pre_reserved_seat') => 'Reserve your preferred seat in advance.',
                                                str_contains($ancType, 'entertainment') => 'In-flight entertainment system.',
                                                default => ($anc['isChargeable'] ? 'Optional paid add-on for your booking.' : 'Included as part of your selected fare.')
                                            };
                                        @endphp
                                        <p class="text-gray-500" style="font-size:9px">{{ $ancSubtitle }}</p>
                                    </div>
                                    <div class="ml-2">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ isset($selectedAncillaries[$anc['code']]) ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300' }}">
                                            @if(isset($selectedAncillaries[$anc['code']]))
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24 font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                </div>

            </div>{{-- end LEFT --}}

            {{-- ── RIGHT: Summary Sidebar ───────────────────────────────────── --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-[1rem] border border-gray-100 shadow-2xl shadow-gray-100/50 overflow-hidden sticky top-16">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900 text-xl">Summary</h2>
                        <span class="text-gray-400 font-medium" style="font-size:11px">{{ $passengerCount }} Passenger{{ $passengerCount > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        @foreach ($summaryItems as $i => $item)
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-400 font-medium leading-tight" style="font-size:11px">{{ $item['label'] }}</span>
                                    @if ($item['removable'])
                                        <button
                                            wire:click="removeItem({{ $i }})"
                                            class="text-indigo-500 hover:underline text-left"
                                            style="font-size:10px"
                                        >Remove</button>
                                    @endif
                                </div>
                                <span class="font-bold text-gray-900 flex-shrink-0" style="font-size:13px">${{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach

                        <div class="pt-4 mt-2 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-gray-900 font-bold text-base">Total</span>
                            <span class="text-gray-900 font-bold text-2xl">${{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="px-6 pb-6 flex gap-3">
                        <button
                            wire:click="back"
                            class="flex-1 py-3 border border-gray-200 text-indigo-600 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-xs"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button
                            wire:click="continue"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 text-xs"
                        >
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-2">
                                Continue <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-2">
                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
