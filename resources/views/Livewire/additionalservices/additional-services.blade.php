<div>
    {{-- ── Outbound Header (Teal Bar) ── --}}
    <div class="max-w-7xl mx-auto px-4 pt-6">
        <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2 shadow-lg shadow-[#2ab4c0]/20" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? '' }} → {{ $searchParams['destination'] ?? '' }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">{{ $searchParams['travelClass'] ?? 'Economy' }}</span>
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">
                        {{ ($searchParams['adultCount'] ?? 1) + ($searchParams['childCount'] ?? 0) + ($searchParams['infantCount'] ?? 0) }} Passenger(s)
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-white/90">Departure Date</p>
                <p class="text-base font-bold text-white">{{ \Carbon\Carbon::parse($searchParams['departDate'] ?? now())->format('l d.m.Y') }}</p>
            </div>
        </div>
    </div>

    {{-- ── Main Body ── --}}
    <div class="max-w-7xl mx-auto px-4 pb-12 flex flex-col lg:flex-row gap-6">

        {{-- ── LEFT: Flight Details & Fares ── --}}
        <div class="flex-1 min-w-0 space-y-6">

            {{-- Flight Card styling --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 relative hover:z-50" 
                 x-data="{ 
                    fareOpen: {{ !empty($availableFares) ? 'true' : 'false' }}, 
                    cabin: '{{ !empty($availableFares) ? strtolower(array_key_first($availableFares)) : 'economy' }}' 
                 }">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-t-xl">
                    <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        
                        Selected Flight Details
                    </h2>
                    <button type="button" @click="fareOpen = !fareOpen" class="bg-[#2ab4c0]/10 text-[#2ab4c0] hover:bg-[#2ab4c0] hover:text-white px-3 py-1.5 rounded-lg transition-all text-xs font-bold flex items-center gap-2">
                        <span x-show="!fareOpen">Manage Fares</span>
                        <span x-show="fareOpen" style="display: none;">Close Panel</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="fareOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Unified Flight Card --}}
                <div class="flex flex-col lg:flex-row">
                    
                    {{-- Left Content: Itinerary --}}
                    <div class="lg:w-[83%] flex-1 border-r border-gray-100 p-4 sm:p-5">
                        <div class="flex sm:flex-row">
                            
                            {{-- Airline Sidebar --}}
                            <div class="w-20 sm:w-24 flex-shrink-0 flex flex-col items-center justify-start gap-2 pt-1 pr-4 border-r border-gray-50">
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 shadow-sm">
                                    {{-- Use the first itinerary's airline for the main logo --}}
                                    @php $firstItin = $selectedFlight['itineraries'][0] ?? null; @endphp
                                    @if($firstItin)
                                        <img src="https://pics.avs.io/128/128/{{ $firstItin['airlineCode'] }}.png" 
                                             alt="{{ $firstItin['airline'] ?? '' }}" 
                                             class="w-full h-full object-contain">
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-tighter leading-tight">
                                    {{ $firstItin['airline'] ?? '' }}
                                </span>
                            </div>

                            {{-- Itinerary Section --}}
                            <div class="flex-1 pl-4 sm:pl-5 space-y-1">
                                @foreach($selectedFlight['itineraries'] ?? [] as $idx => $itin)
                                    <div class="flex flex-col sm:grid sm:grid-cols-3 items-center gap-4 sm:gap-6">
                                        
                                        <div class="flex flex-col justify-center">
                                            <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                                <span>{{ $itin['dep'] }}</span>
                                                <span class="text-gray-300 font-light">–</span>
                                                <span>{{ $itin['arr'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                                <span title="{{ $itin['depCity'] }}" class="cursor-help border-b border-dotted border-gray-300">{{ $itin['depAirport'] }}</span>
                                                <span class="text-gray-300 font-normal ml-0.5">–</span>
                                                <span title="{{ $itin['arrCity'] }}" class="cursor-help border-b border-dotted border-gray-300 ml-0.5">{{ $itin['arrAirport'] }}</span>
                                                <span class="mx-1 text-gray-300">|</span>
                                                <span class="text-gray-500">{{ $itin['flightNumber'] ?? '' }}</span>
                                                <span class="mx-1 text-gray-300">|</span>
                                                <span class="text-[10px] text-gray-400 font-medium">{{ $itin['aircraft'] ?? 'Aircraft' }}</span>
                                            </div>
                                        </div>

                                        {{-- Duration/Stops --}}
                                        <div class="w-full text-center py-2 sm:py-0 border-y sm:border-y-0 border-gray-50">
                                            <div class="text-sm font-black text-gray-800">{{ $itin['duration'] }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">{{ $itin['stops'] }}</div>
                                            
                                            @if(($itin['technicalStops'] ?? 0) > 0)
                                                <div class="mt-1 flex justify-center">
                                                    <span class="px-1.5 py-0.5 rounded-full bg-red-50 text-red-500 text-[9px] font-black uppercase tracking-tighter border border-red-100">
                                                        {{ $itin['technicalStops'] }} Tech Stop
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tools/Seats - Triggers amenities tooltip on hover --}}
                                        <div class="w-full flex flex-col items-center justify-center gap-1.5 group relative">
                                            <div class="flex gap-4 text-gray-400">
                                                <div class="cursor-pointer">
                                                    <div class="flex gap-1.5 group-hover:text-[#2ab4c0] transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                    </div>
                                                    
                                                    @php 
                                                        $allFlightAmenities = collect($selectedFlight['itineraries'] ?? [])
                                                            ->pluck('amenities')
                                                            ->flatten()
                                                            ->unique()
                                                            ->values();
                                                    @endphp

                                                    <div class="absolute bottom-full right-0 mb-3 hidden group-hover:block w-52 bg-white border border-gray-100 text-gray-600 shadow-2xl rounded-xl z-[100] overflow-hidden ring-1 ring-black/5">
                                                        <div class="p-3 bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase tracking-widest">Flight Amenities</div>
                                                        <div class="p-3 space-y-2">
                                                            @foreach($allFlightAmenities as $am)
                                                                <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                                                    <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> 
                                                                    {{ $am }}
                                                                </div>
                                                            @endforeach
                                                            <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                                                <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> 
                                                                Main Baggage: {{ $itin['baggage'] ?? 'Included' }}
                                                            </div>
                                                        </div>
                                                        <div class="absolute top-full right-4 -mt-1 border-4 border-transparent border-t-white"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Seats Indicator --}}
                                            @if(($selectedFlight['seats'] ?? 0) > 0)
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                    <span class="text-[11px] font-black text-orange-500 uppercase tracking-tighter">{{ $selectedFlight['seats'] }} Seats Left</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($idx < count($selectedFlight['itineraries']) - 1)
                                        <div class="relative py-2">
                                            <div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-t border-gray-50"></div></div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Right Content: Quick Price Detail --}}
                    <div class="lg:w-[17%] bg-gray-50/40 flex flex-col items-center justify-center p-6 text-center border-t lg:border-t-0 lg:border-l border-gray-100 ring-1 ring-white ring-inset rounded-b-xl lg:rounded-b-none lg:rounded-r-xl">
                        <div class="mb-2 space-y-0.5">
                            <div class="text-[10px] font-black text-[#2ab4c0] uppercase tracking-[0.25em] mb-1.5">Selected Offer</div>
                            <div class="flex items-baseline justify-center gap-0.5 group/price relative cursor-help">
                                <span class="text-sm font-black text-gray-400 uppercase tracking-tighter">{{ $currencyCode }}</span>
                                <span class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($selectedFlight['price'] ?? 0) }}</span>
                                
                                {{-- Price Breakdown Tooltip --}}
                                 @if(isset($selectedFlight['priceBreakdown']) && isset($selectedFlight['priceBreakdown']['base']) && isset($selectedFlight['priceBreakdown']['taxes']))
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/price:block w-40 bg-gray-900 text-white text-[10px] rounded-lg p-2.5 shadow-xl z-[110]">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-400">Base Fare</span>
                                            <span class="font-bold">{{ $currencyCode }}{{ number_format($selectedFlight['priceBreakdown']['base'], 2) }}</span>
                                        </div>
                                        <div class="flex justify-between border-t border-white/10 pt-1">
                                            <span class="text-gray-400">Taxes &amp; Fees</span>
                                            <span class="font-bold">{{ $currencyCode }}{{ number_format($selectedFlight['priceBreakdown']['taxes'], 2) }}</span>
                                        </div>
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap justify-center gap-1 mt-2">
                                @if($selectedFlight['pricingOptions']['refundable'] ?? false)
                                    <span class="px-1.5 py-0.5 rounded bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-tighter border border-green-100">Refundable</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-tighter border border-gray-100">Non-Refundable</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fare Panel --}}
                <div x-show="fareOpen" style="display: none;" class="border-t border-gray-100 p-6 bg-gradient-to-b from-gray-50/50 to-white">
                    @if(empty($availableFares))
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-8 text-center">
                            <svg class="w-12 h-12 text-[#2ab4c0]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-sm font-bold text-gray-800 mb-1">Standard Basic Fare</h3>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">No branded upgrade packages are available for this specific flight route. Your current selection includes standard amenities.</p>
                        </div>
                    @else
                        <div class="flex items-center gap-6 text-sm font-bold text-gray-400 border-b border-gray-200 mb-6 px-1">
                            @foreach($availableFares as $cabinCode => $fares)
                                @php $lowerCode = strtolower($cabinCode); @endphp
                                <button type="button" class="pb-3 relative transition-all"
                                        @click="cabin='{{ $lowerCode }}'"
                                        :class="cabin==='{{ $lowerCode }}' ? 'text-gray-900 active-fare-tab' : 'hover:text-gray-600'">
                                    {{ ucfirst(str_replace('_', ' ', $lowerCode)) }}
                                    <div x-show="cabin==='{{ $lowerCode }}'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#2ab4c0]"></div>
                                </button>
                            @endforeach
                        </div>

                        @foreach($availableFares as $cabinCode => $fares)
                            @php 
                                $lowerCode = strtolower($cabinCode); 
                                $headerBg = match($lowerCode) {
                                    'economy' => 'bg-emerald-600',
                                    'premium_economy' => 'bg-teal-600',
                                    'business' => 'bg-indigo-600',
                                    'first' => 'bg-red-700',
                                    default => 'bg-[#2ab4c0]'
                                };
                            @endphp
                            <div x-show="cabin === '{{ $lowerCode }}'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                @foreach($fares as $index => $fare)
                                    <div class="group rounded-2xl bg-white border-2 {{ $selectedFareName === $fare['name'] ? 'border-[#2ab4c0] ring-4 ring-[#2ab4c0]/10 shadow-lg' : 'border-gray-100 hover:border-gray-200 shadow-sm' }} overflow-hidden flex flex-col transition-all">
                                        <div class="{{ $headerBg }} text-white p-4 relative overflow-hidden">
                                            <div class="absolute -right-4 -top-4 w-12 h-12 bg-white/10 rounded-full blur-xl"></div>
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/80 mb-1">{{ $fare['name'] }}</p>
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-2xl font-black">{{ $currencyCode }}{{ number_format($fare['price']) }}</span>
                                                <span class="text-[10px] font-medium text-white/70">Total price</span>
                                            </div>
                                        </div>
                                        <div class="p-5 flex-1 space-y-3">
                                            <div class="flex items-start gap-2.5">
                                                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700">{{ $fare['bags'] }}</p>
                                            </div>
                                            @foreach($fare['amenities'] as $amenity)
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-600">{{ $amenity }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="p-5 pt-0">
                                            <button type="button" 
                                                    wire:click="selectFare('{{ $cabinCode }}', {{ $index }})"
                                                    class="w-full py-2.5 rounded-xl text-xs font-bold transition-all {{ $selectedFareName === $fare['name'] ? 'bg-[#2ab4c0] text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                {{ $selectedFareName === $fare['name'] ? 'Current Selection' : 'Choose Package' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Ancillary Services Cards (Dynamic Placeholder for Baggage/Lounge) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                 {{-- Dynamic Ancillaries from API --}}
                 @foreach($availableAncillaries as $anc)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-md p-5 flex items-center gap-5 group transition-all hover:shadow-lg">
                        <div class="w-16 h-16 rounded-2xl bg-[#2ab4c0]/5 text-[#2ab4c0] flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">{{ ucwords(strtolower($anc['description'])) }}</h4>
                            <p class="text-xs text-gray-500 mb-2">{{ $anc['amenityType'] === 'PRE_RESERVED_SEAT_DURING_BOOKING' ? 'Choose prefered seats during checkout.' : 'Available for this flight.' }}</p>
                            <button wire:click="toggleAncillary('{{ $anc['code'] }}')"
                                    class="text-xs font-bold px-4 py-1.5 rounded-lg transition-all {{ isset($selectedAncillaries[$anc['code']]) ? 'bg-[#2ab4c0] text-white shadow-md' : 'bg-gray-100 text-[#2ab4c0] hover:bg-gray-200' }}">
                                {{ isset($selectedAncillaries[$anc['code']]) ? 'Added' : 'Add Service' }}
                            </button>
                        </div>
                    </div>
                 @endforeach
            </div>

        </div>

        {{-- ── RIGHT: Premium Summary Sidebar ── --}}
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-200/40 overflow-hidden sticky top-6">
                {{-- Summary Header --}}
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </span>
                        Price Summary
                    </h3>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Line Items --}}
                    @foreach ($summaryItems as $i => $item)
                        <div class="flex items-start justify-between gap-3 group">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight leading-tight truncate">{{ $item['label'] }}</span>
                                @if ($item['removable'])
                                    <button wire:click="removeItem({{ $i }})" class="text-[10px] font-bold text-red-400 hover:text-red-600 flex items-center gap-0.5 mt-0.5">
                                        Remove
                                    </button>
                                @endif
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $currencyCode }}{{ number_format($item['amount'], 2) }}</span>
                        </div>
                    @endforeach

                    {{-- Total Section --}}
                    <div class="pt-5 mt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Final Total</span>
                            <span class="text-gray-900 text-2xl font-black">{{ $currencyCode }}{{ number_format($this->total, 2) }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium">Prices include all taxes and carrier-imposed fees.</p>
                    </div>

                    {{-- Primary Action Buttons --}}
                    <div class="space-y-3 pt-2">
                        <button wire:click="continue" wire:loading.attr="disabled"
                                class="w-full py-4 bg-[#2ab4c0] text-white font-black rounded-2xl hover:bg-[#2399a3] shadow-lg shadow-[#2ab4c0]/30 transition-all flex items-center justify-center gap-3 group">
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-3">
                                Review Seating
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-3">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Syncing...
                            </span>
                        </button>

                        <button wire:click="back" class="w-full py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back to Flight List
                        </button>
                    </div>
                </div>

                {{-- Trust Badges --}}
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-center gap-4">
                    <div class="flex items-center gap-1 opacity-40">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Safe</span>
                    </div>
                    <div class="flex items-center gap-1 opacity-40">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Secure</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .active-fare-tab {
            color: #2ab4c0 !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
