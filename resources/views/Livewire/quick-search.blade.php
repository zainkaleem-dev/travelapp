<div>
    {{-- Compact search card for global modal --}}
    <div class="search-card">

        {{-- Trip type tabs --}}
        <div class="trip-tabs mb-3">
            <button class="trip-tab {{ $tripType === 'return' ? 'active' : '' }}"
                wire:click="switchTab('return')">Return</button>
            <button class="trip-tab {{ $tripType === 'oneway' ? 'active' : '' }}" wire:click="switchTab('oneway')">
                One way</button>
            <button class="trip-tab {{ $tripType === 'multi' ? 'active' : '' }}"
                wire:click="switchTab('multi')">Multi-city</button>
        </div>

        {{-- PANEL: RETURN (copied from flights-search) --}}
        @if($tripType === 'return')
            <div>
                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

                    {{-- Departure --}}
                    <div class="field-wrap" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">Departure airport</span>
                        <input class="field-input" type="text" wire:model.live.debounce.150ms="returnDep"
                            wire:key="return-dep-input" @focus="show = true"
                            placeholder="City or airport" autocomplete="off">
                        @if($returnDep)
                            <button class="field-clear" wire:click.stop="$set('returnDep', '')" title="Clear">×</button>
                        @endif
                        @error('returnDep') <span class="field-error">{{ $message }}</span> @enderror
                        
                        <div x-cloak x-show="show" class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 280px;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>All locations</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="max-h-72 overflow-auto">
                                @php
                                    $items = $this->airportSearchResults;
                                @endphp
                                @if($searchType === 'returnDep')
                                    @forelse($items as $a)
                                        @php
                                            $display = $a['city'] . ' (' . $a['code'] . ')';
                                        @endphp
                                        <button type="button"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                            wire:click.stop="selectReturnDepAirport('{{ $display }}')"
                                            @click="show = false">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-800">{{ $a['city'] }},
                                                    {{ $a['country'] }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $a['airport'] }}</div>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-full bg-[#2ab4c0] text-white">{{ $a['code'] }}</span>
                                        </button>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">No results</div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Arrival --}}
                    <div class="field-wrap" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">Arrival airport</span>
                        <input class="field-input" type="text" wire:model.live.debounce.150ms="returnArr"
                            wire:key="return-arr-input" @focus="show = true"
                            placeholder="City or airport" autocomplete="off">
                        @error('returnArr') <span class="field-error">{{ $message }}</span> @enderror
                        
                        <div x-cloak x-show="show" class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 280px;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>All locations</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="max-h-72 overflow-auto">
                                @php
                                    $items = $this->airportSearchResults;
                                @endphp
                                @if($searchType === 'returnArr')
                                    @forelse($items as $a)
                                        @php
                                            $display = $a['city'] . ' (' . $a['code'] . ')';
                                        @endphp
                                        <button type="button"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                            wire:click.stop="selectReturnArrAirport('{{ $display }}')"
                                            @click="show = false">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-800">{{ $a['city'] }},
                                                    {{ $a['country'] }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $a['airport'] }}</div>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-full bg-[#2ab4c0] text-white">{{ $a['code'] }}</span>
                                        </button>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">No results</div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Date range --}}
                    <div class="field-wrap"
                        style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                        <div x-data="singleDatePicker({
                                value: @js($returnDepDate),
                                wireValueKey: 'returnDepDate',
                                title: 'Please choose your departure date',
                            })"
                            x-init="init()">
                            <span class="field-label">Departing</span>
                            <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text"
                                inputmode="none" readonly :value="display || ''" placeholder="mm/dd/yyyy"
                                @click="open = true">
                            @error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror

                            {{-- Calendar modal --}}
                            <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center"
                                aria-modal="true" role="dialog">
                                <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                <div
                                    class="relative w-[92vw] max-w-lg max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-base font-medium text-gray-800" x-text="title"></p>
                                        </div>
                                    </div>

                                    <div class="px-6 py-5">
                                        <div class="grid grid-cols-1 gap-8">
                                            <template x-for="(m, idx) in months.slice(0, 1)" :key="m.key">
                                                <div>
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="w-10">
                                                            <button type="button"
                                                                class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                @click.prevent="prevMonth()">
                                                                <svg class="w-5 h-5 text-gray-700" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-lg font-medium text-gray-800 text-center"
                                                            x-text="m.title"></p>
                                                        <div class="w-10 flex justify-end">
                                                            <button type="button"
                                                                class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                @click.prevent="nextMonth()">
                                                                <svg class="w-5 h-5 text-gray-700" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-7 gap-1.5 text-xs text-gray-500 mb-2">
                                                        <template x-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']"
                                                            :key="d">
                                                            <div class="text-center tracking-widest" x-text="d"></div>
                                                        </template>
                                                    </div>

                                                    <div class="grid grid-cols-7 gap-1.5">
                                                        <template x-for="cell in m.cells" :key="cell.key">
                                                            <button type="button"
                                                                class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition"
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

                                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button"
                                            class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                            @click="open = false">Close</button>
                                        <button type="button"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#2ab4c0] hover:bg-[#239ea9] disabled:opacity-50"
                                            :disabled="!iso" @click="apply(); open = false">
                                            Done
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span style="color:#9ca3af; font-size:18px; padding:0 4px; margin-top:10px;">–</span>

                        <div x-data="singleDatePicker({
                                value: @js($returnRetDate),
                                wireValueKey: 'returnRetDate',
                                title: 'When would you like to return?',
                            })"
                            x-init="init()">
                            <span class="field-label">Returning</span>
                            <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text"
                                inputmode="none" readonly :value="display || ''" placeholder="mm/dd/yyyy"
                                @click="open = true">
                            @error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror

                            {{-- Calendar modal --}}
                            <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center"
                                aria-modal="true" role="dialog">
                                <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                <div
                                    class="relative w-[92vw] max-w-lg max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-base font-medium text-gray-800" x-text="title"></p>
                                        </div>
                                    </div>

                                    <div class="px-6 py-5">
                                        <div class="grid grid-cols-1 gap-8">
                                            <template x-for="(m, idx) in months.slice(0, 1)" :key="m.key">
                                                <div>
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="w-10">
                                                            <button type="button"
                                                                class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                @click.prevent="prevMonth()">
                                                                <svg class="w-5 h-5 text-gray-700" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-lg font-medium text-gray-800 text-center"
                                                            x-text="m.title"></p>
                                                        <div class="w-10 flex justify-end">
                                                            <button type="button"
                                                                class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                @click.prevent="nextMonth()">
                                                                <svg class="w-5 h-5 text-gray-700" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-7 gap-1.5 text-xs text-gray-500 mb-2">
                                                        <template x-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']"
                                                            :key="d">
                                                            <div class="text-center tracking-widest" x-text="d"></div>
                                                        </template>
                                                    </div>

                                                    <div class="grid grid-cols-7 gap-1.5">
                                                        <template x-for="cell in m.cells" :key="cell.key">
                                                            <button type="button"
                                                                class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition"
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

                                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button"
                                            class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                            @click="open = false">Close</button>
                                        <button type="button"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#2ab4c0] hover:bg-[#239ea9] disabled:opacity-50"
                                            :disabled="!iso" @click="apply(); open = false">
                                            Done
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                {{-- Row 2: Passengers / Class / Currency (copied from flights-search) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    {{-- Passengers --}}
                    <div class="field-wrap" style="position:relative;" x-data="{ open: false }"
                        @click.outside="open = false">
                        <span class="field-label">Passengers</span>

                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span
                                class="text-gray-900">{{ $this->paxSummary($returnAdults, $returnChildren, $returnInfants) }}</span>
                            <span class="select-arrow static">
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg"
                            style="min-width: 280px;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-700">Passengers</p>
                                <div class="h-px bg-gray-100 mt-2"></div>
                            </div>

                            <div class="px-4 pb-3 space-y-4">
                                {{-- Adult --}}
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="decrementReturnPax('adult')" @disabled($returnAdults <= 1)>
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900">{{ $returnAdults }} Adult</div>
                                        <div class="text-xs text-gray-500">Ages 12+</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="incrementReturnPax('adult')" @disabled(($returnAdults + $returnChildren + $returnInfants) >= 9)>
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>

                                {{-- Child --}}
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="decrementReturnPax('child')" @disabled($returnChildren <= 0)>
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900">{{ $returnChildren }} Child</div>
                                        <div class="text-xs text-gray-500">Ages 2-11</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="incrementReturnPax('child')" @disabled(($returnAdults + $returnChildren + $returnInfants) >= 9)>
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>

                                {{-- Infant --}}
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="decrementReturnPax('infant')" @disabled($returnInfants <= 0)>
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900">{{ $returnInfants }} Infant</div>
                                        <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        wire:click="incrementReturnPax('infant')" @disabled(($returnAdults + $returnChildren + $returnInfants) >= 9 || $returnInfants >= $returnAdults)>
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

                    {{-- Class --}}
                    <div class="field-wrap" style="position:relative;" x-data="{ open: false }"
                        @click.outside="open = false">
                        <span class="field-label">Class</span>

                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span class="text-gray-900">{{ $returnClass }}</span>
                            <span class="select-arrow static">
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg"
                            style="min-width: 280px;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-700">Select class</p>
                                <div class="h-px bg-gray-100 mt-2"></div>
                            </div>

                            @php
                                $classes = ['Economy Class', 'Premium Economy', 'Business Class', 'First Class'];
                            @endphp

                            <div class="py-2">
                                @foreach($classes as $class)
                                    @php $isSelected = $returnClass === $class; @endphp
                                    <button type="button"
                                        class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                        wire:click="$set('returnClass', '{{ $class }}')" @click="open = false">
                                        <span
                                            class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium' }}">
                                            {{ $class }}
                                        </span>
                                        @if($isSelected)
                                            <svg class="w-5 h-5 text-[#2ab4c0]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Currency --}}
                    <div class="field-wrap" style="position:relative;" x-data="{ open: false }"
                        @click.outside="open = false">
                        <span class="field-label">Currency</span>

                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span class="text-gray-900">{{ $currency }}</span>
                            <span class="select-arrow static">
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg"
                            style="min-width: 210px;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-700">Preferred Currency</p>
                                <div class="h-px bg-gray-100 mt-2"></div>
                            </div>

                            <div class="py-2">
                                @foreach($currencies as $code => $label)
                                    @php $isSelected = $currency === $code; @endphp
                                    <button type="button"
                                        class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                        wire:click="$set('currency', '{{ $code }}')" @click="open = false">
                                        <span
                                            class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium' }}">
                                            {{ $code }} — {{ $label }}
                                        </span>
                                        @if($isSelected)
                                            <svg class="w-5 h-5 text-[#2ab4c0]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Note: oneway & multi tabs still visible but behaviour will be wired later if needed --}}
    </div>
</div>

