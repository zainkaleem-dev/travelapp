<div>

    {{-- ── Hero ── --}}
    @empty($quick)
        <div class="py-10 text-center px-4">
            <h1 class="hero-title">Book a flight</h1>
            <p class="hero-sub">Search for flights and book online. See our routes and schedules, and discover more about
                the experience you can look forward to on board.</p>
        </div>
    @endempty

    {{-- ════════════════════════════════════════
    SEARCH CARD
    ════════════════════════════════════════ --}}
    <div class="search-card {{ !empty($quick) ? 'quick-inline-search' : '' }}">

        {{-- Tab row (hidden in quick inline variant) --}}
        @empty($quick)
            <div class="trip-tabs">
                <button class="trip-tab {{ $tripType === 'return' ? 'active' : '' }}"
                    wire:click="switchTab('return')">Return</button>
                <button class="trip-tab {{ $tripType === 'oneway' ? 'active' : '' }}" wire:click="switchTab('oneway')">One
                    way</button>
                <button class="trip-tab {{ $tripType === 'multi' ? 'active' : '' }}"
                    wire:click="switchTab('multi')">Multi-city</button>
            </div>
        @endempty



        {{-- ══════════════════════════════════════
        PANEL: RETURN
        ══════════════════════════════════════ --}}
        @if($tripType === 'return')
                    <div class="{{ !empty($quick) ? 'qs-row-wrapper' : '' }}">
                        {{-- Row 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

                            {{-- Departure --}}
                            <div>
                                <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                                    @click.outside="show = false">
                                <span class="field-label">Departure airport</span>
                                <input class="field-input" type="text" wire:model.live.debounce.150ms="returnDep"
                                    wire:key="return-dep-input" @focus="show = true" placeholder="City or airport"
                                    autocomplete="off">
                                @if($returnDep)
                                    <button class="field-clear" wire:click.stop="$set('returnDep', '')" title="Clear">×</button>
                                @endif

                                <div x-cloak x-show="show"
                                    class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                                                    wire:click.stop="selectReturnDepAirport('{{ $display }}')" @click="show = false">
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
                            @error('returnDep') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                            {{-- Arrival --}}
                            <div>
                                <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                                    @click.outside="show = false">
                                <span class="field-label">Arrival airport</span>
                                <input class="field-input" type="text" wire:model.live.debounce.150ms="returnArr"
                                    wire:key="return-arr-input" @focus="show = true" placeholder="City or airport"
                                    autocomplete="off">

                                <div x-cloak x-show="show"
                                    class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                                                    wire:click.stop="selectReturnArrAirport('{{ $display }}')" @click="show = false">
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
                            @error('returnArr') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                            {{-- Date range --}}
                            <div>
                                <div class="field-wrap"
                                    style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                                <div wire:key="return-departure-picker-{{ $returnDepDate ?: 'empty' }}-{{ $errors->has('returnDepDate') ? 'error' : 'ok' }}"
                                    x-data="singleDatePicker({
                                                                                                                                                                                                                                                                                                                                                                             value: @js($returnDepDate),
                                                                                                                                                                                                                                                                                                                                                                             wireValueKey: 'returnDepDate',
                                                                                                                                                                                                                                                                                                                                                                             title: 'Please choose your departure date',
                                                                                                                                                                                                                                                                                                                                                                         })"
                                    x-init="init()">
                                    <span class="field-label">Departing</span>
                                    <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text"
                                        inputmode="none" readonly :value="display || ''" placeholder="mm/dd/yyyy"
                                        @click="open = true">

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

                                <div wire:key="return-arrival-picker-{{ $returnRetDate ?: 'empty' }}-{{ $errors->has('returnRetDate') ? 'error' : 'ok' }}"
                                    x-data="singleDatePicker({
                                                                                                                                                                                                                                                                                                                                                                            value: @js($returnRetDate),
                                                                                                                                                                                                                                                                                                                                                                            wireValueKey: 'returnRetDate',
                                                                                                                                                                                                                                                                                                                                                                            title: 'When would you like to return?',
                                                                                                                                                                                                                                                                                                                                                                        })"
                                    x-init="init()">
                                    <span class="field-label">Returning</span>
                                    <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text"
                                        inputmode="none" readonly :value="display || ''" placeholder="mm/dd/yyyy"
                                        @click="open = true">

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
                            <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px;">
                                <div>@error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                                <div></div>
                                <div>@error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                            </div>
                        </div>

                        </div>

                        {{-- Row 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

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
                                                    class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs' }}">
                                                    {{ $label }}
                                                </span>
                                                @if($isSelected)
                                                    <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor"
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

                        {{-- Search Button --}}
                        <div class="flex justify-end mt-4 {{ !empty($quick) ? 'qs-search-button-wrap' : '' }}">
                            <button class="btn-search" x-data="{ searching: false }"
                                @click="searching = true; $wire.search().finally(() => { searching = false })" :disabled="searching"
                                wire:loading.attr="disabled">
                                {{-- Icon --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                                    </path>
                                </svg>
                                <span>Search flights</span>
                            </button>
                        </div>
                    </div>


                </div>

            </div>
        @endif

{{-- ══════════════════════════════════════
PANEL: ONE WAY
══════════════════════════════════════ --}}
@if($tripType === 'oneway')
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

            <div>
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                <span class="field-label">Departure airport</span>
                <input class="field-input" type="text" wire:model.live.debounce.150ms="onewayDep"
                    wire:key="oneway-dep-input" @focus="show = true" placeholder="City or airport" autocomplete="off">
                @if($onewayDep)
                    <button class="field-clear" wire:click.stop="$set('onewayDep', '')" title="Clear">×</button>
                @endif

                <div x-cloak x-show="show"
                    class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                        @if($searchType === 'onewayDep')
                            @forelse($items as $a)
                                @php
                                    $display = $a['city'] . ' (' . $a['code'] . ')';
                                @endphp
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click.stop="selectOnewayDepAirport('{{ $display }}')" @click="show = false">
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
                @error('onewayDep') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                <span class="field-label">Arrival airport</span>
                <input class="field-input" type="text" wire:model.live.debounce.150ms="onewayArr"
                    wire:key="oneway-arr-input" @focus="show = true" placeholder="City or airport" autocomplete="off">

                <div x-cloak x-show="show"
                    class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                        @if($searchType === 'onewayArr')
                            @forelse($items as $a)
                                @php
                                    $display = $a['city'] . ' (' . $a['code'] . ')';
                                @endphp
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click.stop="selectOnewayArrAirport('{{ $display }}')" @click="show = false">
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
                @error('onewayArr') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="field-wrap"
                    x-data="singleDatePicker({
                                                                                                                                                                                                                     value: @js($onewayDepDate),
                                                                                                                                                                                                                     wireValueKey: 'onewayDepDate',
                                                                                                                                                                                                                     title: 'Please choose your departure date',
                                                                                                                                                                                                                 })"
                    x-init="init()">
                    <span class="field-label">Departing</span>
                    <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text" inputmode="none"
                        readonly :value="display || ''" placeholder="mm/dd/yyyy" @click="open = true">

                {{-- Calendar modal --}}
                <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center" aria-modal="true"
                    role="dialog">
                    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                    <div
                        class="relative w-[92vw] max-w-lg max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="text-lg font-medium text-gray-800 text-center" x-text="m.title">
                                            </p>
                                            <div class="w-10 flex justify-end">
                                                <button type="button"
                                                    class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                    @click.prevent="nextMonth()">
                                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1.5 text-xs text-gray-500 mb-2">
                                            <template x-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']" :key="d">
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
                @error('onewayDepDate') <span class="field-error">{{ $message }}</span> @enderror
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Passengers</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span
                        class="text-gray-900">{{ $this->paxSummary($onewayAdults, $onewayChildren, $onewayInfants) }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementOnewayPax('adult')" @disabled($onewayAdults <= 1)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $onewayAdults }} Adult</div>
                                <div class="text-xs text-gray-500">Ages 12+</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementOnewayPax('adult')" @disabled(($onewayAdults + $onewayChildren + $onewayInfants) >= 9)>
                                <span class="text-xl leading-none">+</span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementOnewayPax('child')" @disabled($onewayChildren <= 0)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $onewayChildren }} Child</div>
                                <div class="text-xs text-gray-500">Ages 2-11</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementOnewayPax('child')" @disabled(($onewayAdults + $onewayChildren + $onewayInfants) >= 9)>
                                <span class="text-xl leading-none">+</span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementOnewayPax('infant')" @disabled($onewayInfants <= 0)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $onewayInfants }} Infant</div>
                                <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementOnewayPax('infant')" @disabled(($onewayAdults + $onewayChildren + $onewayInfants) >= 9 || $onewayInfants >= $onewayAdults)>
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

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Class</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span class="text-gray-900">{{ $onewayClass }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                            @php $isSelected = $onewayClass === $class; @endphp
                            <button type="button"
                                class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                wire:click="$set('onewayClass', '{{ $class }}')" @click="open = false">
                                <span class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium' }}">
                                    {{ $class }}
                                </span>
                                @if($isSelected)
                                    <svg class="w-5 h-5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Currency</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span class="text-gray-900">{{ $currency }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                                    class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs' }}">
                                    {{ $label }}
                                </span>
                                @if($isSelected)
                                    <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Search Button --}}
        <div class="flex justify-end mt-4 {{ !empty($quick) ? 'qs-search-button-wrap' : '' }}">
            <div class="{{ !empty($quick) ? 'w-auto' : 'w-full md:w-1/3 text-end' }}">
                <button class="btn-search" x-data="{ searching: false }"
                    @click="searching = true; $wire.search().finally(() => { searching = false })" :disabled="searching"
                    wire:loading.attr="disabled">
                    <div class="flex items-center justify-center gap-2">
                        {{-- Icon --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                            </path>
                        </svg>

                        <span>Search flights</span>
                    </div>
                </button>
            </div>
        </div>


    </div>
@endif

{{-- ══════════════════════════════════════
PANEL: MULTI-CITY
══════════════════════════════════════ --}}
@if($tripType === 'multi')
    <div>
        <div class="space-y-4 mb-4">
            @foreach($multiFlights as $index => $flight)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-2">Flight {{ $index + 1 }}</p>
                    <div class="mc-row">

                        <div>
                            <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                                @click.outside="show = false">
                            <span class="field-label">Departure airport</span>
                            <input class="field-input" type="text"
                                wire:model.live.debounce.150ms="multiFlights.{{ $index }}.dep"
                                wire:key="multi-dep-input-{{ $index }}" @focus="show = true" placeholder="City or airport"
                                autocomplete="off">
                            @if($flight['dep'] && $index === 0)
                                <button class="field-clear" wire:click.stop="$set('multiFlights.0.dep', '')"
                                    title="Clear">×</button>
                            @endif

                            <div x-cloak x-show="show"
                                class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                                        $q = $multiFlights[$index]['dep'] ?? '';
                                        $items = $this->airportSearchResults;
                                    @endphp
                                    @if($searchType === "multi.$index.dep")
                                        @forelse($items as $a)
                                            @php
                                                $display = $a['city'] . ' (' . $a['code'] . ')';
                                            @endphp
                                            <button type="button"
                                                class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                                wire:click.stop="selectMultiDepAirport({{ $index }}, '{{ $display }}')"
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
                            @error("multiFlights.$index.dep") <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                                @click.outside="show = false">
                            <span class="field-label">Arrival airport</span>
                            <input class="field-input" type="text"
                                wire:model.live.debounce.150ms="multiFlights.{{ $index }}.arr"
                                wire:key="multi-arr-input-{{ $index }}" @focus="show = true" placeholder="City or airport"
                                autocomplete="off">

                            <div x-cloak x-show="show"
                                class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
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
                                        $q = $multiFlights[$index]['arr'] ?? '';
                                        $items = $this->airportSearchResults;
                                    @endphp
                                    @if($searchType === "multi.$index.arr")
                                        @forelse($items as $a)
                                            @php
                                                $display = $a['city'] . ' (' . $a['code'] . ')';
                                            @endphp
                                            <button type="button"
                                                class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                                wire:click.stop="selectMultiArrAirport({{ $index }}, '{{ $display }}')"
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
                            @error("multiFlights.$index.arr") <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <div class="field-wrap"
                                x-data="singleDatePicker({
                                                                                                                                                                                                                                                                                                                                                                                                                                  value: @js($multiFlights[$index]['date'] ?? ''),
                                                                                                                                                                                                                                                                                                                                                                                                                                  wireValueKey: 'multiFlights.{{ $index }}.date',
                                                                                                                                                                                                                                                                                                                                                                                                                                  title: 'Please choose your departure date',
                                                                                                                                                                                                                                                                                                                                                                                                                             })"
                                x-init="init()">
                                <span class="field-label">Departing</span>
                                <input class="field-input date-input" :class="display ? 'has-val' : ''" type="text" inputmode="none"
                                    readonly :value="display || ''" placeholder="mm/dd/yyyy" @click="open = true">

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
                            @error("multiFlights.$index.date") <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Remove button (only for flight 3+) --}}
                        @if($index >= 2)
                            <button class="mc-remove" wire:click="removeFlight({{ $index }})" title="Remove flight">×</button>
                        @else
                            <div></div>{{-- spacer --}}
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Add flight --}}
	        @if($this->canAddFlight)
	            <button class="btn-add-flight mb-5" wire:click="addFlight">
	                <span class="add-icon" aria-hidden="true"></span>
	                Add a flight
	            </button>
	        @endif

        {{-- Passengers / Class / Search --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Passengers</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span class="text-gray-900">{{ $this->paxSummary($multiAdults, $multiChildren, $multiInfants) }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementMultiPax('adult')" @disabled($multiAdults <= 1)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $multiAdults }} Adult</div>
                                <div class="text-xs text-gray-500">Ages 12+</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementMultiPax('adult')" @disabled(($multiAdults + $multiChildren + $multiInfants) >= 9)>
                                <span class="text-xl leading-none">+</span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementMultiPax('child')" @disabled($multiChildren <= 0)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $multiChildren }} Child</div>
                                <div class="text-xs text-gray-500">Ages 2-11</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementMultiPax('child')" @disabled(($multiAdults + $multiChildren + $multiInfants) >= 9)>
                                <span class="text-xl leading-none">+</span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="decrementMultiPax('infant')" @disabled($multiInfants <= 0)>
                                <span class="text-xl leading-none">−</span>
                            </button>
                            <div class="text-center">
                                <div class="text-base font-semibold text-gray-900">{{ $multiInfants }} Infant</div>
                                <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                            </div>
                            <button type="button"
                                class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                wire:click="incrementMultiPax('infant')" @disabled(($multiAdults + $multiChildren + $multiInfants) >= 9 || $multiInfants >= $multiAdults)>
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

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Class</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span class="text-gray-900">{{ $multiClass }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                            @php $isSelected = $multiClass === $class; @endphp
                            <button type="button"
                                class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                wire:click="$set('multiClass', '{{ $class }}')" @click="open = false">
                                <span class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium' }}">
                                    {{ $class }}
                                </span>
                                @if($isSelected)
                                    <svg class="w-5 h-5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="field-wrap" style="position:relative;" x-data="{ open: false }" @click.outside="open = false">
                <span class="field-label">Currency</span>

                <button type="button" class="field-select text-left w-full flex items-center justify-between"
                    @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                    <span class="text-gray-900">{{ $currency }}</span>
                    <span class="select-arrow static">
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                                    class="{{ $isSelected ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs' }}">
                                    {{ $label }}
                                </span>
                                @if($isSelected)
                                    <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Search Button --}}
        <div class="flex justify-end mt-4 {{ !empty($quick) ? 'qs-search-button-wrap' : '' }}">
            <div class="{{ !empty($quick) ? 'w-auto' : 'w-full md:w-1/3 text-end' }}">
                <button class="btn-search" x-data="{ searching: false }"
                    @click="searching = true; $wire.search().finally(() => { searching = false })" :disabled="searching"
                    wire:loading.attr="disabled">
                    <div class="flex items-center justify-center gap-2">
                        {{-- Icon --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                            </path>
                        </svg>

                        <span>Search flights</span>
                    </div>
                </button>
            </div>
        </div>


    </div>
@endif

</div>{{-- /search-card --}}
</div>

<script>
    window.dateRangePicker = function (opts) {
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
            active: 'dep',
            dep: opts?.dep ? fmt(opts.dep) : '',
            ret: opts?.ret ? fmt(opts.ret) : '',
            depIso: opts?.dep || '',
            retIso: opts?.ret || '',
            base: null,
            months: [],
            minIso: toIso(startOfDay(new Date())),

            init() {
                const baseIso = this.depIso || this.minIso;
                const baseDate = parseIso(baseIso) || new Date();
                this.base = new Date(baseDate.getFullYear(), baseDate.getMonth(), 1);
                this.months = [];
                this.refreshMonths();
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
                const m2Date = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
                const m2 = this.buildMonth(m2Date.getFullYear(), m2Date.getMonth());
                // Always exactly 2 months on screen
                this.months = [m1, m2];
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

                // Always render 6 rows (42 cells) for consistent layout like Emirates
                while (cells.length < 42) {
                    cells.push({ key: `${year}-${monthIndex}-tail-${cells.length}`, day: null, iso: null, disabled: true });
                }

                return { key: `${year}-${monthIndex}`, title, cells };
            },

            pick(iso) {
                if (!iso || iso < this.minIso) return;

                // If user clicks Departing explicitly or both dates already selected, start a new range.
                if (this.active === 'dep' || (this.depIso && this.retIso)) {
                    this.depIso = iso;
                    this.retIso = '';
                    this.active = 'ret';
                } else {
                    if (iso < this.depIso) {
                        this.depIso = iso;
                        this.retIso = '';
                        this.active = 'ret';
                    } else {
                        this.retIso = iso;
                        this.active = 'dep';
                    }
                }

                this.dep = fmt(this.depIso);
                this.ret = fmt(this.retIso);
            },

            apply() {
                if (!this.$wire) return;
                this.$wire.$set('returnDepDate', this.depIso);
                this.$wire.$set('returnRetDate', this.retIso);
            },

            dayClass(cell) {
                if (!cell.day) return 'text-transparent';
                if (cell.disabled) return 'text-gray-300 cursor-not-allowed';

                const iso = cell.iso;
                const isDep = this.depIso && iso === this.depIso;
                const isRet = this.retIso && iso === this.retIso;
                const inRange = this.depIso && this.retIso && iso > this.depIso && iso < this.retIso;

                if (isDep || isRet) return 'bg-[#2ab4c0] text-white';
                if (inRange) return 'bg-blue-50 text-gray-900';
                return 'text-gray-900 hover:bg-gray-100';
            },
        };
    }

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
            wireValueKey: opts?.wireValueKey || '',
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
                const m2Date = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
                const m2 = this.buildMonth(m2Date.getFullYear(), m2Date.getMonth());
                this.months = [m1, m2];
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
            },

            dayClass(cell) {
                if (!cell.day) return 'text-transparent';
                if (cell.disabled) return 'text-gray-300 cursor-not-allowed';
                if (this.iso && cell.iso === this.iso) return 'bg-[#2ab4c0] text-whit           e';
                return 'text-gray-900 hover:bg-gray-100';
            },
        };
    }
</script>
