<div>

{{-- ══════════════════════════════════════════════════════════
     TOP NAV
══════════════════════════════════════════════════════════ --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-12">

        <div class="flex items-center gap-6">
            <button class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
            </button>
            <button class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white text-xs font-bold">U</div>

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Login / Register
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════════
     STEP BAR
══════════════════════════════════════════════════════════ --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center text-xs text-gray-500 py-1.5 gap-2">
            <span>Home</span><span>/</span><span>Flight Tickets</span><span>/</span>
            <span>{{ $origin }} – {{ $destination }}</span>
        </div>
        <div class="flex items-center overflow-x-auto">
            <div class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-t whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                Select Flight
            </div>
            @foreach(['Passenger Details','Additional Services','Choice Next'] as $step)
                <div class="px-4 py-2 text-gray-400 text-xs whitespace-nowrap">{{ $step }}</div>
            @endforeach
            <div class="px-4 py-2 text-gray-400 text-xs ml-auto whitespace-nowrap">Payment</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SEARCH BAR
══════════════════════════════════════════════════════════ --}}
<div class="bg-blue-600 py-3">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-3 flex-wrap">

        {{-- Origin --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
            </svg>
            <input wire:model.blur="origin"
                   class="bg-transparent font-medium placeholder-white/70 focus:outline-none text-sm w-52"
                   value="{{ $origin }}">
        </div>

        {{-- Swap button --}}
        <button wire:click="$set('origin', '{{ $destination }}')" class="text-white/70 hover:text-white transition-colors hidden sm:block">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </button>

        {{-- Destination --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
            </svg>
            <input wire:model.blur="destination"
                   class="bg-transparent font-medium placeholder-white/70 focus:outline-none text-sm w-52"
                   value="{{ $destination }}">
        </div>

        {{-- Depart Date --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <input wire:model.blur="departDate" type="date"
                   class="bg-transparent text-sm text-white focus:outline-none cursor-pointer"
                   value="{{ $departDate }}">
        </div>

        {{-- Return Date --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <input wire:model.blur="returnDate" type="date"
                   class="bg-transparent text-sm text-white focus:outline-none cursor-pointer"
                   value="{{ $returnDate }}">
        </div>

        {{-- Passengers --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm">
            <svg class="w-4 h-4 opacity-70" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <select wire:model.live="passengers" class="bg-transparent focus:outline-none cursor-pointer text-white">
                @for($i = 1; $i <= 9; $i++)
                    <option value="{{ $i }}" class="text-gray-800">{{ $i }} {{ $i === 1 ? 'Passenger' : 'Passengers' }}</option>
                @endfor
            </select>
        </div>

        <button wire:click="search"
                wire:loading.attr="disabled"
                class="ml-auto px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
            <span wire:loading.remove wire:target="search">Search</span>
            <span wire:loading wire:target="search" class="flex items-center gap-1.5">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Searching...
            </span>
        </button>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 py-4 flex gap-4">

    {{-- ─── SIDEBAR FILTERS ─────────────────────────────────── --}}
    <aside class="w-56 flex-shrink-0 hidden lg:block">

        {{-- Price Alert card --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-3">
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">Setup Price Alert</p>
                    <p class="text-xs text-gray-500 mt-0.5">Get notified when prices change for this route</p>
                </div>
            </div>
            <button class="mt-2 w-full py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Pricing Table
            </button>
        </div>

        {{-- Filter panel --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-3 py-2.5 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </span>
                <div class="flex gap-2 text-xs">
                    <button class="text-gray-500 hover:text-gray-700">Save filter</button>
                    <span class="text-gray-300">|</span>
                    <button wire:click="clearFilters" class="text-blue-500 hover:text-blue-700">Clear all</button>
                </div>
            </div>

            {{-- Price filter --}}
            <div class="filter-section px-3 py-3">
                <p class="text-xs font-semibold text-gray-600 mb-2">Price Filter</p>
                <div class="flex gap-2 mb-2">
                    <input wire:model.live="priceMin" type="number" placeholder="Min"
                           class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400">
                    <input wire:model.live="priceMax" type="number" placeholder="Max"
                           class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400">
                </div>
                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <span>${{ $priceMin }}</span>
                    <input wire:model.live="priceMax" type="range" min="100" max="1500" class="w-full h-1.5 flex-1">
                    <span>${{ $priceMax }}</span>
                </div>
            </div>

            {{-- Stops --}}
            <x-flight-search.filter-section title="Stops">
                @foreach(['any' => 'Any', 'direct' => 'Direct', '1stop' => '1 Stop', '2stop' => '2+ Stops'] as $val => $label)
                    <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-600">
                        <input wire:model.live="stops" type="checkbox" value="{{ $val }}"
                               class="rounded text-blue-600">
                        {{ $label }}
                    </label>
                @endforeach
            </x-flight-search.filter-section>

            {{-- Airlines --}}
            <x-flight-search.filter-section title="Airlines">
                @foreach(['any' => 'Any', 'tk' => 'Turkish Airlines', 'pc' => 'Pegasus', 'lh' => 'Lufthansa'] as $val => $label)
                    <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-600">
                        <input wire:model.live="airlines" type="checkbox" value="{{ $val }}"
                               class="rounded text-blue-600">
                        {{ $label }}
                    </label>
                @endforeach
            </x-flight-search.filter-section>

            {{-- Departure Times --}}
            <x-flight-search.filter-section title="Depart" :border="false">
                @foreach(['any' => 'Any', '0-6' => '0 – 6 Hrs', '6-12' => '6 – 12 Hrs', '12-18' => '12 – 18 Hrs', '18-24' => '18 – 24 Hrs'] as $val => $label)
                    <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-600">
                        <input wire:model.live="departTimes" type="checkbox" value="{{ $val }}"
                               class="rounded text-blue-600">
                        {{ $label }}
                    </label>
                @endforeach
            </x-flight-search.filter-section>
        </div>
    </aside>

    {{-- ─── MAIN RESULTS ────────────────────────────────────── --}}
    <main class="flex-1 min-w-0">

        {{-- Outbound header --}}
        <div class="bg-blue-600 text-white rounded-xl px-4 py-3 mb-3 flex items-center justify-between flex-wrap gap-2">
            <div>
                <p class="text-xs opacity-75 mb-0.5">Select Outbound Flight</p>
                <p class="text-sm font-semibold">{{ $origin }} → {{ $destination }}</p>
                <p class="text-xs opacity-75 mt-0.5">After selecting your outbound flight you will be directed to select your return flight</p>
            </div>
            <div class="text-right">
                <p class="text-xs opacity-75">{{ $origin }} → {{ $destination }}</p>
                <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($departDate)->format('l d.m.Y') }}</p>
            </div>
        </div>

        {{-- Sort tabs + date rail --}}
        <div class="bg-white rounded-xl border border-gray-200 mb-3 overflow-hidden">

            {{-- Sort tabs --}}
            <div class="flex items-center border-b border-gray-100 px-2 gap-1 overflow-x-auto">
                @foreach([
                    'cheap'    => 'Cheap Flights',
                    'recommend'=> 'Recommended',
                    'fastest'  => 'Fastest Route',
                    'dynamic'  => 'Dynamic',
                    'depart'   => 'Sectors Departure',
                    'failure'  => 'Failure Detail',
                    'daily'    => 'Daily / Stay',
                ] as $key => $label)
                    <button wire:click="setSort('{{ $key }}')"
                            class="tab px-3 py-2.5 text-xs text-gray-500 hover:text-gray-700 whitespace-nowrap {{ $sortTab === $key ? 'active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Date rail --}}
            <div class="flex items-center gap-1 px-3 py-2.5 border-b border-gray-100 overflow-x-auto">
                <button class="p-1 text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="flex gap-1 flex-1">
                    @foreach($dateRail as $day)
                        <button wire:click="selectDate('{{ $day['date'] }}')"
                                class="flex-shrink-0 text-center px-3 py-1.5 rounded-lg cursor-pointer transition-colors
                                       {{ $selectedDate === $day['date']
                                           ? 'bg-blue-600'
                                           : 'hover:bg-gray-50' }}">
                            <p class="text-xs {{ $selectedDate === $day['date'] ? 'text-blue-200' : 'text-gray-400' }}">
                                {{ $day['label'] }}
                            </p>
                            <p class="text-xs font-semibold {{ $selectedDate === $day['date'] ? 'text-white' : ($day['price'] <= 175 ? 'text-green-600' : 'text-gray-700') }}">
                                ${{ number_format($day['price'], 2) }}
                            </p>
                        </button>
                    @endforeach
                </div>
                <button class="p-1 text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Flight list --}}
            <div class="divide-y divide-gray-100" wire:loading.class="opacity-50">

                @forelse($this->flights as $flight)
                    <div class="flight-card px-4 py-3 {{ $flight['bgClass'] }}" x-data="{ open: false, cabin: 'economy' }">
                        <div class="flex items-center gap-4 w-full">
                            {{-- Airline logo --}}
                            <div class="w-8 flex-shrink-0">
                                <div class="airline-logo {{ $flight['airlineColor'] }}">{{ $flight['airline'] }}</div>
                            </div>

                            {{-- Route info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    {{-- Departure --}}
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-800">{{ $flight['dep'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $flight['depCity'] }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[120px]">{{ $flight['depAirport'] }}</p>
                                    </div>

                                    {{-- Route line --}}
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 text-xs text-gray-500 relative z-10">
                                                {{ $flight['stops'] }}
                                            </div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-xs text-gray-400">{{ $flight['duration'] }}</p>
                                    </div>

                                    {{-- Arrival --}}
                                    <div class="text-right min-w-0">
                                        <p class="text-sm font-bold text-gray-800">{{ $flight['arr'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $flight['arrCity'] }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[120px]">{{ $flight['arrAirport'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Price & CTA --}}
                            <div class="text-right flex-shrink-0 w-36">
                                <div class="flex items-start justify-end gap-2">
                                    <div>
                                        @if($flight['badge'])
                                            <div class="{{ $flight['badgeClass'] }}">{{ $flight['badge'] }}</div>
                                        @else
                                            <div class="mb-4"></div>
                                        @endif
                                    </div>
                                    <button type="button"
                                            class="mt-0.5 w-7 h-7 rounded-full border border-gray-200 bg-white text-red-600 hover:bg-red-50 flex items-center justify-center"
                                            @click="open = !open"
                                            :aria-expanded="open.toString()">
                                        <svg class="w-4 h-4 transition-transform duration-200"
                                             :class="open ? 'rotate-180' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>

                                <p class="text-lg font-bold text-gray-800">${{ number_format($flight['price'], 2) }}</p>

                                @if(!empty($flight['oldPrice']))
                                    <p class="text-xs text-gray-500 line-through">${{ number_format($flight['oldPrice'], 2) }}</p>
                                @elseif(!empty($flight['note']))
                                    <p class="text-xs text-red-500 font-medium">{{ $flight['note'] }}</p>
                                @else
                                    <p class="text-xs text-gray-400">Per person</p>
                                @endif

                                <a href="{{ route('passenger.details') }}"
                                   class="mt-1 w-full py-1.5 {{ $flight['btnClass'] }} text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1">
                                    Select
                                </a>
                            </div>
                        </div>

                        {{-- Emirates-style packages panel --}}
                        <div x-show="open" x-collapse class="pt-3">
                            <div class="border-t border-gray-100 pt-3">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center gap-4">
                                        <button type="button" class="pb-2 font-medium"
                                                @click="cabin='economy'"
                                                :class="cabin==='economy' ? 'text-gray-900 border-b-2 border-emerald-700' : 'text-gray-400'">Economy</button>
                                        <button type="button" class="pb-2 font-medium"
                                                @click="cabin='business'"
                                                :class="cabin==='business' ? 'text-gray-900 border-b-2 border-emerald-700' : 'text-gray-400'">Business</button>
                                        <button type="button" class="pb-2 font-medium"
                                                @click="cabin='first'"
                                                :class="cabin==='first' ? 'text-gray-900 border-b-2 border-emerald-700' : 'text-gray-400'">First</button>
                                    </div>
                                    <a href="#" class="text-blue-600 hover:underline">Compare all services</a>
                                </div>

                                @php
                                    $basePrice = (float) ($flight['price'] ?? 0);
                                    $saver = $basePrice;
                                    $flex = round($basePrice * 1.1, 2);
                                    $flexPlus = round($basePrice * 1.4, 2);
                                    $businessBase = round($basePrice * 2.2, 2);
                                    $businessSpecial = $businessBase;
                                    $businessSaver = round($businessBase * 1.07, 2);
                                    $businessFlex = round($businessBase * 1.21, 2);
                                    $businessFlexPlus = round($businessBase * 1.41, 2);
                                @endphp

                                {{-- Economy / Premium (Emirates-style cards) --}}
                                <div x-show="cabin === 'economy'" class="mt-4 bg-gray-50 rounded-2xl p-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-stretch">
                                        <div class="hidden lg:block text-[11px] text-gray-600 space-y-4 pt-2">
                                            <p class="text-gray-500">Fare benefits (per person)</p>
                                            <a href="#" class="text-blue-600 hover:underline text-[11px]">Compare all services</a>
                                            <p>Regular seat selection</p>
                                            <p>Baggage</p>
                                            <p>Change fee</p>
                                            <p>Refund fee</p>
                                            <p>Skywards Miles</p>
                                            <p>Upgrade to Business</p>
                                        </div>

                                        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm h-full flex flex-col">
                                        <div class="bg-emerald-800 text-white px-5 py-4 min-h-[88px]">
                                            <p class="text-sm font-semibold">Saver</p>
                                            <p class="text-lg font-extrabold leading-tight">PKR {{ number_format($saver, 0) }}</p>
                                            <p class="text-[11px] text-emerald-100 mt-0.5">Lowest price</p>
                                        </div>
                                        <div class="px-5 py-4 text-xs text-gray-700 flex flex-col gap-2 flex-1">
                                            <p class="text-gray-400">At a charge</p>
                                            <p>2 x 23 kg</p>
                                            <p class="underline decoration-dotted">USD 100.00</p>
                                            <p class="underline decoration-dotted">From USD 150.00</p>
                                            <p>1,400 Miles</p>
                                            <p class="underline decoration-dotted">After check-in opens 90,000 Miles</p>
                                            <a href="{{ route('passenger.details') }}"
                                               class="mt-auto w-full py-2.5 rounded-lg border border-gray-800 text-xs font-semibold bg-white hover:bg-gray-50 flex items-center justify-center">
                                                Select
                                            </a>
                                        </div>
                                    </div>

                                        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm h-full flex flex-col">
                                         <div class="bg-emerald-900 text-white px-5 py-4 min-h-[88px]">
                                            <p class="text-sm font-semibold">Flex</p>
                                            <p class="text-lg font-extrabold leading-tight">PKR {{ number_format($flex, 0) }}</p>
                                        </div>
                                        <div class="px-5 py-4 text-xs text-gray-700 flex flex-col gap-2 flex-1">
                                            <p class="underline decoration-dotted">Complimentary</p>
                                            <p>2 x 23 kg</p>
                                            <p class="underline decoration-dotted">USD 75.00</p>
                                            <p class="underline decoration-dotted">USD 125.00</p>
                                            <p>2,600 Miles</p>
                                            <p class="underline decoration-dotted">Eligible 60,840 Miles</p>
                                            <a href="{{ route('passenger.details') }}"
                                               class="mt-auto w-full py-2.5 rounded-lg border border-gray-800 text-xs font-semibold bg-white hover:bg-gray-50 flex items-center justify-center">
                                                Select
                                            </a>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm h-full flex flex-col">
                                        <div class="bg-emerald-950 text-white px-5 py-4 min-h-[88px]">
                                            <p class="text-sm font-semibold">Flex Plus</p>
                                            <p class="text-lg font-extrabold leading-tight">PKR {{ number_format($flexPlus, 0) }}</p>
                                        </div>
                                        <div class="px-5 py-4 text-xs text-gray-700 flex flex-col gap-2 flex-1">
                                            <p class="underline decoration-dotted">Complimentary</p>
                                            <p>2 x 23 kg</p>
                                            <p class="underline decoration-dotted">Complimentary</p>
                                            <p class="underline decoration-dotted">Complimentary</p>
                                            <p>3,400 Miles</p>
                                            <p class="underline decoration-dotted">Eligible 46,800 Miles</p>
                                            <a href="{{ route('passenger.details') }}"
                                               class="mt-auto w-full py-2.5 rounded-lg border border-gray-800 text-xs font-semibold bg-white hover:bg-gray-50 flex items-center justify-center">
                                                Select
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                {{-- Business --}}
                                <div x-show="cabin === 'business'" class="mt-4 bg-gray-50 rounded-2xl p-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                                        <div class="hidden lg:block text-[11px] text-gray-600 space-y-4 pt-2">
                                            <p class="text-gray-500">Fare benefits (per person)</p>
                                            <a href="#" class="text-blue-600 hover:underline text-[11px]">Compare all services</a>
                                            <p>Chauffeur-drive</p>
                                            <p>Lounge</p>
                                            <p>Seat selection</p>
                                            <p>Baggage</p>
                                            <p>Change fee</p>
                                            <p>Refund fee</p>
                                            <p>Skywards Miles</p>
                                            <p>Upgrade to First</p>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-blue-700 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Special</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($businessSpecial, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="text-gray-400">Info not available</p>
                                                <p class="underline decoration-dotted">Not eligible</p>
                                                <p class="text-gray-400">Restricted</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">USD 250.00</p>
                                                <p class="underline decoration-dotted">From USD 375.00</p>
                                                <p>5,225 Miles</p>
                                                <p class="text-gray-400">Not permitted</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-blue-800 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Saver</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($businessSaver, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="underline decoration-dotted text-gray-400">Not eligible</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>Complimentary</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">USD 200.00</p>
                                                <p class="underline decoration-dotted">From USD 300.00</p>
                                                <p>5,938 Miles</p>
                                                <p class="underline decoration-dotted">After check-in opens 60,840 Miles</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-blue-900 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Flex</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($businessFlex, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="underline decoration-dotted text-gray-400">Not eligible</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>Complimentary</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">USD 150.00</p>
                                                <p class="underline decoration-dotted">USD 225.00</p>
                                                <p>8,313 Miles</p>
                                                <p class="underline decoration-dotted">Eligible 53,820 Miles</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-slate-900 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Flex Plus</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($businessFlexPlus, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="underline decoration-dotted text-gray-400">Not eligible</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>Complimentary</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>9,025 Miles</p>
                                                <p class="underline decoration-dotted">Eligible 46,800 Miles</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- First --}}
                                <div x-show="cabin === 'first'" class="mt-4 bg-gray-50 rounded-2xl p-4">
                                    @php
                                        $firstBase = round($basePrice * 4.5, 2);
                                        $firstFlex = $firstBase;
                                        $firstFlexPlus = round($firstBase * 1.16, 2);
                                    @endphp

                                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                                        <div class="hidden lg:block text-[11px] text-gray-600 space-y-4 pt-2">
                                            <p class="text-gray-500">Fare benefits (per person)</p>
                                            <a href="#" class="text-blue-600 hover:underline text-[11px]">Compare all services</a>
                                            <p>Chauffeur-drive</p>
                                            <p>Lounge</p>
                                            <p>Seat selection</p>
                                            <p>Baggage</p>
                                            <p>Change fee</p>
                                            <p>Refund fee</p>
                                            <p>Skywards Miles</p>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-red-700 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Flex</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($firstFlex, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="underline decoration-dotted text-gray-400">Not eligible</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>Complimentary</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">USD 150.00</p>
                                                <p class="underline decoration-dotted">USD 225.00</p>
                                                <p>11,875 Miles</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-white overflow-hidden border border-gray-200 flex flex-col h-full">
                                            <div class="bg-red-900 text-white px-4 py-3 min-h-[72px]">
                                                <p class="text-sm font-semibold">Flex Plus</p>
                                                <p class="text-sm font-bold">PKR {{ number_format($firstFlexPlus, 0) }}</p>
                                            </div>
                                            <div class="px-4 py-3 text-[11px] text-gray-600 flex-1 flex flex-col gap-3">
                                                <p class="underline decoration-dotted text-gray-400">Not eligible</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>Complimentary</p>
                                                <p>2 x 32 kg</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p class="underline decoration-dotted">Complimentary</p>
                                                <p>11,875 Miles</p>
                                                <a href="{{ route('passenger.details') }}"
                                                   class="mt-auto w-full py-2 rounded-lg border border-gray-800 text-xs font-semibold flex items-center justify-center">
                                                    Select
                                                </a>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-white border border-gray-200 p-4 lg:col-span-2">
                                            <p class="text-xs text-gray-700 font-semibold">First Class is partially available for this journey</p>

                                            <div class="mt-3 relative">
                                                <div class="absolute left-3 top-8 bottom-8 w-px bg-gray-200"></div>

                                                <div class="relative pl-8">
                                                    <div class="absolute left-0 top-4 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 21l-1.5-4.5L3 15l18-6-6 18-4.5-1.5L6 20.5"/>
                                                        </svg>
                                                    </div>

                                                    <div class="rounded-lg border border-gray-200 p-3">
                                                        <p class="text-[11px] text-gray-500">Islamabad to Dubai</p>
                                                        <p class="text-xs font-semibold text-gray-800">3 hrs 30 mins</p>
                                                        <p class="text-xs text-blue-700 font-medium mt-1">Business Class</p>
                                                    </div>
                                                </div>

                                                <div class="mt-4 pl-8">
                                                    <p class="text-[11px] text-gray-500">Connection in Dubai (DXB)</p>
                                                    <p class="text-xs text-gray-700">Duration 1 hr 40 mins</p>
                                                </div>

                                                <div class="mt-4 relative pl-8">
                                                    <div class="absolute left-0 top-4 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 17h18M5 7l2 10m12-10-2 10"/>
                                                        </svg>
                                                    </div>

                                                    <div class="rounded-lg border border-gray-200 p-3">
                                                        <p class="text-[11px] text-gray-500">Dubai to Accra</p>
                                                        <p class="text-xs font-semibold text-gray-800">9 hrs 5 mins</p>
                                                        <p class="text-xs text-red-700 font-medium mt-1">First Class</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <p class="text-sm font-medium">No flights match your filters</p>
                        <button wire:click="clearFilters" class="mt-2 text-xs text-blue-500 hover:underline">Clear all filters</button>
                    </div>
                @endforelse

            </div>

            {{-- Loading overlay --}}
            <div wire:loading wire:target="search,selectDate,setSort,priceMin,priceMax,stops,airlines,departTimes"
                 class="flex items-center justify-center py-8">
                <div class="flex items-center gap-2 text-blue-600 text-sm font-medium">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    Updating flights…
                </div>
            </div>

        </div>
    </main>
</div>

</div>
