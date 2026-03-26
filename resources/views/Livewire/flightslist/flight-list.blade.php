<div class="relative" wire:init="$dispatch('loadFlights'); $dispatch('loadDateRailPrices')">

    {{-- Explicitly Styled Top-Center Loader for Debugging --}}
    <div
        wire:loading
        style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1000000;"
        class="flex items-center justify-center"
        aria-live="polite"
        aria-busy="true"
    >
        <div class="flex items-center gap-3 rounded-full bg-white px-8 py-4 shadow-[0_10px_40px_rgba(0,0,0,0.2)] border-2 border-[#2ab4c0] ring-4 ring-[#2ab4c0]/20">
            <svg class="animate-spin w-8 h-8 text-[#2ab4c0]" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="flex flex-col">
                <span class="text-[16px] font-black text-gray-900 tracking-tight whitespace-nowrap uppercase leading-none">
                    Searching for flights
                </span>
                <span class="text-[10px] text-[#2ab4c0] font-bold mt-1 animate-pulse">
                    Please wait while we find your flights...
                </span>
            </div>
        </div>
    </div>

{{-- ══════════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════════ --}}
<div class="w-full py-4 flex flex-col lg:flex-row gap-6">

    {{-- ─── MAIN RESULTS ────────────────────────────────────── --}}
    <main class="flex-1 min-w-0">

        {{-- Outbound header --}}
        <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-3 flex items-center justify-between flex-wrap gap-2" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                @if ($isMulti && count($multiFlights) > 0)
                    <p class="text-base font-bold text-white">Multi-City Trip</p>
                    <p class="text-xs font-medium text-white/90">{{ count($multiFlights) }} Flights:
                        {{ $multiFlights[0]['dep'] }} →
                        {{ $multiFlights[count($multiFlights) - 1]['arr'] }}</p>
                @elseif($tripType === 'return')
                    <p class="text-base font-bold text-white">{{ $origin }} → {{ $destination }}</p>
                    <p class="text-xs font-medium text-white/90">Return Trip</p>
                @elseif($tripType === 'oneway')
                    <p class="text-base font-bold text-white">{{ $origin }} → {{ $destination }}</p>
                    <p class="text-xs font-medium text-white/90">One-way Trip</p>
                @else
                    <p class="text-base font-bold text-white">{{ $origin }} → {{ $destination }}</p>
                @endif
            </div>
            <div class="text-right">
                @if ($isMulti && count($multiFlights) > 0)
                    <p class="text-xs font-medium text-white/90">First Departure</p>
                    <p class="text-base font-bold text-white">
                        {{ \Carbon\Carbon::parse($multiFlights[0]['date'])->format('l d.m.Y') }}</p>
                @else
                    <p class="text-xs font-medium text-white/90">Departure Date</p>
                    <p class="text-base font-bold text-white">
                        {{ \Carbon\Carbon::parse($departDate)->format('l d.m.Y') }}</p>
                @endif
            </div>
        </div>

        {{-- Sort tabs + date rail --}}
        <div class="bg-white rounded-xl border border-gray-200 mb-3 overflow-hidden">
            <style>
                .tab {
                    position: relative;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                }
                .tab.active {
                    color: #2ab4c0 !important;
                    font-weight: 800 !important;
                }
                .tab.active::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 20%;
                    right: 20%;
                    height: 2px;
                    background: #2ab4c0;
                    border-radius: 2px;
                }
                .tab:hover:not(.active) {
                    background: #f9fafb;
                }
            </style>

            {{-- Sort tabs --}}
            <div class="flex items-center bg-white border-b border-gray-200 px-2 gap-1 overflow-x-auto overflow-y-hidden no-scrollbar relative">
                {{-- Sorting Loading Indicator --}}
                <div wire:loading wire:target="search,clearFilters" class="absolute inset-x-0 bottom-0 h-1 bg-[#2ab4c0]/20 overflow-hidden z-50">
                    <div class="h-full bg-[#2ab4c0] sliding-loader" style="width: 35%; background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);"></div>
                </div>

                <style>
                    .sliding-loader {
                        animation: sliding-loading 1s infinite linear;
                    }
                    @keyframes sliding-loading {
                        from { transform: translateX(-100%); }
                        to { transform: translateX(300%); }
                    }
                </style>

                @foreach([
                        'best' => 'Best Value',
                        'cheap' => 'Cheapest',
                        'fastest' => 'Fastest',
                        'early' => 'Early Depart',
                        'late' => 'Late Depart',
                    ] as $key => $label)
                        <button wire:click="setSort('{{ $key }}')"
                                class="tab px-3 py-2.5 text-xs text-gray-500 hover:text-gray-700 whitespace-nowrap {{ $sortTab === $key ? 'active' : '' }}">
                            {{ $label }}
                        </button>
                @endforeach

		                <div class="ml-auto flex-shrink-0 flex items-center gap-2">
		                    <span id="itinerary-layout-label"
		                          class="inline-flex items-center gap-1.5 text-xs font-semibold whitespace-nowrap select-none text-gray-500">
		                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
		                            <rect x="3" y="6" width="8" height="12" rx="2" stroke="currentColor" stroke-width="2" />
		                            <rect x="13" y="6" width="8" height="12" rx="2" stroke="currentColor" stroke-width="2" />
		                        </svg>
		                        Side-by-side view
		                    </span>

	                    <button type="button" wire:click="toggleItineraryLayout" role="switch" aria-labelledby="itinerary-layout-label"
	                            aria-checked="{{ $itineraryLayoutVertical ? 'true' : 'false' }}"
	                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full border-0 p-0.5 transition-colors focus:outline-none {{ $itineraryLayoutVertical ? 'bg-[#2ab4c0]' : 'bg-gray-200' }}">
	                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform {{ $itineraryLayoutVertical ? 'translate-x-5' : 'translate-x-0' }}"
	                              aria-hidden="true"></span>
	                    </button>
	                </div>
            </div>

            {{-- Date rail --}}
            @if(!$isMulti)
            <div class="flex items-center px-4 py-3 border-b border-gray-100 bg-gray-50/70 relative">
                {{-- Global loading overlay for date rail --}}
                {{-- <div wire:loading wire:target="fetchDateRailPrices,shiftDate,selectDate" class="absolute inset-0 bg-white/40 backdrop-blur-[1px] z-10 flex items-center justify-center">
                    <div class="flex gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0] animate-bounce [animation-delay:-0.3s]"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0] animate-bounce [animation-delay:-0.15s]"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0] animate-bounce"></div>
                    </div>
                </div> --}}
                <button wire:click="shiftDate(-1)" wire:loading.attr="disabled"
                        class="w-8 h-8 flex items-center justify-center bg-white border border-gray-100 rounded-full shadow-sm text-gray-400 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/20 hover:shadow-md transition-all flex-shrink-0 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex flex-1 justify-between px-2 py-1 overflow-x-auto no-scrollbar">
                    @foreach($dateRail as $day)
                                        <button wire:key="date-{{ $day['date'] }}"
                                                wire:click="selectDate('{{ $day['date'] }}')"
                                                class="flex-shrink-0 text-center px-4 py-1.5 rounded-xl cursor-pointer transition-all duration-200 border
                                                       {{ $selectedDate === $day['date']
                        ? 'bg-[#2ab4c0] border-[#2ab4c0] shadow-md shadow-[#2ab4c0]/20 scale-105'
                        : 'bg-white border-gray-100 shadow-sm hover:border-gray-200 hover:shadow-md' }}">
                                            <p class="text-[10px] font-medium uppercase tracking-wider {{ $selectedDate === $day['date'] ? 'text-white' : 'text-gray-400' }}">
                                                {{ $day['label'] }}
                                            </p>
                                            @if($day['price'])
                                                <p class="text-xs font-bold mt-0.5 {{ $selectedDate === $day['date'] ? 'text-white' : ($day['price'] <= (collect($dateRail)->whereNotNull('price')->min('price') ?: 0) ? 'text-green-600' : 'text-gray-900') }}">
                                                    {{ $day['price'] }}
                                                </p>
                                            @else
                                                <div class="h-4 flex items-center justify-center mt-0.5">
                                                    @if($loadingPrices)
                                                        <div class="flex gap-0.5">
                                                            <div class="w-1 h-1 rounded-full bg-gray-300 animate-pulse"></div>
                                                            <div class="w-1 h-1 rounded-full bg-gray-300 animate-pulse" style="animation-delay: 0.2s"></div>
                                                            <div class="w-1 h-1 rounded-full bg-gray-300 animate-pulse" style="animation-delay: 0.4s"></div>
                                                        </div>
                                                    @else
                                                        <p class="text-xs font-bold {{ $selectedDate === $day['date'] ? 'text-white/40' : 'text-gray-300' }}">-</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </button>
                    @endforeach
                </div>

                <button wire:click="shiftDate(1)" wire:loading.attr="disabled"
                        class="w-8 h-8 flex items-center justify-center bg-white border border-gray-100 rounded-full shadow-sm text-gray-400 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/20 hover:shadow-md transition-all flex-shrink-0 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            @endif

            {{-- Flight list --}}
            <div class="space-y-4 p-4" wire:loading.class="opacity-50" wire:target="loadFlights">
                @forelse($this->flights as $flight)
                    <div wire:key="flight-{{ $flight['id'] }}" class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-5 relative hover:z-50" x-data="{ open: false }">
                        <div class="flex flex-col lg:flex-row">
                            {{-- Main Content Area (83%) --}}
                            <div class="lg:w-[83%] flex-1 border-r border-gray-100 p-4 sm:p-5">
                                <div class="flex sm:flex-row">
                                    {{-- Flight Details Column --}}
                                    <div class="flex-1 sm:pl-5 space-y-1">
                                        @php $itineraries = $flight['itineraries'] ?? []; @endphp

                                        @if($itineraryLayoutVertical && count($itineraries) >= 2)
                                            {{-- Vertical layout: One-way | separator | Return --}}
                                            <div class="grid grid-cols-2 divide-x divide-gray-200 items-stretch min-h-[120px]">
                                                {{-- Left: One-way --}}
                                                <div class="  flex flex-col pr-4">
                                                    <p class="text-[10px] font-black text-[#2ab4c0] uppercase tracking-widest mb-2">One-way</p>
                                                    @php $itin = $itineraries[0]; @endphp
                                                    @include('livewire.flightslist.partials.itinerary-leg', ['flight' => $flight, 'itin' => $itin])
                                                </div>
                                                {{-- Center: Separator --}}
                                                {{-- <div class="flex flex-col items-center justify-center px-3">
                                                    <div class="w-px h-full min-h-[80px] bg-gray-200 rounded-full" aria-hidden="true"></div>
                                                </div> --}}
                                                {{-- Right: Return --}}
                                                <div class=" flex flex-col pl-4">
                                                    <p class="text-[10px] font-black text-[#2ab4c0] uppercase tracking-widest mb-2">Return</p>
                                                    @php $itin = $itineraries[1]; @endphp
                                                    @include('livewire.flightslist.partials.itinerary-leg', ['flight' => $flight, 'itin' => $itin])
                                                </div>
                                            </div>
                                        @elseif($itineraryLayoutVertical && count($itineraries) === 1)
                                            {{-- Vertical layout but only one leg: show One-way left, separator, empty right --}}
                                            <div class="grid grid-cols-[1fr_auto_1fr] gap-0 items-stretch min-h-[120px]">
                                                <div class="pr-4 border-r border-gray-200 flex flex-col">
                                                    <p class="text-[10px] font-black text-[#2ab4c0] uppercase tracking-widest mb-2">One-way</p>
                                                    @include('livewire.flightslist.partials.itinerary-leg', ['flight' => $flight, 'itin' => $itineraries[0]])
                                                </div>
                                                <div class="flex flex-col items-center justify-center px-4">
                                                    <div class="w-px h-full min-h-[80px] bg-gray-200 rounded-full"></div>
                                                </div>
                                                <div class="pl-4 flex flex-col justify-center text-gray-400 text-xs font-medium">—</div>
                                            </div>
                                        @else
                                            {{-- Horizontal layout (default) --}}
                                            @foreach($itineraries as $idx => $itin)
                                                <div class="flex flex-col sm:grid sm:grid-cols-4 items-center gap-4 sm:gap-6">
                                                    <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                                        <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 shadow-sm">
                                                            <img src="https://www.gstatic.com/flights/airline_logos/70px/{{ $flight['airlineCode'] }}.png"
                                                                alt="{{ $flight['airline'] }}"
                                                                onerror="this.src='https://pics.avs.io/128/128/{{ $flight['airlineCode'] }}.png'"
                                                                class="w-full h-full object-contain">
                                                        </div>
                                                        <span class="text-[10px] font-bold text-gray-500 text-center uppercase tracking-tighter leading-none">{{ $flight['airline'] }}</span>
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                                            <span>{{ $itin['dep'] }}</span>
                                                            <span class="text-gray-300 font-light">–</span>
                                                            <span>{{ $itin['arr'] }}</span>
                                                            @if(isset($itin['daysNext']) && $itin['daysNext'] > 0)
                                                                <sup class="text-[#2ab4c0] text-[10px] font-bold">+{{ $itin['daysNext'] }}</sup>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                                            <span title="{{ $itin['depCity'] }}" class="cursor-help border-b border-dotted border-gray-300">{{ $itin['depAirport'] }}</span>
                                                            <span class="text-gray-300 font-normal ml-0.5">–</span>
                                                            <span title="{{ $itin['arrCity'] }}" class="cursor-help border-b border-dotted border-gray-300 ml-0.5">{{ $itin['arrAirport'] }}</span>
                                                            <span class="mx-1 text-gray-300">|</span>
                                                            <span class="text-gray-500">{{ $itin['flightNumber'] }}</span>
                                                            <span class="mx-1 text-gray-300">|</span>
                                                            <span class="text-[10px] text-gray-400 font-medium">{{ $itin['aircraft'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="w-full text-center py-2 sm:py-0 border-y sm:border-y-0 border-gray-50">
                                                        <div class="text-sm font-black text-gray-800">{{ $itin['duration'] }}</div>
                                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">{{ $itin['stops'] }}</div>
                                                        @if(isset($itin['technicalStops']) && $itin['technicalStops'] > 0)
                                                            <div class="mt-1 flex items-center justify-center gap-1 text-[9px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full ring-1 ring-red-100 uppercase tracking-tighter">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                {{ $itin['technicalStops'] }} Tech Stop{{ $itin['technicalStops'] > 1 ? 's' : '' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="w-full flex flex-col items-center justify-center gap-1.5 group relative">
                                                        <div class="flex gap-4 text-gray-400">
                                                            <div class="cursor-pointer">
                                                                <div class="flex gap-1.5 group-hover:text-[#2ab4c0] transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                                </div>
                                                                @php $allFlightAmenities = collect($flight['itineraries'] ?? [])->pluck('amenities')->flatten()->unique()->values(); @endphp
                                                                <div class="absolute top-full right-0 mt-3 hidden group-hover:block w-52 bg-white border border-gray-100 text-gray-600 shadow-2xl rounded-xl z-[100] overflow-hidden ring-1 ring-black/5">
                                                                    <div class="p-3 bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase tracking-widest">Flight Amenities</div>
                                                                    <div class="p-3 space-y-2">
                                                                        @foreach($allFlightAmenities as $am)
                                                                            <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                                                                <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                                                {{ $am }}
                                                                            </div>
                                                                        @endforeach
                                                                        <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                                                            <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                                            Baggage: {{ $itin['baggage'] ?? 'Included' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="absolute bottom-full right-4 -mb-1 border-4 border-transparent border-b-white"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(isset($flight['seats']) && $flight['seats'] > 0)
                                                            <div class="flex items-center gap-1">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                                <span class="text-[11px] font-black text-orange-500 uppercase tracking-tighter">{{ $flight['seats'] }} Seats Left</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($idx < count($itineraries) - 1)
                                                    <div class="relative py-2">
                                                        <div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-t border-gray-50"></div></div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Price & CTA Sidebar (17%) --}}
                            <div class="lg:w-[17%] bg-gray-50/40 flex flex-col items-center justify-center p-6 text-center border-t lg:border-t-0 lg:border-l border-gray-100 ring-1 ring-white ring-inset rounded-b-xl lg:rounded-b-none lg:rounded-r-xl">
                                <div class="mb-5 space-y-0.5">
                                    <div class="text-[10px] font-black text-[#2ab4c0] uppercase tracking-[0.25em] mb-1.5">{{ $flight['badge'] ?: 'Best Value' }}</div>
                                    <div class="flex items-baseline justify-center gap-0.5 group/price relative cursor-help">
                                        <span class="text-sm font-black text-gray-400 uppercase tracking-tighter">{{ $currencyCode }}</span>
                                        <span class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tighter">{{ $flight['price'] }}</span>

                                        {{-- Price Breakdown Tooltip --}}
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/price:block w-40 bg-gray-900 text-white text-[10px] rounded-lg p-2.5 shadow-xl z-50">
                                            <div class="flex justify-between mb-1">
                                                <span class="text-gray-400">Base Fare</span>
                                                <span class="font-bold">{{ $currencyCode }}{{ $flight['priceBreakdown']['base'] }}</span>
                                            </div>
                                            <div class="flex justify-between border-t border-white/10 pt-1">
                                                <span class="text-gray-400">Taxes & Fees</span>
                                                <span class="font-bold">{{ $currencyCode }}{{ $flight['priceBreakdown']['taxes'] }}</span>
                                            </div>
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                        </div>
                                    </div>

                                    {{-- Fare Badges --}}
                                    <div class="flex flex-wrap justify-center gap-1 mt-2">
                                        @if($flight['refundable'])
                                            <span class="px-1.5 py-0.5 rounded bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-tighter border border-green-100">Refundable</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-tighter border border-gray-100">Non-Refundable</span>
                                        @endif

                                        @if($flight['flexible'])
                                            <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-tighter border border-blue-100">Flexible</span>
                                        @endif
                                    </div>
                                </div>

                                <button wire:click="selectFlight('{{ $flight['id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="selectFlight('{{ $flight['id'] }}')"

                                        class="w-full bg-[#2ab4c0] hover:bg-[#239ba6] text-white text-[13px] font-black uppercase tracking-widest py-3.5 rounded-xl shadow-xl shadow-[#2ab4c0]/20 transition-all duration-200 hover:-translate-y-1 active:scale-[0.98] disabled:opacity-50 relative overflow-hidden group flex items-center justify-center"
                                        aria-label="Select flight">
                                    <span wire:loading.remove wire:target="selectFlight('{{ $flight['id'] }}')">Select</span>
                                    <svg wire:loading wire:target="selectFlight('{{ $flight['id'] }}')" class="animate-spin w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </button>


                            </div>
                        </div>
                    </div>
                @empty
                    @if(!$isLoading && !$isInitialLoad)
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <p class="text-sm font-medium">{{ $this->errorMessage ?: 'No flights match your filters' }}</p>
                        <button wire:click="clearFilters" class="mt-2 text-xs text-blue-500 hover:underline">Clear all filters</button>
                    </div>
                    @endif
                @endforelse

            </div>

        </div>
    </main>
    {{-- Move script inside root to avoid multiple root elements --}}
    <script>
        window.singleDatePicker = function (opts) {
            // ... (rest of the script content)
            const pad = (n) => String(n).padStart(2, '0');
            const toIso = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
            const parseIso = (iso) => {
                if (!iso) return null;
                const [y, m, d] = iso.split('-').map(Number);
                return new Date(y, (m || 1) - 1, d || 1);
            };
            const fmt = (iso) => {
                const d = parseIso(iso);
                if (!d) return '';
                return `${pad(d.getMonth() + 1)}/${pad(d.getDate())}/${d.getFullYear()}`;
            };
            const startOfDay = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());

            return {
                open: false,
                title: opts?.title || 'Select date',
                display: opts?.value ? fmt(opts.value) : '',
                iso: opts?.value || '',
                flexible: !!opts?.flexible,
                wireValueKey: opts?.wireValueKey || '',
                wireFlexibleKey: opts?.wireFlexibleKey || '',
                base: null,
                months: [],
                minIso: toIso(startOfDay(new Date())),

                init() {
                    const baseIso = this.iso || this.minIso;
                    const baseDate = parseIso(baseIso) || new Date();
                    this.base = new Date(baseDate.getFullYear(), baseDate.getMonth(), 1);
                    this.months = [];
                    this.refreshMonths();
                },

                toggleFlexible() {
                    this.flexible = !this.flexible;
                    if (this.$wire && this.wireFlexibleKey) this.$wire.$set(this.wireFlexibleKey, this.flexible);
                },

                prevMonth() {
                    const today = new Date();
                    const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    const prev = new Date(this.base.getFullYear(), this.base.getMonth() - 1, 1);
                    if (prev >= currentMonth) {
                        this.base = prev;
                        this.refreshMonths();
                    }
                },

                nextMonth() {
                    this.base = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
                    this.refreshMonths();
                },

                refreshMonths() {
                    const m1 = this.buildMonth(this.base.getFullYear(), this.base.getMonth());
                    this.months = [m1];
                },

                buildMonth(year, monthIndex) {
                    const monthStart = new Date(year, monthIndex, 1);
                    const monthEnd = new Date(year, monthIndex + 1, 0);
                    const daysInMonth = monthEnd.getDate();
                    const jsDow = monthStart.getDay(); // 0=Sun
                    const offset = (jsDow + 6) % 7; // 0=Mon

                    const title = monthStart.toLocaleString(undefined, { month: 'long', year: 'numeric' });
                    const cells = [];

                    for (let i = 0; i < offset; i++) {
                        cells.push({ key: `${year}-${monthIndex}-blank-${i}`, day: null, iso: null, disabled: true });
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const d = new Date(year, monthIndex, day);
                        const iso = toIso(d);
                        cells.push({
                            key: iso,
                            day,
                            iso,
                            disabled: iso < this.minIso,
                        });
                    }

                    while (cells.length < 42) {
                        cells.push({ key: `${year}-${monthIndex}-tail-${cells.length}`, day: null, iso: null, disabled: true });
                    }

                    return { key: `${year}-${monthIndex}`, title, cells };
                },

                pick(iso) {
                    if (!iso || iso < this.minIso) return;
                    this.iso = iso;
                    this.display = fmt(this.iso);
                },

                apply() {
                    if (!this.$wire || !this.wireValueKey) return;
                    this.$wire.$set(this.wireValueKey, this.iso);
                    if (this.wireFlexibleKey) this.$wire.$set(this.wireFlexibleKey, this.flexible);
                },

                dayClass(cell) {
                    if (!cell.day) return 'text-transparent';
                    if (cell.disabled) return 'text-gray-300 cursor-not-allowed';
                    if (this.iso && cell.iso === this.iso) return 'bg-[#2ab4c0] text-white';
                    return 'text-gray-900 hover:bg-gray-100';
                },
            };
        }
    </script>
</div>
