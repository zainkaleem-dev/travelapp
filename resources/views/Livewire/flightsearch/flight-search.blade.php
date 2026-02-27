{{-- resources/views/livewire/book-flight.blade.php --}}
<div>

    {{-- ════════════════════════════════════════
    SEARCH CARD
    ════════════════════════════════════════ --}}
    <div class="search-card">

        {{-- Tab row --}}
        <div class="flex items-center border-b border-gray-200 mb-5">
            <button class="trip-tab {{ $tripType === 'return' ? 'active' : '' }}"
                wire:click="switchTab('return')">Return</button>
            <button class="trip-tab {{ $tripType === 'oneway' ? 'active' : '' }}" wire:click="switchTab('oneway')">One
                way</button>
            <button class="trip-tab {{ $tripType === 'multi' ? 'active' : '' }}"
                wire:click="switchTab('multi')">Multi-city</button>
        </div>

        {{-- Login hint --}}
        <div class="login-link mb-5">
            <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01" />
            </svg>
            <a href="{{ route('login') }}">Log in to book Classic Rewards</a>
            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </div>

        {{-- ══════════════════════════════════════
        PANEL: RETURN
        ══════════════════════════════════════ --}}
        @if($tripType === 'return')
            <div>
                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

                    {{-- Departure --}}
                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Departure airport</span>
                        <input class="field-input" type="text" wire:model="returnDep" placeholder="City or airport"
                            autocomplete="off">
                        @if($returnDep)
                            <button class="field-clear" wire:click="$set('returnDep', '')" title="Clear">×</button>
                        @endif
                        @error('returnDep') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Arrival --}}
                    <div class="field-wrap">
                        <span class="field-label">Arrival airport</span>
                        <input class="field-input" type="text" wire:model="returnArr" placeholder="City or airport"
                            autocomplete="off">
                        @error('returnArr') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Date range --}}
                    <div class="field-wrap"
                        style="display:grid; grid-template-columns:1fr auto 1fr; gap:4px; align-items:center;">
                        <div>
                            <span class="field-label">Departing</span>
                            <input class="field-input date-input" type="date" wire:model="returnDepDate">
                            @error('returnDepDate') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <span style="color:#9ca3af; font-size:18px; padding:0 4px; margin-top:10px;">–</span>
                        <div>
                            <span class="field-label">Returning</span>
                            <input class="field-input date-input" type="date" wire:model="returnRetDate">
                            @error('returnRetDate') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Passengers</span>
                        <select class="field-select" wire:model="returnPax">
                            <option>1 Adult</option>
                            <option>2 Adults</option>
                            <option>3 Adults</option>
                            <option>1 Adult, 1 Child</option>
                            <option>2 Adults, 1 Child</option>
                            <option>2 Adults, 2 Children</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Class</span>
                        <select class="field-select" wire:model="returnClass">
                            <option>Economy Class</option>
                            <option>Business Class</option>
                            <option>First Class</option>
                            <option>Premium Economy</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35" />
                            </svg>
                            Search flights
                        </span>
                        <span wire:loading wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Searching…
                        </span>
                    </button>

                </div>

                {{-- Promo --}}
                <div class="mt-4">
                    <button class="promo-toggle {{ $returnPromo ? 'open' : '' }}" wire:click="$toggle('returnPromo')">
                        Use promotional code
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if($returnPromo)
                        <div class="promo-input open">
                            <input type="text" wire:model="returnPromoCode" placeholder="Enter promotional code"
                                class="field-input border border-gray-200 rounded px-3 py-2 text-sm w-full max-w-xs focus:outline-none focus:border-blue-400">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════
        PANEL: ONE WAY
        ══════════════════════════════════════ --}}
        @if($tripType === 'oneway')
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Departure airport</span>
                        <input class="field-input" type="text" wire:model="onewayDep" placeholder="City or airport"
                            autocomplete="off">
                        @if($onewayDep)
                            <button class="field-clear" wire:click="$set('onewayDep', '')" title="Clear">×</button>
                        @endif
                        @error('onewayDep') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-wrap">
                        <span class="field-label">Arrival airport</span>
                        <input class="field-input" type="text" wire:model="onewayArr" placeholder="City or airport"
                            autocomplete="off">
                        @error('onewayArr') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-wrap">
                        <span class="field-label">Departing</span>
                        <input class="field-input date-input" type="date" wire:model="onewayDepDate">
                        @error('onewayDepDate') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Passengers</span>
                        <select class="field-select" wire:model="onewayPax">
                            <option>1 Adult</option>
                            <option>2 Adults</option>
                            <option>3 Adults</option>
                            <option>1 Adult, 1 Child</option>
                            <option>2 Adults, 1 Child</option>
                            <option>2 Adults, 2 Children</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Class</span>
                        <select class="field-select" wire:model="onewayClass">
                            <option>Economy Class</option>
                            <option>Business Class</option>
                            <option>First Class</option>
                            <option>Premium Economy</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35" />
                            </svg>
                            Search flights
                        </span>
                        <span wire:loading wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Searching…
                        </span>
                    </button>

                </div>

                {{-- Promo --}}
                <div class="mt-4">
                    <button class="promo-toggle {{ $onewayPromo ? 'open' : '' }}" wire:click="$toggle('onewayPromo')">
                        Use promotional code
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if($onewayPromo)
                        <div class="promo-input open">
                            <input type="text" wire:model="onewayPromoCode" placeholder="Enter promotional code"
                                class="field-input border border-gray-200 rounded px-3 py-2 text-sm w-full max-w-xs focus:outline-none focus:border-blue-400">
                        </div>
                    @endif
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

                                <div class="field-wrap" style="position:relative;">
                                    <span class="field-label">Departure airport</span>
                                    <input class="field-input" type="text" wire:model="multiFlights.{{ $index }}.dep"
                                        placeholder="City or airport" autocomplete="off">
                                    @if($flight['dep'] && $index === 0)
                                        <button class="field-clear" wire:click="$set('multiFlights.0.dep', '')"
                                            title="Clear">×</button>
                                    @endif
                                    @error("multiFlights.$index.dep") <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="field-wrap">
                                    <span class="field-label">Arrival airport</span>
                                    <input class="field-input" type="text" wire:model="multiFlights.{{ $index }}.arr"
                                        placeholder="City or airport" autocomplete="off">
                                    @error("multiFlights.$index.arr") <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="field-wrap">
                                    <span class="field-label">Departing</span>
                                    <input class="field-input date-input" type="date"
                                        wire:model="multiFlights.{{ $index }}.date">
                                    @error("multiFlights.$index.date") <span class="field-error">{{ $message }}</span> @enderror
                                </div>

                                {{-- Remove button (only for flight 3+) --}}
                                @if($index >= 2)
                                    <button class="mc-remove" wire:click="removeFlight({{ $index }})"
                                        title="Remove flight">×</button>
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
                        <span class="add-icon">+</span>
                        Add a flight
                    </button>
                @endif

                {{-- Passengers / Class / Search --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Passengers</span>
                        <select class="field-select" wire:model="multiPax">
                            <option>1 Adult</option>
                            <option>2 Adults</option>
                            <option>3 Adults</option>
                            <option>1 Adult, 1 Child</option>
                            <option>2 Adults, 1 Child</option>
                            <option>2 Adults, 2 Children</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <div class="field-wrap" style="position:relative;">
                        <span class="field-label">Class</span>
                        <select class="field-select" wire:model="multiClass">
                            <option>Economy Class</option>
                            <option>Business Class</option>
                            <option>First Class</option>
                            <option>Premium Economy</option>
                        </select>
                        <span class="select-arrow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <button class="btn-search" wire:click="search" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35" />
                            </svg>
                            Search flights
                        </span>
                        <span wire:loading wire:target="search" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Searching…
                        </span>
                    </button>

                </div>

                {{-- Promo --}}
                <div class="mt-4">
                    <button class="promo-toggle {{ $multiPromo ? 'open' : '' }}" wire:click="$toggle('multiPromo')">
                        Use promotional code
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if($multiPromo)
                        <div class="promo-input open">
                            <input type="text" wire:model="multiPromoCode" placeholder="Enter promotional code"
                                class="field-input border border-gray-200 rounded px-3 py-2 text-sm w-full max-w-xs focus:outline-none focus:border-blue-400">
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- /search-card --}}
</div>