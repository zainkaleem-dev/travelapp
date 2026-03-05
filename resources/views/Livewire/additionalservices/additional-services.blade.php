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

                    {{-- Flight Itineraries --}}
                    @foreach($selectedFlight['itineraries'] ?? [] as $index => $itin)
                        @if($index > 0)
                            <div class="mx-4 border-t border-dashed border-gray-200"></div>
                        @endif
                        <div class="px-4 py-3">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-semibold mb-3" 
                                style="background:{{ $index === 0 ? '#2ab4c0' : '#6366f1' }}; font-size:10px">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                                {{ $index === 0 ? 'Outbound' : 'Return' }} Flight
                            </div>
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-11 h-11 flex items-center justify-center flex-shrink-0 relative overflow-hidden rounded-lg">
                                    <img src="https://pics.avs.io/128/128/{{ $itin['airlineCode'] }}.png" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-shrink-0">
                                            <p class="font-bold text-gray-800 text-sm">{{ $itin['dep'] }}</p>
                                            <p class="text-gray-500" style="font-size:9px">{{ $itin['depCity'] }}</p>
                                            <p class="text-gray-400 font-medium" style="font-size:8px">{{ $itin['depAirport'] }}</p>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                            <span class="font-bold text-gray-500 uppercase" style="font-size:8px">{{ $itin['flightNumber'] ?? '' }}</span>
                                            <div class="relative w-full flight-line flex items-center justify-between">
                                                <div class="flight-dot"></div>
                                                <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">
                                                    {{ $itin['stops'] }}
                                                </div>
                                                <div class="flight-dot"></div>
                                            </div>
                                            <p class="text-gray-400" style="font-size:9px">{{ $itin['duration'] }}</p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="font-bold text-gray-800 text-sm">{{ $itin['arr'] }}</p>
                                            <p class="text-gray-500" style="font-size:9px">{{ $itin['arrCity'] }}</p>
                                            <p class="text-gray-400 font-medium" style="font-size:8px">{{ $itin['arrAirport'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

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
                                        @foreach($fares as $index => $fare)
                                            <div class="rounded-2xl bg-white border {{ $selectedFareName === $fare['name'] ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-gray-200 hover:border-indigo-400' }} overflow-hidden shadow-sm flex flex-col transition-colors">
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
                                                    <button type="button" 
                                                            wire:click="selectFare('{{ $cabinCode }}', {{ $index }})"
                                                            class="w-full py-1.5 border text-xs font-semibold rounded transition-colors {{ $selectedFareName === $fare['name'] ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'border-indigo-200 text-indigo-700 hover:bg-indigo-50' }}">
                                                        {{ $selectedFareName === $fare['name'] ? 'Selected' : 'Select Fare' }}
                                                    </button>
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
