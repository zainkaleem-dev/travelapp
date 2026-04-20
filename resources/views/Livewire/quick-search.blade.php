<div
    class="{{ empty($quick) ? 'search-card' : 'bg-white rounded-xl border border-gray-200 shadow-sm p-4 w-full mb-4' }}">

    <div class="mb-3 flex items-start justify-between gap-3">
        {{-- Trip type tabs --}}
        <div class="trip-tabs !mb-0">
            <button class="trip-tab {{ $tripType === 'return' ? 'active' : '' }}"
                wire:click="switchTab('return')">Return</button>
            <button class="trip-tab {{ $tripType === 'oneway' ? 'active' : '' }}" wire:click="switchTab('oneway')">One
                way</button>
            <button class="trip-tab {{ $tripType === 'multi' ? 'active' : '' }}"
                wire:click="switchTab('multi')">Multi-city</button>
        </div>
    </div>



    {{-- ══════════════════════════════════════
    PANEL: RETURN
    ══════════════════════════════════════ --}}
    @if($tripType === 'return')
        <div wire:key="trip-panel-return" class="">
            {{-- Row 1 --}}
            <div
                class="flex items-center md:space-y-0 space-y-4 md:space-x-4 space-x-0 md:flex-row flex-col justify-between mb-3">

                {{-- Departure --}}
                <div>
                    <div class="field-wrap has-icon-right {{ $returnDep ? 'has-clear' : '' }}" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">From</span>
                        <span class="field-icon right" aria-hidden="true"
                            style="color: {{ $returnDep ? '#2ab4c0' : '#9ca3af' }};">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                            </svg>
                        </span>
                        <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnDep"
                            wire:key="return-dep-input" @focus="show = true" placeholder="City or airport"
                            autocomplete="off">
                        @if($returnDep)
                            <button class="field-clear" wire:click.stop="$set('returnDep', '')" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>Search locations</span>
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
                {{-- Swap Button --}}
                <button type="button" wire:click="swapReturnAirports"
                    class="m-0 flex z-50 px-2 w-8 h-8 items-center justify-center bg-white border border-gray-200 rounded-full shadow-sm hover:border-[#2ab4c0] hover:text-[#2ab4c0] transition-all group"
                    title="Swap locations">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </button>
                {{-- Arrival --}}
                <div>
                    <div class="field-wrap has-icon-right {{ $returnArr ? 'has-clear' : '' }}" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">To</span>
                        <span class="field-icon right" aria-hidden="true"
                            style="color: {{ $returnArr ? '#2ab4c0' : '#9ca3af' }};">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                            </svg>
                        </span>
                        <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnArr"
                            wire:key="return-arr-input" @focus="show = true" placeholder="City or airport"
                            autocomplete="off">
                        @if($returnArr)
                            <button class="field-clear" wire:click.stop="$set('returnArr', '')" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>Search locations</span>
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
                <div wire:key="return-date-range-picker-{{ $returnDepDate ?: 'empty' }}-{{ $returnRetDate ?: 'empty' }}-{{ ($errors->has('returnDepDate') || $errors->has('returnRetDate')) ? 'error' : 'ok' }}"
                    x-data="dateRangePicker({ dep: @js($returnDepDate), ret: @js($returnRetDate) })" x-init="init()">
                    <div class="field-wrap"
                        style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                        <div>
                            <span class="field-label">Departing</span>
                            <input class="field-input date-input" :class="dep ? 'has-val' : ''" type="text" inputmode="none"
                                readonly :value="dep || ''" placeholder="mm/dd/yyyy" @click="active = 'dep'; open = true">
                        </div>
                        <span style="color:#9ca3af; font-size:18px; padding:0 4px; margin-top:10px;">&ndash;</span>
                        <div>
                            <span class="field-label">Returning</span>
                            <input class="field-input date-input" :class="ret ? 'has-val' : ''" type="text" inputmode="none"
                                readonly :value="ret || ''" placeholder="mm/dd/yyyy"
                                @click="active = (depIso ? 'ret' : 'dep'); open = true">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px;">
                        <div>@error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                        <div></div>
                        <div>@error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                    </div>

                    {{-- Calendar modal --}}
                    <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center"
                        aria-modal="true" role="dialog">
                        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                        <div
                            class="relative w-[92vw] max-w-3xl max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-base font-medium text-gray-800">Select your travel dates</p>
                                        <p class="text-xs text-gray-500"
                                            x-text="active === 'dep' ? 'Pick departing date' : 'Pick returning date'">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 overflow-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <template x-for="(m, idx) in months" :key="m.key">
                                        <div>
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="w-10">
                                                    <button type="button"
                                                        class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                        x-show="idx === 0" @click.prevent="prevMonth()">
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
                                                        x-show="idx === 1" @click.prevent="nextMonth()">
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
                                                        class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition flex flex-col items-center justify-center leading-none"
                                                        @mouseenter="hoveredIso = cell.iso" @mouseleave="hoveredIso = null"
                                                        @click="cell.day && pick(cell.iso)" :class="dayClass(cell)">
                                                        <span x-text="cell.day || ''"></span>
                                                        <span x-show="depIso && cell.iso === depIso" class="-mt-0.5"
                                                            aria-hidden="true">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                                <path
                                                                    d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                                                            </svg>
                                                        </span>
                                                        <span x-show="retIso && cell.iso === retIso" class="-mt-0.5"
                                                            aria-hidden="true">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                                <path
                                                                    d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                <div class="text-xs text-gray-500">
                                    <span x-text="dep ? ('Departing: ' + dep) : 'Departing: \\u2014'"></span>
                                    <span class="mx-2">&bull;</span>
                                    <span x-text="ret ? ('Returning: ' + ret) : 'Returning: \\u2014'"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        @click="open = false">Close</button>
                                    <button type="button"
                                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#2ab4c0] hover:bg-[#239ea9] disabled:opacity-50"
                                        :disabled="!depIso || !retIso" @click="apply(); open = false">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(false)
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
                @endif

            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-stretch return-second-row">

                {{-- Passengers --}}
                <div class="field-wrap" style="position:relative;"
                    x-data="paxDropdown({ adults: @entangle('returnAdults'), children: @entangle('returnChildren'), infants: @entangle('returnInfants') })"
                    @click.outside="open = false">
                    <span class="field-label">Passengers</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="summary"></span>
                        <span class="select-arrow static">
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-cloak x-show="open" x-transition
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-700">Passengers</p>
                            <div class="h-px bg-gray-100 mt-2"></div>
                        </div>
                        <div class="px-4 pb-3 space-y-4">
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('adult')" :disabled="adults <= 1">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="adults"></span>
                                        Adult</div>
                                    <div class="text-xs text-gray-500">Ages 12+</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('adult')" :disabled="total >= 9">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('child')" :disabled="children <= 0">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="children"></span>
                                        Child</div>
                                    <div class="text-xs text-gray-500">Ages 2-11</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('child')" :disabled="total >= 9">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('infant')" :disabled="infants <= 0">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="infants"></span>
                                        Infant</div>
                                    <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('infant')" :disabled="total >= 9 || infants >= adults">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <p class="text-xs text-gray-600">Please note: You can book for a maximum of nine passengers.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Class --}}
                <div class="field-wrap" style="position:relative;"
                    x-data="{ open: false, selected: @entangle('returnClass') }" @click.outside="open = false">
                    <span class="field-label">Class</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="selected"></span>
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
                        <div class="py-2">
                            @foreach(['Economy Class', 'Premium Economy', 'Business Class', 'First Class'] as $class)
                                <button type="button"
                                    class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                    @click.prevent="selected = '{{ $class }}'; open = false">
                                    <span
                                        :class="selected === '{{ $class }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium'">
                                        {{ $class }}
                                    </span>
                                    <svg x-cloak x-show="selected === '{{ $class }}'" class="w-5 h-5 text-[#2ab4c0]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Currency --}}
                <div class="field-wrap" style="position:relative;" x-data="{ open: false, selected: @entangle('currency') }"
                    @click.outside="open = false">
                    <span class="field-label">Currency</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="selected"></span>
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
                        <div class="py-2 max-h-72 overflow-auto">
                            @foreach($currencies as $code => $label)
                                <button type="button"
                                    class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                    @click.prevent="selected = '{{ $code }}'; open = false">
                                    <span
                                        :class="selected === '{{ $code }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs'">
                                        {{ $label }}
                                    </span>
                                    <svg x-cloak x-show="selected === '{{ $code }}'" class="w-4 h-4 text-[#2ab4c0]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Users --}}
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                    <span class="field-label">Users</span>
                    <input class="field-input" type="text" wire:model.live.debounce.150ms="returnUserSearch"
                        wire:key="return-main-usersearch-input" @focus="show = true" placeholder="Search users"
                        autocomplete="off">

                    @if($returnUserSearch)
                        <button class="field-clear" wire:click.stop="clearReturnUser()" title="Clear">×</button>
                    @endif

                    <div x-cloak x-show="show"
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="9" cy="7" r="4" stroke-width="2" />
                                </svg>
                                <span>Search users</span>
                            </div>
                        </div>
                        <div class="h-px bg-gray-100"></div>
                        <div class="max-h-72 overflow-auto">
                            @php
                                $items = $this->returnUserSearchResults;
                            @endphp
                            @forelse($items as $u)
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click.stop="selectReturnUser({{ $u['id'] }})" @click="show = false">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $u['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $u['email'] }}</div>
                                    </div>

                                </button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">No users</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Airlines --}}
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                    <span class="field-label">Airlines</span>
                    <input class="field-input" type="text" wire:model.live.debounce.150ms="returnAirlineSearch"
                        wire:key="return-main-airlinesearch-input" @focus="show = true" placeholder="Search airline"
                        autocomplete="off">

                    @if($returnAirlineSearch)
                        <button class="field-clear" wire:click.stop="clearReturnAirline()" title="Clear">×</button>
                    @endif

                    <div x-cloak x-show="show"
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2-7h-4l-2 2h-3l2-4-2-4h3l2 2h4l-2-7h3z" />
                                </svg>
                                <span>Search airlines</span>
                            </div>
                        </div>
                        <div class="h-px bg-gray-100"></div>
                        <div class="max-h-72 overflow-auto">
                            @forelse($this->returnAirlineSearchResults as $a)
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between group transition-colors"
                                    wire:click.stop="selectReturnAirline('{{ $a['code'] }}', '{{ $a['name'] }}')"
                                    @click="show = false">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 group-hover:bg-white transition-colors">
                                            <img src="https://www.gstatic.com/flights/airline_logos/70px/{{ $a['code'] }}.png"
                                                alt="{{ $a['name'] }}"
                                                onerror="this.src='https://pics.avs.io/128/128/{{ $a['code'] }}.png'"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-semibold text-gray-800 group-hover:text-[#2ab4c0] transition-colors">
                                                {{ $a['name'] }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">
                                                {{ $a['code'] }}
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">No airlines</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>


            {{-- Search Button --}}
            <div class="flex justify-end mt-4">
                <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                    {{-- Spinner (visible only when loading) --}}
                    <svg wire:loading wire:target="search" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>

                    {{-- Search Icon (hidden when loading) --}}
                    <svg wire:loading.remove wire:target="search" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                        </path>
                    </svg>

                    <span wire:loading.remove wire:target="search">Search flights</span>
                    <span wire:loading wire:target="search">Searching...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════
    PANEL: ONE WAY
    ══════════════════════════════════════ --}}
    @if($tripType === 'oneway')
        <div wire:key="trip-panel-oneway" class="">
            <div
                class="flex items-center md:space-y-0 space-y-4 md:space-x-4 space-x-0 md:flex-row flex-col justify-between mb-3">

                {{-- Departure --}}
                <div>
                    <div class="field-wrap has-icon-right {{ $returnDep ? 'has-clear' : '' }}" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">From</span>
                        <span class="field-icon right" aria-hidden="true"
                            style="color: {{ $returnDep ? '#2ab4c0' : '#9ca3af' }};">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                            </svg>
                        </span>
                        <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnDep"
                            wire:key="return-dep-input" @focus="show = true" placeholder="City or airport"
                            autocomplete="off">
                        @if($returnDep)
                            <button class="field-clear" wire:click.stop="$set('returnDep', '')" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>Search locations</span>
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
                {{-- Swap Button --}}
                <button type="button" wire:click="swapReturnAirports"
                    class="m-0 flex z-50 px-2 w-8 h-8 items-center justify-center bg-white border border-gray-200 rounded-full shadow-sm hover:border-[#2ab4c0] hover:text-[#2ab4c0] transition-all group"
                    title="Swap locations">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </button>
                {{-- Arrival --}}
                <div>
                    <div class="field-wrap has-icon-right {{ $returnArr ? 'has-clear' : '' }}" style="position:relative;"
                        x-data="{ show: false }" @click.outside="show = false">
                        <span class="field-label">To</span>
                        <span class="field-icon right" aria-hidden="true"
                            style="color: {{ $returnArr ? '#2ab4c0' : '#9ca3af' }};">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                            </svg>
                        </span>
                        <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnArr"
                            wire:key="return-arr-input" @focus="show = true" placeholder="City or airport"
                            autocomplete="off">
                        @if($returnArr)
                            <button class="field-clear" wire:click.stop="$set('returnArr', '')" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2" />
                                    </svg>
                                    <span>Search locations</span>
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
                <div wire:key="return-date-range-picker-{{ $returnDepDate ?: 'empty' }}-{{ $returnRetDate ?: 'empty' }}-{{ ($errors->has('returnDepDate') || $errors->has('returnRetDate')) ? 'error' : 'ok' }}"
                    x-data="dateRangePicker({ dep: @js($returnDepDate), ret: @js($returnRetDate) })" x-init="init()">
                    <div class="field-wrap"
                        style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                        <div>
                            <span class="field-label">Departing</span>
                            <input class="field-input date-input" :class="dep ? 'has-val' : ''" type="text" inputmode="none"
                                readonly :value="dep || ''" placeholder="mm/dd/yyyy" @click="active = 'dep'; open = true">
                        </div>
                        <span style="color:#9ca3af; font-size:18px; padding:0 4px; margin-top:10px;">&ndash;</span>
                        <div>
                            <span class="field-label">Returning</span>
                            <input class="field-input date-input" :class="ret ? 'has-val' : ''" type="text" inputmode="none"
                                readonly :value="ret || ''" placeholder="mm/dd/yyyy"
                                @click="active = (depIso ? 'ret' : 'dep'); open = true">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px;">
                        <div>@error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                        <div></div>
                        <div>@error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror</div>
                    </div>

                    {{-- Calendar modal --}}
                    <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center"
                        aria-modal="true" role="dialog">
                        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                        <div
                            class="relative w-[92vw] max-w-3xl max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-base font-medium text-gray-800">Select your travel dates</p>
                                        <p class="text-xs text-gray-500"
                                            x-text="active === 'dep' ? 'Pick departing date' : 'Pick returning date'">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 overflow-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <template x-for="(m, idx) in months" :key="m.key">
                                        <div>
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="w-10">
                                                    <button type="button"
                                                        class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                        x-show="idx === 0" @click.prevent="prevMonth()">
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
                                                        x-show="idx === 1" @click.prevent="nextMonth()">
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
                                                        class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition flex flex-col items-center justify-center leading-none"
                                                        @mouseenter="hoveredIso = cell.iso" @mouseleave="hoveredIso = null"
                                                        @click="cell.day && pick(cell.iso)" :class="dayClass(cell)">
                                                        <span x-text="cell.day || ''"></span>
                                                        <span x-show="depIso && cell.iso === depIso" class="-mt-0.5"
                                                            aria-hidden="true">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                                <path
                                                                    d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                                                            </svg>
                                                        </span>
                                                        <span x-show="retIso && cell.iso === retIso" class="-mt-0.5"
                                                            aria-hidden="true">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                                <path
                                                                    d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                <div class="text-xs text-gray-500">
                                    <span x-text="dep ? ('Departing: ' + dep) : 'Departing: \\u2014'"></span>
                                    <span class="mx-2">&bull;</span>
                                    <span x-text="ret ? ('Returning: ' + ret) : 'Returning: \\u2014'"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        @click="open = false">Close</button>
                                    <button type="button"
                                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#2ab4c0] hover:bg-[#239ea9] disabled:opacity-50"
                                        :disabled="!depIso || !retIso" @click="apply(); open = false">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(false)
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
                @endif

            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-stretch oneway-second-row">

                {{-- Passengers --}}
                <div class="field-wrap" style="position:relative;"
                    x-data="paxDropdown({ adults: @entangle('onewayAdults'), children: @entangle('onewayChildren'), infants: @entangle('onewayInfants') })"
                    @click.outside="open = false">
                    <span class="field-label">Passengers</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="summary"></span>
                        <span class="select-arrow static">
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-cloak x-show="open" x-transition
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-700">Passengers</p>
                            <div class="h-px bg-gray-100 mt-2"></div>
                        </div>
                        <div class="px-4 pb-3 space-y-4">
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('adult')" :disabled="adults <= 1">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="adults"></span>
                                        Adult</div>
                                    <div class="text-xs text-gray-500">Ages 12+</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('adult')" :disabled="total >= 9">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('child')" :disabled="children <= 0">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="children"></span>
                                        Child</div>
                                    <div class="text-xs text-gray-500">Ages 2-11</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('child')" :disabled="total >= 9">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="button"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="dec('infant')" :disabled="infants <= 0">
                                    <span class="text-xl leading-none">−</span>
                                </button>
                                <div class="text-center">
                                    <div class="text-base font-semibold text-gray-900"><span x-text="infants"></span>
                                        Infant</div>
                                    <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                                </div>
                                <button type="button"
                                    class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                    @click.prevent="inc('infant')" :disabled="total >= 9 || infants >= adults">
                                    <span class="text-xl leading-none">+</span>
                                </button>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <p class="text-xs text-gray-600">Please note: You can book for a maximum of nine passengers.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Class --}}
                <div class="field-wrap" style="position:relative;"
                    x-data="{ open: false, selected: @entangle('onewayClass') }" @click.outside="open = false">
                    <span class="field-label">Class</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="selected"></span>
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
                        <div class="py-2">
                            @foreach(['Economy Class', 'Premium Economy', 'Business Class', 'First Class'] as $class)
                                <button type="button"
                                    class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                    @click.prevent="selected = '{{ $class }}'; open = false">
                                    <span
                                        :class="selected === '{{ $class }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium'">
                                        {{ $class }}
                                    </span>
                                    <svg x-cloak x-show="selected === '{{ $class }}'" class="w-5 h-5 text-[#2ab4c0]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Currency --}}
                <div class="field-wrap" style="position:relative;" x-data="{ open: false, selected: @entangle('currency') }"
                    @click.outside="open = false">
                    <span class="field-label">Currency</span>
                    <button type="button" class="field-select text-left w-full flex items-center justify-between"
                        @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                        <span class="text-black" x-text="selected"></span>
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
                        <div class="py-2 max-h-72 overflow-auto">
                            @foreach($currencies as $code => $label)
                                <button type="button"
                                    class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                    @click.prevent="selected = '{{ $code }}'; open = false">
                                    <span
                                        :class="selected === '{{ $code }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs'">
                                        {{ $label }}
                                    </span>
                                    <svg x-cloak x-show="selected === '{{ $code }}'" class="w-4 h-4 text-[#2ab4c0]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Users --}}
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                    <span class="field-label">Users</span>
                    <input class="field-input" type="text" wire:model.live.debounce.150ms="onewayUserSearch"
                        wire:key="oneway-main-usersearch-input" @focus="show = true" placeholder="Search users"
                        autocomplete="off">

                    @if($onewayUserSearch)
                        <button class="field-clear" wire:click.stop="clearOnewayUser()" title="Clear">×</button>
                    @endif

                    <div x-cloak x-show="show"
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="9" cy="7" r="4" stroke-width="2" />
                                </svg>
                                <span>Search users</span>
                            </div>
                        </div>
                        <div class="h-px bg-gray-100"></div>
                        <div class="max-h-72 overflow-auto">
                            @php
                                $items = $this->onewayUserSearchResults;
                            @endphp
                            @forelse($items as $u)
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                    wire:click.stop="selectOnewayUser({{ $u['id'] }})" @click="show = false">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $u['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $u['email'] }}</div>
                                    </div>

                                </button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">No users</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Airlines --}}
                <div class="field-wrap" style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                    <span class="field-label">Airlines</span>
                    <input class="field-input" type="text" wire:model.live.debounce.150ms="onewayAirlineSearch"
                        wire:key="oneway-main-airlinesearch-input" @focus="show = true" placeholder="Search airline"
                        autocomplete="off">

                    @if($onewayAirlineSearch)
                        <button class="field-clear" wire:click.stop="clearOnewayAirline()" title="Clear">×</button>
                    @endif

                    <div x-cloak x-show="show"
                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                        style="min-width: 0;">
                        <div class="px-4 py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2-7h-4l-2 2h-3l2-4-2-4h3l2 2h4l-2-7h3z" />
                                </svg>
                                <span>Search airlines</span>
                            </div>
                        </div>
                        <div class="h-px bg-gray-100"></div>
                        <div class="max-h-72 overflow-auto">
                            @forelse($this->onewayAirlineSearchResults as $a)
                                <button type="button"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between group transition-colors"
                                    wire:click.stop="selectOnewayAirline('{{ $a['code'] }}', '{{ $a['name'] }}')"
                                    @click="show = false">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 group-hover:bg-white transition-colors">
                                            <img src="https://www.gstatic.com/flights/airline_logos/70px/{{ $a['code'] }}.png"
                                                alt="{{ $a['name'] }}"
                                                onerror="this.src='https://pics.avs.io/128/128/{{ $a['code'] }}.png'"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-semibold text-gray-800 group-hover:text-[#2ab4c0] transition-colors">
                                                {{ $a['name'] }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">
                                                {{ $a['code'] }}
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">No airlines</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- Search Button --}}
            <div class="flex justify-end mt-4">
                <div class="">
                    <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Spinner (visible only when loading) --}}
                            <svg wire:loading wire:target="search" class="animate-spin w-4 h-4" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>

                            {{-- Search Icon (hidden when loading) --}}
                            <svg wire:loading.remove wire:target="search" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                                </path>
                            </svg>

                            <span wire:loading.remove wire:target="search">Search flights</span>
                            <span wire:loading wire:target="search">Searching...</span>
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
        <div wire:key="trip-panel-multi">
            <div class="space-y-4 mb-4">
                @foreach($multiFlights as $index => $flight)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">Flight {{ $index + 1 }}</p>
                        <div
                            class="flex items-center md:space-y-0 space-y-4 md:space-x-4 space-x-0 md:flex-row flex-col justify-between mb-3">

                            {{-- Departure --}}
                            <div>
                                <div class="field-wrap has-icon-right {{ $returnDep ? 'has-clear' : '' }}"
                                    style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                                    <span class="field-label">From</span>
                                    <span class="field-icon right" aria-hidden="true"
                                        style="color: {{ $returnDep ? '#2ab4c0' : '#9ca3af' }};">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                                        </svg>
                                    </span>
                                    <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnDep"
                                        wire:key="return-dep-input" @focus="show = true" placeholder="City or airport"
                                        autocomplete="off">
                                    @if($returnDep)
                                        <button class="field-clear" wire:click.stop="$set('returnDep', '')" title="Clear">×</button>
                                    @endif

                                    <div x-cloak x-show="show"
                                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                                        style="min-width: 0;">
                                        <div class="px-4 py-3">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                                    <circle cx="12" cy="11" r="2" />
                                                </svg>
                                                <span>Search locations</span>
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
                                @error('returnDep') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                            {{-- Swap Button --}}
                            <button type="button" wire:click="swapReturnAirports"
                                class="m-0 flex z-50 px-2 w-8 h-8 items-center justify-center bg-white border border-gray-200 rounded-full shadow-sm hover:border-[#2ab4c0] hover:text-[#2ab4c0] transition-all group"
                                title="Swap locations">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                            {{-- Arrival --}}
                            <div>
                                <div class="field-wrap has-icon-right {{ $returnArr ? 'has-clear' : '' }}"
                                    style="position:relative;" x-data="{ show: false }" @click.outside="show = false">
                                    <span class="field-label">To</span>
                                    <span class="field-icon right" aria-hidden="true"
                                        style="color: {{ $returnArr ? '#2ab4c0' : '#9ca3af' }};">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                                        </svg>
                                    </span>
                                    <input class="field-input md:pr-10" type="text" wire:model.live.debounce.350ms="returnArr"
                                        wire:key="return-arr-input" @focus="show = true" placeholder="City or airport"
                                        autocomplete="off">
                                    @if($returnArr)
                                        <button class="field-clear" wire:click.stop="$set('returnArr', '')" title="Clear">×</button>
                                    @endif

                                    <div x-cloak x-show="show"
                                        class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                                        style="min-width: 0;">
                                        <div class="px-4 py-3">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 21s-6-4.35-6-10a6 6 0 0112 0c0 5.65-6 10-6 10z" />
                                                    <circle cx="12" cy="11" r="2" />
                                                </svg>
                                                <span>Search locations</span>
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
                                @error('returnArr') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            {{-- Date range --}}
                            <div wire:key="return-date-range-picker-{{ $returnDepDate ?: 'empty' }}-{{ $returnRetDate ?: 'empty' }}-{{ ($errors->has('returnDepDate') || $errors->has('returnRetDate')) ? 'error' : 'ok' }}"
                                x-data="dateRangePicker({ dep: @js($returnDepDate), ret: @js($returnRetDate) })"
                                x-init="init()">
                                <div class="field-wrap"
                                    style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                                    <div>
                                        <span class="field-label">Departing</span>
                                        <input class="field-input date-input" :class="dep ? 'has-val' : ''" type="text"
                                            inputmode="none" readonly :value="dep || ''" placeholder="mm/dd/yyyy"
                                            @click="active = 'dep'; open = true">
                                    </div>
                                    <span style="color:#9ca3af; font-size:18px; padding:0 4px; margin-top:10px;">&ndash;</span>
                                    <div>
                                        <span class="field-label">Returning</span>
                                        <input class="field-input date-input" :class="ret ? 'has-val' : ''" type="text"
                                            inputmode="none" readonly :value="ret || ''" placeholder="mm/dd/yyyy"
                                            @click="active = (depIso ? 'ret' : 'dep'); open = true">
                                    </div>
                                </div>

                                <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px;">
                                    <div>@error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div></div>
                                    <div>@error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Calendar modal --}}
                                <div x-cloak x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center"
                                    aria-modal="true" role="dialog">
                                    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                    <div
                                        class="relative w-[92vw] max-w-3xl max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div>
                                                    <p class="text-base font-medium text-gray-800">Select your travel dates
                                                    </p>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="active === 'dep' ? 'Pick departing date' : 'Pick returning date'">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-6 py-5 overflow-auto">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                <template x-for="(m, idx) in months" :key="m.key">
                                                    <div>
                                                        <div class="flex items-center justify-between mb-4">
                                                            <div class="w-10">
                                                                <button type="button"
                                                                    class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                    x-show="idx === 0" @click.prevent="prevMonth()">
                                                                    <svg class="w-5 h-5 text-gray-700" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M15 19l-7-7 7-7" />
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <p class="text-lg font-medium text-gray-800 text-center"
                                                                x-text="m.title">
                                                            </p>

                                                            <div class="w-10 flex justify-end">
                                                                <button type="button"
                                                                    class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center"
                                                                    x-show="idx === 1" @click.prevent="nextMonth()">
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
                                                                    class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition flex flex-col items-center justify-center leading-none"
                                                                    @mouseenter="hoveredIso = cell.iso"
                                                                    @mouseleave="hoveredIso = null"
                                                                    @click="cell.day && pick(cell.iso)" :class="dayClass(cell)">
                                                                    <span x-text="cell.day || ''"></span>
                                                                    <span x-show="depIso && cell.iso === depIso" class="-mt-0.5"
                                                                        aria-hidden="true">
                                                                        <svg class="w-3 h-3" viewBox="0 0 24 24"
                                                                            fill="currentColor">
                                                                            <path
                                                                                d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10l-4.7-4.22-1.66.44 2.82 4.89-4.76 1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55-2.29 5.1-1.37c.8-.21 1.28-1.04 1.06-1.84z" />
                                                                        </svg>
                                                                    </span>
                                                                    <span x-show="retIso && cell.iso === retIso" class="-mt-0.5"
                                                                        aria-hidden="true">
                                                                        <svg class="w-3 h-3" viewBox="0 0 24 24"
                                                                            fill="currentColor">
                                                                            <path
                                                                                d="M2.5 19h19v2h-19v-2zm17.07-9.36c.21-.8-.26-1.63-1.06-1.84l-5.1-1.37-4.7-4.22-1.66.44 2.82 4.89-4.76-1.27-1.61-1.43-1.19.32 1.86 3.23.21.36.43-.11 8.55 2.29 5.31 1.42c.8.22 1.63-.26 1.84-1.06z" />
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                            <div class="text-xs text-gray-500">
                                                <span x-text="dep ? ('Departing: ' + dep) : 'Departing: \\u2014'"></span>
                                                <span class="mx-2">&bull;</span>
                                                <span x-text="ret ? ('Returning: ' + ret) : 'Returning: \\u2014'"></span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                                    @click="open = false">Close</button>
                                                <button type="button"
                                                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#2ab4c0] hover:bg-[#239ea9] disabled:opacity-50"
                                                    :disabled="!depIso || !retIso" @click="apply(); open = false">
                                                    Done
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(false)
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
                                            <div x-cloak x-show="open"
                                                class="fixed inset-0 z-[999] flex items-center justify-center" aria-modal="true"
                                                role="dialog">
                                                <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                                <div
                                                    class="relative w-[92vw] max-w-lg max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
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
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M15 19l-7-7 7-7" />
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
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M9 5l7 7-7 7" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="grid grid-cols-7 gap-1.5 text-xs text-gray-500 mb-2">
                                                                        <template
                                                                            x-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']"
                                                                            :key="d">
                                                                            <div class="text-center tracking-widest" x-text="d">
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="grid grid-cols-7 gap-1.5">
                                                                        <template x-for="cell in m.cells" :key="cell.key">
                                                                            <button type="button"
                                                                                class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition"
                                                                                :disabled="cell.disabled || !cell.day"
                                                                                @click="cell.day && pick(cell.iso)"
                                                                                :class="dayClass(cell)">
                                                                                <span x-text="cell.day || ''"></span>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
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
                                            <div x-cloak x-show="open"
                                                class="fixed inset-0 z-[999] flex items-center justify-center" aria-modal="true"
                                                role="dialog">
                                                <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                                <div
                                                    class="relative w-[92vw] max-w-lg max-h-[85vh] rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
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
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M15 19l-7-7 7-7" />
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
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M9 5l7 7-7 7" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="grid grid-cols-7 gap-1.5 text-xs text-gray-500 mb-2">
                                                                        <template
                                                                            x-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']"
                                                                            :key="d">
                                                                            <div class="text-center tracking-widest" x-text="d">
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="grid grid-cols-7 gap-1.5">
                                                                        <template x-for="cell in m.cells" :key="cell.key">
                                                                            <button type="button"
                                                                                class="h-9 w-9 mx-auto rounded-full text-sm font-medium transition"
                                                                                :disabled="cell.disabled || !cell.day"
                                                                                @click="cell.day && pick(cell.iso)"
                                                                                :class="dayClass(cell)">
                                                                                <span x-text="cell.day || ''"></span>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
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
                                        <div>@error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                        <div></div>
                                        <div>@error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif


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
            <div class="">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-stretch multi-second-row">

                    <div class="field-wrap" style="position:relative;"
                        x-data="paxDropdown({ adults: @entangle('multiAdults'), children: @entangle('multiChildren'), infants: @entangle('multiInfants') })"
                        @click.outside="open = false">
                        <span class="field-label">Passengers</span>
                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span class="text-black" x-text="summary"></span>
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
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-700">Passengers</p>
                                <div class="h-px bg-gray-100 mt-2"></div>
                            </div>
                            <div class="px-4 pb-3 space-y-4">
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="dec('adult')" :disabled="adults <= 1">
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900"><span x-text="adults"></span>
                                            Adult</div>
                                        <div class="text-xs text-gray-500">Ages 12+</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="inc('adult')" :disabled="total >= 9">
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="dec('child')" :disabled="children <= 0">
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900"><span x-text="children"></span>
                                            Child</div>
                                        <div class="text-xs text-gray-500">Ages 2-11</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="inc('child')" :disabled="total >= 9">
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button"
                                        class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="dec('infant')" :disabled="infants <= 0">
                                        <span class="text-xl leading-none">−</span>
                                    </button>
                                    <div class="text-center">
                                        <div class="text-base font-semibold text-gray-900"><span x-text="infants"></span>
                                            Infant</div>
                                        <div class="text-xs text-gray-500">Ages under 2, on lap</div>
                                    </div>
                                    <button type="button"
                                        class="w-10 h-10 rounded-full border border-gray-200 text-gray-700 flex items-center justify-center disabled:opacity-50"
                                        @click.prevent="inc('infant')" :disabled="total >= 9 || infants >= adults">
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>
                                <div class="h-px bg-gray-100"></div>
                                <p class="text-xs text-gray-600">Please note: You can book for a maximum of nine
                                    passengers.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Class --}}
                    <div class="field-wrap" style="position:relative;"
                        x-data="{ open: false, selected: @entangle('multiClass') }" @click.outside="open = false">
                        <span class="field-label">Class</span>
                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span class="text-black" x-text="selected"></span>
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
                            <div class="py-2">
                                @foreach(['Economy Class', 'Premium Economy', 'Business Class', 'First Class'] as $class)
                                    <button type="button"
                                        class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                        @click.prevent="selected = '{{ $class }}'; open = false">
                                        <span
                                            :class="selected === '{{ $class }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium'">
                                            {{ $class }}
                                        </span>
                                        <svg x-cloak x-show="selected === '{{ $class }}'" class="w-5 h-5 text-[#2ab4c0]"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Currency --}}
                    <div class="field-wrap" style="position:relative;"
                        x-data="{ open: false, selected: @entangle('currency') }" @click.outside="open = false">
                        <span class="field-label">Currency</span>
                        <button type="button" class="field-select text-left w-full flex items-center justify-between"
                            @click="open = !open" aria-haspopup="listbox" :aria-expanded="open">
                            <span class="text-black" x-text="selected"></span>
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
                            <div class="py-2 max-h-72 overflow-auto">
                                @foreach($currencies as $code => $label)
                                    <button type="button"
                                        class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-gray-50"
                                        @click.prevent="selected = '{{ $code }}'; open = false">
                                        <span
                                            :class="selected === '{{ $code }}' ? 'text-[#2ab4c0] font-semibold' : 'text-gray-900 font-medium text-xs'">
                                            {{ $label }}
                                        </span>
                                        <svg x-cloak x-show="selected === '{{ $code }}'" class="w-4 h-4 text-[#2ab4c0]"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Users --}}
                    <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                        @click.outside="show = false">
                        <span class="field-label">Users</span>
                        <input class="field-input" type="text" wire:model.live.debounce.150ms="multiUserSearch"
                            wire:key="multi-main-usersearch-input" @focus="show = true" placeholder="Search users"
                            autocomplete="off">

                        @if($multiUserSearch)
                            <button class="field-clear" wire:click.stop="clearMultiUser()" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                        <circle cx="9" cy="7" r="4" stroke-width="2" />
                                    </svg>
                                    <span>Search users</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="max-h-72 overflow-auto">
                                @php $items = $this->multiUserSearchResults; @endphp
                                @forelse($items as $u)
                                    <button type="button"
                                        class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between"
                                        wire:click.stop="selectMultiUser({{ $u['id'] }})" @click="show = false">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">{{ $u['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $u['email'] }}</div>
                                        </div>

                                    </button>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">No users</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Airlines --}}
                    <div class="field-wrap" style="position:relative;" x-data="{ show: false }"
                        @click.outside="show = false">
                        <span class="field-label">Airlines</span>
                        <input class="field-input" type="text" wire:model.live.debounce.150ms="multiAirlineSearch"
                            wire:key="multi-main-airlinesearch-input" @focus="show = true" placeholder="Search airline"
                            autocomplete="off">

                        @if($multiAirlineSearch)
                            <button class="field-clear" wire:click.stop="clearMultiAirline()" title="Clear">×</button>
                        @endif

                        <div x-cloak x-show="show"
                            class="absolute left-0 right-0 top-full z-50 mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden"
                            style="min-width: 0;">
                            <div class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2-7h-4l-2 2h-3l2-4-2-4h3l2 2h4l-2-7h3z" />
                                    </svg>
                                    <span>Search airlines</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="max-h-72 overflow-auto">
                                @forelse($this->multiAirlineSearchResults as $a)
                                    <button type="button"
                                        class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center justify-between group transition-colors"
                                        wire:click.stop="selectMultiAirline('{{ $a['code'] }}', '{{ $a['name'] }}')"
                                        @click="show = false">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 group-hover:bg-white transition-colors">
                                                <img src="https://www.gstatic.com/flights/airline_logos/70px/{{ $a['code'] }}.png"
                                                    alt="{{ $a['name'] }}"
                                                    onerror="this.src='https://pics.avs.io/128/128/{{ $a['code'] }}.png'"
                                                    class="w-full h-full object-contain">
                                            </div>
                                            <div>
                                                <div
                                                    class="text-sm font-semibold text-gray-800 group-hover:text-[#2ab4c0] transition-colors">
                                                    {{ $a['name'] }}
                                                </div>
                                                <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">
                                                    {{ $a['code'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">No airlines</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Search Button --}}
            <div class="flex justify-end mt-6">
                <div class="">
                    <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Spinner (visible only when loading) --}}
                            <svg wire:loading wire:target="search" class="animate-spin w-4 h-4" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>

                            {{-- Search Icon (hidden when loading) --}}
                            <svg wire:loading.remove wire:target="search" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35">
                                </path>
                            </svg>

                            <span wire:loading.remove wire:target="search">Search flights</span>
                            <span wire:loading wire:target="search">Searching...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>{{-- /wrapper --}}
    @endif

</div>{{-- /search-card --}}