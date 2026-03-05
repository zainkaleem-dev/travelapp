<div>

{{-- ══════════════════════════════════════════════════════════
     SEARCH BAR — Professional, modern, eye-catching
══════════════════════════════════════════════════════════ --}}
<div class="relative overflow-visible z-10 py-4" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%); box-shadow: 0 4px 24px rgba(29, 78, 216, 0.35), inset 0 1px 0 rgba(255,255,255,0.12);">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-2 sm:gap-3 flex-nowrap min-h-[56px] overflow-visible">

        {{-- Origin with airport dropdown --}}
        <div class="relative flex-shrink-0"
             wire:click.outside="$set('showOriginAirports', false)">
            <div class="flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200"
                 wire:click="$set('showOriginAirports', true)"
                 style="min-height: 44px;">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input
                    class="bg-transparent font-semibold placeholder-white/80 focus:outline-none text-sm w-36 min-w-[7rem] max-w-[12rem]"
                    type="text"
                    wire:model.live.debounce.150ms="origin"
                    wire:focus="$set('showOriginAirports', true)"
                    placeholder="From"
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

        {{-- Swap button --}}
        <button type="button" wire:click="swapAirports" class="flex-shrink-0 h-10 w-10 rounded-xl border border-white/25 bg-white/15 backdrop-blur-sm text-white hover:bg-white/25 hover:border-white/40 transition-all duration-200 flex items-center justify-center shrink-0" title="Swap departure and destination">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </button>

        {{-- Destination with airport dropdown --}}
        <div class="relative flex-shrink-0"
             wire:click.outside="$set('showDestinationAirports', false)">
            <div class="flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200"
                 wire:click="$set('showDestinationAirports', true)"
                 style="min-height: 44px;">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input
                    class="bg-transparent font-semibold placeholder-white/80 focus:outline-none text-sm w-36 min-w-[7rem] max-w-[12rem]"
                    type="text"
                    wire:model.live.debounce.150ms="destination"
                    wire:focus="$set('showDestinationAirports', true)"
                    placeholder="To"
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
        <div class="relative flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200 flex-shrink-0 min-h-[44px]"
             x-data="singleDatePicker({
                    value: @js($departDate),
                    flexible: false,
                    wireValueKey: 'departDate',
                    wireFlexibleKey: null,
                    title: 'Please choose your departure date',
                })"
             x-init="init()"
             @click.outside="open = false">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </span>
            <button type="button"
                    class="bg-transparent text-xs sm:text-sm font-semibold text-white focus:outline-none cursor-pointer whitespace-nowrap"
                    @click="open = !open"
                    x-text="display || 'Departing'">
            </button>

            {{-- Calendar dropdown below button --}}
            <div x-cloak x-show="open" x-transition
                 class="absolute left-0 top-full z-[999] mt-2 rounded-2xl bg-white shadow-xl border border-gray-200 overflow-hidden flex flex-col"
                 style="min-width: 320px;"
                 @click.stop>
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

                    <div class="p-5 w-full box-border">
                        <div class="grid grid-cols-1">
                            <template x-for="m in months" :key="m.key">
                                <div class="w-full">
                                    <div class="flex items-center justify-between mb-5">
                                        <button type="button"
                                                class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="prevMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <p class="text-base font-semibold text-gray-900 shrink-0" x-text="m.title"></p>
                                        <button type="button"
                                                class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="nextMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-x-1 gap-y-0 w-full mb-2" style="grid-template-columns: repeat(7, minmax(2rem, 1fr));">
                                        <template x-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="d">
                                            <div class="flex h-8 min-w-[2rem] w-full items-center justify-center text-[11px] font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" x-text="d"></div>
                                        </template>
                                    </div>

                                    <div class="grid gap-1 w-full" style="grid-template-columns: repeat(7, minmax(2rem, 1fr));">
                                        <template x-for="cell in m.cells" :key="cell.key">
                                            <button type="button"
                                                    class="flex h-9 min-w-[2rem] w-full max-w-[2.5rem] mx-auto items-center justify-center rounded-full text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed shrink-0"
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

        {{-- Returning date (separate calendar) --}}
        <div class="relative flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200 flex-shrink-0 min-h-[44px]"
             x-data="singleDatePicker({
                    value: @js($returnDate),
                    flexible: false,
                    wireValueKey: 'returnDate',
                    wireFlexibleKey: null,
                    title: 'Please choose your return date',
                })"
             x-init="init()"
             @click.outside="open = false">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </span>
            <button type="button"
                    class="bg-transparent text-xs sm:text-sm font-semibold text-white focus:outline-none cursor-pointer whitespace-nowrap"
                    @click="open = !open"
                    x-text="display || 'Returning'">
            </button>

            {{-- Calendar dropdown below button --}}
            <div x-cloak x-show="open" x-transition
                 class="absolute left-0 top-full z-[999] mt-2 rounded-2xl bg-white shadow-xl border border-gray-200 overflow-hidden flex flex-col"
                 style="min-width: 320px;"
                 @click.stop>
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

                    <div class="p-5 w-full box-border">
                        <div class="grid grid-cols-1">
                            <template x-for="m in months" :key="m.key">
                                <div class="w-full">
                                    <div class="flex items-center justify-between mb-5">
                                        <button type="button"
                                                class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="prevMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <p class="text-base font-semibold text-gray-900 shrink-0" x-text="m.title"></p>
                                        <button type="button"
                                                class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                @click.prevent="nextMonth()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-x-1 gap-y-0 w-full mb-2" style="grid-template-columns: repeat(7, minmax(2rem, 1fr));">
                                        <template x-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="d">
                                            <div class="flex h-8 min-w-[2rem] w-full items-center justify-center text-[11px] font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" x-text="d"></div>
                                        </template>
                                    </div>

                                    <div class="grid gap-1 w-full" style="grid-template-columns: repeat(7, minmax(2rem, 1fr));">
                                        <template x-for="cell in m.cells" :key="cell.key">
                                            <button type="button"
                                                    class="flex h-9 min-w-[2rem] w-full max-w-[2.5rem] mx-auto items-center justify-center rounded-full text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed shrink-0"
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

        {{-- Passengers (dropdown UI similar to search page) --}}
        <div class="flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200 relative flex-shrink-0 min-w-[7rem] min-h-[44px]"
             x-data="{ open: false }"
             @click.outside="open = false">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </span>

            <button type="button"
                    class="field-select text-left w-full flex items-center justify-between bg-transparent text-white text-xs sm:text-sm"
                    @click="open = !open"
                    aria-haspopup="listbox"
                    :aria-expanded="open">
                <span class="text-white font-semibold">
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
        <div class="flex items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-white text-sm border border-white/20 bg-white/15 backdrop-blur-sm shadow-sm hover:bg-white/20 hover:border-white/30 transition-all duration-200 relative flex-shrink-0 min-w-[7rem] min-h-[44px]"
             x-data="{ open: false }"
             @click.outside="open = false">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </span>
            <button type="button"
                    class="field-select text-left w-full flex items-center justify-between bg-transparent text-white text-xs sm:text-sm min-w-[140px]"
                    @click="open = !open"
                    aria-haspopup="listbox"
                    :aria-expanded="open">
                <span class="text-white font-semibold truncate">{{ $travelClass }}</span>
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
                class="flex-shrink-0 ml-auto rounded-xl px-5 py-2.5 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-white/40 focus:ring-offset-2 focus:ring-offset-transparent flex items-center gap-2 self-center min-h-[44px]"
                style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
            <span wire:loading.remove wire:target="search" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Search
            </span>
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
    <aside class="w-full lg:w-64 flex-shrink-0 space-y-5">

        {{-- Price Range --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Price Range
                </h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex items-center justify-between text-xs font-medium text-gray-500">
                    <span class="text-gray-600">${{ number_format($priceMin) }}</span>
                    <span class="text-gray-600">${{ number_format($priceMax) }}</span>
                </div>
                <input type="range" wire:model.live.debounce.250ms="priceMax" min="100" max="500000" step="500"
                       class="flight-list-price-range w-full h-2 rounded-full appearance-none cursor-pointer bg-gray-200 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-blue-500 [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:border-0 [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-blue-500 [&::-moz-range-thumb]:border-0">
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">$</span>
                        <input type="number" wire:model.live.debounce.500ms="priceMin"
                               class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all bg-gray-50/50 hover:bg-white">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">$</span>
                        <input type="number" wire:model.live.debounce.500ms="priceMax"
                               class="w-full pl-7 pr-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all bg-gray-50/50 hover:bg-white">
                    </div>
                </div>
            </div>
        </div>

        {{-- Stops --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8v8M3 21h18M3 10h18M3 7l9-4 9 4M3 10l9 4 9-4"/></svg>
                    </span>
                    Stops
                </h3>
            </div>
            <div class="p-4 space-y-1">
                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                    <input type="checkbox" wire:model.live="stops" value="any" class="w-4 h-4 rounded border-2 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Any stops</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                    <input type="checkbox" wire:model.live="stops" value="direct" class="w-4 h-4 rounded border-2 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Non-stop</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                    <input type="checkbox" wire:model.live="stops" value="1stop" class="w-4 h-4 rounded border-2 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">1 Stop</span>
                </label>
            </div>
        </div>

        {{-- Airlines --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </span>
                    Airlines
                </h3>
            </div>
            <div class="p-3 space-y-0.5 max-h-64 overflow-y-auto pr-1 no-scrollbar">
                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                    <input type="checkbox" wire:model.live="airlines" value="any" class="w-4 h-4 rounded border-2 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">All Airlines</span>
                </label>
                @foreach($this->availableAirlines as $airline)
                    <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                        <input type="checkbox" wire:model.live="airlines" value="{{ strtolower($airline) }}" class="w-4 h-4 rounded border-2 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $airline }}</span>
                    </label>
                @endforeach
            </div>
        </div>

    </aside>

    {{-- ─── MAIN RESULTS ────────────────────────────────────── --}}
    <main class="flex-1 min-w-0">

        {{-- Outbound header --}}
        <div class="bg-blue-500 text-white rounded-xl px-4 py-3 mb-3 flex items-center justify-between flex-wrap gap-2" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                <p class="text-base font-bold text-white">{{ $origin }} → {{ $destination }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-white">{{ $origin }} → {{ $destination }}</p>
                <p class="text-base font-bold text-white">{{ \Carbon\Carbon::parse($departDate)->format('l d.m.Y') }}</p>
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
            <div class="space-y-4 p-4" wire:loading.class="opacity-50" wire:target="search,selectDate,setSort">

                @forelse($this->flights as $flight)
                    <div wire:key="flight-{{ $flight['id'] }}" class="flight-card bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden {{ $flight['bgClass'] }}" x-data="{ open: false, cabin: 'economy' }" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.06);">
                        {{-- Outbound & Return in separate sections --}}
                        <div class="flex flex-col sm:flex-row">
                            <div class="flex-1 min-w-0 divide-y divide-gray-100">
                                @php $itineraries = $flight['itineraries'] ?? []; @endphp
                                @foreach($itineraries as $idx => $itin)
                                    <div class="px-4 py-4 {{ $idx > 0 ? 'bg-gray-50/50' : '' }}" style="box-shadow: 0 1px 2px 0 rgba(0,0,0,0.03);">
                                        {{-- Section label: Outbound / Return --}}
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-semibold uppercase tracking-wider {{ $idx === 0 ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                                @if($idx === 0)
                                                    <svg class="w-3 h-3 flex-shrink-0" style="margin-top: 0.5px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                    Outbound
                                                @else
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="margin-top: 0.5px;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M5 12l5 5M5 12l5-5"/></svg>
                                                    Return
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            {{-- Airline logo --}}
                                            <div class="w-14 h-14 flex-shrink-0 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 overflow-hidden shadow-sm">
                                                <img src="https://pics.avs.io/128/128/{{ $itin['airlineCode'] }}.png"
                                                     alt="{{ $itin['airline'] }}"
                                                     class="w-full h-full object-contain">
                                            </div>
                                            {{-- Route info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-stretch gap-3">
                                                    {{-- Departure: time + city + airport --}}
                                                    <div class="min-w-0 w-28 rounded-xl border border-gray-100 bg-gray-50/80 px-3 py-2.5 flex flex-col justify-center">
                                                        <p class="text-base font-bold text-gray-900">{{ $itin['dep'] }}</p>
                                                        <p class="text-xs font-medium text-gray-600 truncate mt-0.5" title="{{ $itin['depCity'] }}">{{ $itin['depCity'] }}</p>
                                                        <p class="text-xs text-gray-500 font-medium truncate max-w-[120px]">{{ $itin['depAirport'] }}</p>
                                                    </div>
                                                    <div class="flex-1 flex flex-col items-center justify-center gap-2 min-w-0">
                                                        {{-- Flight number badge (center-aligned) --}}
                                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-gradient-to-r from-slate-100 to-blue-50 text-blue-700 border border-blue-100/80 shadow-sm w-full max-w-[120px] text-center">
                                                            {{ $itin['flightNumber'] ?? '' }}
                                                        </span>
                                                        {{-- Route line: start dot → stops → arrow only (no end dot) --}}
                                                        <div class="relative w-full flex items-center py-1.5" style="min-height: 28px;">
                                                            <div class="absolute left-0 right-0 top-1/2 h-0.5 rounded-full bg-gradient-to-r from-blue-200 via-blue-300 to-indigo-400 opacity-90" style="margin-top: -1px;"></div>
                                                            <div class="w-3 h-3 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex-shrink-0 relative z-10 border-2 border-white shadow-md ring-2 ring-blue-200/50 self-center"></div>
                                                            <div class="flex-1 min-w-0"></div>
                                                            <div class="relative z-10 px-3 py-1 rounded-lg text-[11px] font-semibold whitespace-nowrap bg-white/95 text-gray-700 border border-gray-200/80 shadow-md ring-1 ring-gray-100 backdrop-blur-sm text-center min-w-[4.5rem]">
                                                                {{ $itin['stops'] }}
                                                            </div>
                                                            <div class="flex-1 min-w-0"></div>
                                                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 relative z-10 drop-shadow-sm self-center block" style="vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                            </svg>
                                                        </div>
                                                        {{-- Duration (center-aligned) --}}
                                                        <div class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-700 w-full max-w-[120px]">
                                                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                                                            <span>{{ $itin['duration'] }}</span>
                                                        </div>
                                                    </div>
                                                    {{-- Arrival: time + city + airport --}}
                                                    <div class="text-right min-w-0 w-28 rounded-xl border border-gray-100 bg-gray-50/80 px-3 py-2.5 flex flex-col justify-center">
                                                        <p class="text-base font-bold text-gray-900">{{ $itin['arr'] }}</p>
                                                        <p class="text-xs font-medium text-gray-600 truncate mt-0.5" title="{{ $itin['arrCity'] }}">{{ $itin['arrCity'] }}</p>
                                                        <p class="text-xs text-gray-500 font-medium truncate max-w-[120px]">{{ $itin['arrAirport'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Price & CTA (right column / bottom on small screens) --}}
                            <div class="flex-shrink-0 px-5 py-5 sm:py-6 border-t sm:border-t-0 sm:border-l border-gray-200/80 bg-gradient-to-b from-white to-gray-50/50 flex flex-row sm:flex-col items-center justify-between sm:justify-center gap-5 sm:w-44">
                                <div class="text-left sm:text-center space-y-1">
                                    <p class="text-2xl font-bold text-gray-900 tracking-tight">${{ number_format($flight['price'], 2) }}</p>
                                    @if(!empty($flight['oldPrice']))
                                        <p class="text-xs text-gray-500 line-through">${{ number_format($flight['oldPrice'], 2) }}</p>
                                    @elseif(!empty($flight['note']))
                                        <p class="text-xs text-red-500 font-medium">{{ $flight['note'] }}</p>
                                    @else
                                        <p class="text-xs text-gray-500 font-medium">Per person</p>
                                    @endif
                                </div>
                                <button wire:click="selectFlight('{{ $flight['id'] }}')"
                                        class="flex-shrink-0 w-full sm:w-auto px-6 py-3 {{ $flight['btnClass'] }} text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400">
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
