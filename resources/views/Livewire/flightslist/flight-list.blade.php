<div>

{{-- ══════════════════════════════════════════════════════════
     SEARCH BAR
══════════════════════════════════════════════════════════ --}}
<div class="bg-blue-600 py-3 overflow-visible relative z-10">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-2 sm:gap-3 flex-nowrap min-h-[52px] overflow-visible">

        {{-- Origin with airport dropdown (like flights-search) --}}
        <div class="relative flex-shrink-0"
             wire:click.outside="$set('showOriginAirports', false)">
            <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm"
                 wire:click="$set('showOriginAirports', true)">
                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                </svg>
                <input
                    class="bg-transparent font-medium placeholder-white/70 focus:outline-none text-sm w-36 min-w-[7rem] max-w-[12rem]"
                    type="text"
                    wire:model.live.debounce.150ms="origin"
                    wire:focus="$set('showOriginAirports', true)"
                    placeholder="City or airport"
                    autocomplete="off">
            </div>

            @if($showOriginAirports)
                <div class="absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden text-gray-900">
                    <div class="px-4 py-3">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z"/>
                                <circle cx="12" cy="11" r="2"/>
                            </svg>
                            <span>All locations</span>
                        </div>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="max-h-72 overflow-auto no-scrollbar">
                        @php
                            $originItems = $this->filteredAirports($origin);
                        @endphp
                        @forelse($originItems as $a)
                            @php
                                $display = $a['city'] . ' (' . $a['code'] . ')';
                            @endphp
                            <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click="selectOriginAirport('{{ $display }}')">
                                <div>
                                    <div class="text-sm font-semibold text-gray-800">{{ $a['city'] }}, {{ $a['country'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $a['airport'] }}</div>
                                </div>
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-600 text-white">{{ $a['code'] }}</span>
                            </button>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500">No results</div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        {{-- Swap button: swap departure and return airports --}}
        <button type="button" wire:click="swapAirports" class="text-white/70 hover:text-white transition-colors flex-shrink-0 p-1 shrink-0" title="Swap departure and destination">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </button>

        {{-- Destination with airport dropdown --}}
        <div class="relative flex-shrink-0"
             wire:click.outside="$set('showDestinationAirports', false)">
            <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm"
                 wire:click="$set('showDestinationAirports', true)">
                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                </svg>
                <input
                    class="bg-transparent font-medium placeholder-white/70 focus:outline-none text-sm w-36 min-w-[7rem] max-w-[12rem]"
                    type="text"
                    wire:model.live.debounce.150ms="destination"
                    wire:focus="$set('showDestinationAirports', true)"
                    placeholder="City or airport"
                    autocomplete="off">
            </div>

            @if($showDestinationAirports)
                <div class="absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden text-gray-900">
                    <div class="px-4 py-3">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z"/>
                                <circle cx="12" cy="11" r="2"/>
                            </svg>
                            <span>All locations</span>
                        </div>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    <div class="max-h-72 overflow-auto no-scrollbar">
                        @php
                            $destItems = $this->filteredAirports($destination);
                        @endphp
                        @forelse($destItems as $a)
                            @php
                                $display = $a['city'] . ' (' . $a['code'] . ')';
                            @endphp
                            <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click="selectDestinationAirport('{{ $display }}')">
                                <div>
                                    <div class="text-sm font-semibold text-gray-800">{{ $a['city'] }}, {{ $a['country'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $a['airport'] }}</div>
                                </div>
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-600 text-white">{{ $a['code'] }}</span>
                            </button>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500">No results</div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        {{-- Departing date (separate calendar) --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm flex-shrink-0"
             x-data="singleDatePicker({
                    value: @js($departDate),
                    flexible: false,
                    wireValueKey: 'departDate',
                    wireFlexibleKey: null,
                    title: 'Please choose your departure date',
                })"
             x-init="init()">
            <svg class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <button type="button"
                    class="bg-transparent text-xs sm:text-sm text-white focus:outline-none cursor-pointer whitespace-nowrap"
                    @click="open = true"
                    x-text="display || 'Departing'">
            </button>

            {{-- Calendar modal for departing --}}
            <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                 aria-modal="true" role="dialog">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

                <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden flex flex-col border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <p class="text-sm font-semibold text-gray-800" x-text="title"></p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1">
                            <template x-for="m in months" :key="m.key">
                                <div>
                                    <div class="flex items-center justify-between mb-5">
                                        <button type="button"
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="prevMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <p class="text-base font-semibold text-gray-900" x-text="m.title"></p>
                                        <button type="button"
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="nextMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-0.5 text-[11px] font-medium text-gray-500 uppercase tracking-wider mb-3">
                                        <template x-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="d">
                                            <div class="flex h-8 items-center justify-center" x-text="d"></div>
                                        </template>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="cell in m.cells" :key="cell.key">
                                            <button type="button"
                                                    class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed"
                                                    :disabled="cell.disabled || !cell.day"
                                                    @click="cell.day && pick(cell.iso)" :class="dayClass(cell)">
                                                <span x-text="cell.day || ''"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-end gap-2">
                        <button type="button"
                                class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors"
                                @click="open = false">Close</button>
                        <button type="button"
                                class="px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                :disabled="!iso" @click="apply(); open = false">
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Returning date (separate calendar) --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm flex-shrink-0"
             x-data="singleDatePicker({
                    value: @js($returnDate),
                    flexible: false,
                    wireValueKey: 'returnDate',
                    wireFlexibleKey: null,
                    title: 'Please choose your return date',
                })"
             x-init="init()">
            <svg class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <button type="button"
                    class="bg-transparent text-xs sm:text-sm text-white focus:outline-none cursor-pointer whitespace-nowrap"
                    @click="open = true"
                    x-text="display || 'Returning'">
            </button>

            {{-- Calendar modal for returning --}}
            <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                 aria-modal="true" role="dialog">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

                <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden flex flex-col border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <p class="text-sm font-semibold text-gray-800" x-text="title"></p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1">
                            <template x-for="m in months" :key="m.key">
                                <div>
                                    <div class="flex items-center justify-between mb-5">
                                        <button type="button"
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="prevMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <p class="text-base font-semibold text-gray-900" x-text="m.title"></p>
                                        <button type="button"
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="nextMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-0.5 text-[11px] font-medium text-gray-500 uppercase tracking-wider mb-3">
                                        <template x-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="d">
                                            <div class="flex h-8 items-center justify-center" x-text="d"></div>
                                        </template>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="cell in m.cells" :key="cell.key">
                                            <button type="button"
                                                    class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed"
                                                    :disabled="cell.disabled || !cell.day"
                                                    @click="cell.day && pick(cell.iso)" :class="dayClass(cell)">
                                                <span x-text="cell.day || ''"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-end gap-2">
                        <button type="button"
                                class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors"
                                @click="open = false">Close</button>
                        <button type="button"
                                class="px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                :disabled="!iso" @click="apply(); open = false">
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Passengers (dropdown UI similar to search page) --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm relative flex-shrink-0 min-w-[7rem]"
             x-data="{ open: false }"
             @click.outside="open = false">
            <svg class="w-4 h-4 opacity-70" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>

            <button type="button"
                    class="field-select text-left w-full flex items-center justify-between bg-transparent text-white text-xs sm:text-sm"
                    @click="open = !open"
                    aria-haspopup="listbox"
                    :aria-expanded="open">
                <span class="text-white">
                    {{ $passengers }} {{ $passengers === 1 ? 'Passenger' : 'Passengers' }}
                </span>
                <span class="ml-1">
                    <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>

            <div x-cloak x-show="open" x-transition
                 class="absolute left-0 top-full mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg text-gray-900 z-[100]">
                <div class="px-4 py-3">
                    <p class="text-sm font-medium text-gray-700">Passengers</p>
                    <div class="h-px bg-gray-100 mt-2"></div>
                </div>

                <div class="px-4 pb-3 space-y-4">
                    {{-- Adult --}}
                    <div class="flex items-center justify-between">
                        <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementPassengerType('adult')"
                                @disabled($adultCount <= 1)>
                            <span class="text-xl leading-none">−</span>
                        </button>
                        <div class="text-center">
                            <div class="text-base font-semibold text-gray-900">{{ $adultCount }} Adult</div>
                            <div class="text-xs text-gray-500">Ages 12+</div>
                        </div>
                        <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementPassengerType('adult')"
                                @disabled(($adultCount + $childCount + $infantCount) >= 9)>
                            <span class="text-xl leading-none">+</span>
                        </button>
                    </div>

                    {{-- Child --}}
                    <div class="flex items-center justify-between">
                        <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementPassengerType('child')"
                                @disabled($childCount <= 0)>
                            <span class="text-xl leading-none">−</span>
                        </button>
                        <div class="text-center">
                            <div class="text-base font-semibold text-gray-900">{{ $childCount }} Child</div>
                            <div class="text-xs text-gray-500">Ages 2–11</div>
                        </div>
                        <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementPassengerType('child')"
                                @disabled(($adultCount + $childCount + $infantCount) >= 9)>
                            <span class="text-xl leading-none">+</span>
                        </button>
                    </div>

                    {{-- Infant --}}
                    <div class="flex items-center justify-between">
                        <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementPassengerType('infant')"
                                @disabled($infantCount <= 0)>
                            <span class="text-xl leading-none">−</span>
                        </button>
                        <div class="text-center">
                            <div class="text-base font-semibold text-gray-900">{{ $infantCount }} Infant</div>
                            <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                        </div>
                        <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementPassengerType('infant')"
                                @disabled(($adultCount + $childCount + $infantCount) >= 9 || $infantCount >= $adultCount)>
                            <span class="text-xl leading-none">+</span>
                        </button>
                    </div>

                    <div class="h-px bg-gray-100"></div>
                    <p class="text-xs text-gray-600">
                        Please note: You can book for a maximum of nine passengers.
                    </p>
                </div>
            </div>
        </div>

        {{-- Class dropdown (Economy, Premium Economy, Business, First) --}}
        <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2 text-white text-sm relative flex-shrink-0 min-w-[7rem]"
             x-data="{ open: false }"
             @click.outside="open = false">
            <svg class="w-4 h-4 opacity-70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <button type="button"
                    class="field-select text-left w-full flex items-center justify-between bg-transparent text-white text-xs sm:text-sm min-w-[140px]"
                    @click="open = !open"
                    aria-haspopup="listbox"
                    :aria-expanded="open">
                <span class="text-white truncate">{{ $travelClass }}</span>
                <span class="ml-1 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>

            <div x-cloak x-show="open" x-transition
                 class="absolute left-0 top-full mt-2 w-72 rounded-xl border border-gray-200 bg-white shadow-lg text-gray-900 z-[100] min-w-[16rem]">
                <div class="px-4 py-3">
                    <p class="text-sm font-medium text-gray-700">Select class</p>
                    <div class="h-px bg-gray-100 mt-2"></div>
                </div>
                @php
                    $classOptions = ['Economy Class', 'Premium Economy', 'Business Class', 'First Class'];
                @endphp
                <div class="py-2">
                    @foreach($classOptions as $classOpt)
                        @php $isSelected = $travelClass === $classOpt; @endphp
                        <button type="button"
                                class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                wire:click="setTravelClass('{{ $classOpt }}')"
                                @click="open = false">
                            <span class="{{ $isSelected ? 'text-blue-600 font-semibold' : 'text-gray-900 font-medium' }}">
                                {{ $classOpt }}
                            </span>
                            @if($isSelected)
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="button" wire:click="search"
                wire:loading.attr="disabled"
                class="flex-shrink-0 ml-auto px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2 self-center">
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
<div class="max-w-7xl mx-auto px-4 py-4 flex flex-col lg:flex-row gap-6" wire:init="$dispatch('loadDateRailPrices')">

    {{-- ─── SIDEBAR FILTERS ────────────────────────────────── --}}
    <aside class="w-full lg:w-64 flex-shrink-0 space-y-6">

        {{-- Price Range --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Price Range</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>${{ number_format($priceMin) }}</span>
                    <span>${{ number_format($priceMax) }}</span>
                </div>
                <input type="range" wire:model.live.debounce.250ms="priceMax" min="100" max="500000" step="500"
                       class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]">$</span>
                        <input type="number" wire:model.live.debounce.500ms="priceMin"
                               class="w-full pl-6 pr-2 py-2.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]">$</span>
                        <input type="number" wire:model.live.debounce.500ms="priceMax"
                               class="w-full pl-6 pr-2 py-2.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Stops --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Stops</h3>
            <div class="space-y-3">
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model.live="stops" value="any" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">Any stops</span>
                </label>
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model.live="stops" value="direct" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">Non-stop</span>
                </label>
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model.live="stops" value="1stop" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">1 Stop</span>
                </label>
            </div>
        </div>

        {{-- Airlines --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Airlines</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto pr-2 no-scrollbar">
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model.live="airlines" value="any" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">All Airlines</span>
                </label>
                @foreach($this->availableAirlines as $airline)
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" wire:model.live="airlines" value="{{ strtolower($airline) }}" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">{{ $airline }}</span>
                    </label>
                @endforeach
            </div>
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
            <div class="flex items-center border-b border-gray-100 px-2 gap-1 overflow-x-auto overflow-y-hidden no-scrollbar">
                @foreach([
                    'best'       => 'Best Value',
                    'cheap'      => 'Cheapest',
                    'fastest'    => 'Fastest',
                    'early'      => 'Early Depart',
                    'late'       => 'Late Depart',
                    'refundable' => 'Refundable',
                ] as $key => $label)
                    <button wire:click="setSort('{{ $key }}')"
                            class="tab px-3 py-2.5 text-xs text-gray-500 hover:text-gray-700 whitespace-nowrap {{ $sortTab === $key ? 'active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Date rail --}}
            <div class="flex items-center px-4 py-3 border-b border-gray-100 bg-gray-50/30">
                <button wire:click="shiftDate(-1)" wire:loading.attr="disabled"
                        class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex flex-1 justify-between px-2 py-1 overflow-x-auto no-scrollbar">
                    @foreach($dateRail as $day)
                        <button wire:key="date-{{ $day['date'] }}"
                                wire:click="selectDate('{{ $day['date'] }}')"
                                class="flex-shrink-0 text-center px-4 py-1.5 rounded-xl cursor-pointer transition-all duration-200
                                       {{ $selectedDate === $day['date']
                                           ? 'bg-blue-600 shadow-md shadow-blue-200 scale-105'
                                           : 'hover:bg-white hover:shadow-sm' }}">
                            <p class="text-[10px] font-medium uppercase tracking-wider {{ $selectedDate === $day['date'] ? 'text-blue-100' : 'text-gray-400' }}">
                                {{ $day['label'] }}
                            </p>
                            @if($day['price'])
                                <p class="text-xs font-bold mt-0.5 {{ $selectedDate === $day['date'] ? 'text-white' : ($day['price'] <= current($dateRail)['price'] ? 'text-green-600' : 'text-gray-900') }}">
                                    ${{ $day['price'] }}
                                </p>
                            @else
                                <p class="text-xs font-bold mt-0.5 {{ $selectedDate === $day['date'] ? 'text-white/40' : 'text-gray-300' }}">-</p>
                            @endif
                        </button>
                    @endforeach
                </div>

                <button wire:click="shiftDate(1)" wire:loading.attr="disabled"
                        class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Flight list --}}
            <div class="divide-y divide-gray-100" wire:loading.class="opacity-50" wire:target="search,selectDate,setSort">

                @forelse($this->flights as $flight)
                    <div wire:key="flight-{{ $flight['id'] }}" class="flight-card px-4 py-3 {{ $flight['bgClass'] }}" x-data="{ open: false, cabin: 'economy' }">
                        <div class="flex items-center gap-4 w-full">
                            {{-- Route itineraries --}}
                            <div class="flex-1 min-w-0 flex flex-col gap-3">
                                @foreach($flight['itineraries'] ?? [] as $itin)
                                    <div class="flex items-center gap-4">
                                        {{-- Airline logo for itinerary --}}
                                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center relative">
                                            <img src="https://pics.avs.io/64/64/{{ $itin['airlineCode'] }}.png" 
                                                 alt="{{ $itin['airline'] }}"
                                                 class="w-full h-full object-contain">
                                        </div>

                                        {{-- Route info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3">
                                                {{-- Departure --}}
                                                <div class="min-w-0 w-24">
                                                    <p class="text-sm font-bold text-gray-800">{{ $itin['dep'] }}</p>
                                                    <p class="text-xs text-gray-500 truncate" title="{{ $itin['depCity'] }}">{{ $itin['depCity'] }}</p>
                                                    <p class="text-xs text-gray-400 truncate max-w-[120px]">{{ $itin['depAirport'] }}</p>
                                                </div>

                                                {{-- Route line --}}
                                                <div class="flex-1 flex flex-col items-center gap-1">
                                                    <span class="text-[9px] font-bold text-gray-500 uppercase">{{ $itin['flightNumber'] ?? '' }}</span>
                                                    <div class="relative w-full flight-line flex items-center justify-between">
                                                        <div class="flight-dot"></div>
                                                        <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 text-[11px] text-gray-500 relative z-10 whitespace-nowrap">
                                                            {{ $itin['stops'] }}
                                                        </div>
                                                        <div class="flight-dot"></div>
                                                    </div>
                                                    <p class="text-[11px] text-gray-400 whitespace-nowrap">{{ $itin['duration'] }}</p>
                                                </div>

                                                {{-- Arrival --}}
                                                <div class="text-right min-w-0 w-24">
                                                    <p class="text-sm font-bold text-gray-800">{{ $itin['arr'] }}</p>
                                                    <p class="text-xs text-gray-500 truncate" title="{{ $itin['arrCity'] }}">{{ $itin['arrCity'] }}</p>
                                                    <p class="text-xs text-gray-400 truncate max-w-[120px]">{{ $itin['arrAirport'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Price & CTA --}}
                            <div class="text-right flex-shrink-0 w-36">
                                
                                <p class="text-lg font-bold text-gray-800">${{ $flight['price'] }}</p>

                                @if(!empty($flight['oldPrice']))
                                    <p class="text-xs text-gray-500 line-through">${{ number_format($flight['oldPrice'], 2) }}</p>
                                @elseif(!empty($flight['note']))
                                    <p class="text-xs text-red-500 font-medium">{{ $flight['note'] }}</p>
                                @else
                                    <p class="text-xs text-gray-400">Per person</p>
                                @endif

                                <button wire:click="selectFlight('{{ $flight['id'] }}')"
                                   class="mt-1 w-full py-1.5 {{ $flight['btnClass'] }} text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1">
                                    Select
                                </button>
                            </div>
                        </div>

                    </div>



                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <p class="text-sm font-medium">{{ $this->errorMessage ?: 'No flights match your filters' }}</p>
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

<script>
    window.singleDatePicker = function (opts) {
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
                if (this.iso && cell.iso === this.iso) return 'bg-blue-600 text-white';
                return 'text-gray-900 hover:bg-gray-100';
            },
        };
    }
</script>
