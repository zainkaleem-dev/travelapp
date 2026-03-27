<div>
    <div class="w-full py-4 flex flex-col lg:flex-row gap-6">
        <main class="flex-1 min-w-0">
    {{-- ── Teal header bar (match choose-seat) ── --}}
        <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2 shadow-lg shadow-[#2ab4c0]/20" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                @if ($searchParams['isMulti'] ?? false && !empty($searchParams['segments']))
                    <p class="text-base font-bold text-white">Multi-City Trip</p>
                    <p class="text-xs font-medium text-white/90">
                        {{ count($searchParams['segments']) }} Flights:
                        {{ $searchParams['segments'][0]['origin'] }} →
                        {{ $searchParams['segments'][count($searchParams['segments']) - 1]['destination'] }}
                    </p>
                @elseif(($searchParams['tripType'] ?? '') === 'return')
                    <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? 'Origin' }} → {{ $searchParams['destination'] ?? 'Destination' }}</p>
                    <p class="text-xs font-medium text-white/90">Return Trip</p>
                @elseif(($searchParams['tripType'] ?? '') === 'oneway')
                    <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? 'Origin' }} → {{ $searchParams['destination'] ?? 'Destination' }}</p>
                    <p class="text-xs font-medium text-white/90">One-way Trip</p>
                @else
                    <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? 'Origin' }} → {{ $searchParams['destination'] ?? 'Destination' }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-white/90">
                    @if ($searchParams['isMulti'] ?? false)
                        First Departure
                    @else
                        Departure Date
                    @endif
                </p>
                <p class="text-base font-bold text-white">
                    @php
                        $dateToParse = $searchParams['departDate'] ?? now();
                        if ($searchParams['isMulti'] ?? false && !empty($searchParams['segments'])) {
                            $dateToParse = $searchParams['segments'][0]['date'];
                        }
                    @endphp
                    {{ \Carbon\Carbon::parse($dateToParse)->format('l d.m.Y') }}
                </p>
            </div>
        </div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-6 rounded-xl mb-3 overflow-hidden">

        {{-- ── LEFT: Main Form ─────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-6">

            {{-- Amadeus Error Alert --}}
            @if (session()->has('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <strong class="font-semibold block mb-0.5">Booking failed</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Contact Details ──────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        Contact Details
                    </h2>
                </div>
                <div class="px-4 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Email --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">E-Mail</label>
                            <input
                                type="email"
                                wire:model.live.debounce.400ms="contactEmail"
                                placeholder="E-Mail"
                                class="w-full px-3 py-2.5 border @error('contactEmail') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                            />
                            @error('contactEmail')
                                <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Phone --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <div class="flex gap-1.5">
                                <select
                                    wire:model="phoneCode"
                                    class="px-2 py-2.5 border @error('phoneCode') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 w-24 flex-shrink-0 bg-white focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                                >
                                    @foreach($this->countries as $c)
                                        <option value="{{ $c['dial_code'] }}">{{ $c['code'] }} ({{ $c['dial_code'] }})</option>
                                    @endforeach
                                </select>
                                <input
                                    type="tel"
                                    wire:model.live.debounce.400ms="phoneNumber"
                                    placeholder="Phone number"
                                    class="flex-1 min-w-0 px-3 py-2.5 border @error('phoneNumber') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                />
                            </div>
                            @error('phoneNumber')
                                <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Passenger Selection Tabs ───────────────────────────────── --}}
            <div x-data="{
                canScrollLeft: false,
                canScrollRight: false,
                updateButtons() {
                    const el = this.$refs.passengerScroller;
                    if (!el) return;
                    this.canScrollLeft = el.scrollLeft > 0;
                    this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - 1;
                },
                scrollPassenger(direction) {
                    const el = this.$refs.passengerScroller;
                    if (!el) return;
                    el.scrollBy({ left: direction * 200, behavior: 'smooth' });
                    setTimeout(() => this.updateButtons(), 250);
                }
            }" x-init="$nextTick(() => updateButtons())" @resize.window="updateButtons()"
                class="relative group/passengers bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                
                {{-- Scroll Buttons --}}
                <button type="button" x-show="canScrollLeft" x-transition.opacity
                    @click.prevent="scrollPassenger(-1)"
                    class="absolute left-2 top-1/2 z-50 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-[#2ab4c0] shadow-sm hover:bg-white hover:shadow-md transition-all backdrop-blur-sm"
                    aria-label="Scroll left">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button type="button" x-show="canScrollRight" x-transition.opacity
                    @click.prevent="scrollPassenger(1)"
                    class="absolute right-2 top-1/2 z-50 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-[#2ab4c0] shadow-sm hover:bg-white hover:shadow-md transition-all backdrop-blur-sm"
                    aria-label="Scroll right">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-ref="passengerScroller" @scroll.passive="updateButtons()"
                    class="flex items-center gap-3 overflow-x-auto px-4 py-3 pb-2 no-scrollbar touch-pan-x scroll-smooth">
                    @foreach ($passengers as $index => $passenger)
                        @php
                            $type = $passenger['type'];
                            $isCompleted = $this->completedPassengers[$index] ?? false;
                            $isActive = $activePassengerIndex === $index;
                            
                            $shortType = match($type) {
                                'ADULT' => 'Adult',
                                'CHILD' => 'Child',
                                'HELD_INFANT' => 'Infant',
                                default => 'Passenger'
                            };
                        @endphp
                        <button wire:click="setActivePassenger({{ $index }})"
                            class="flex items-center gap-3 px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm min-w-max {{ $isActive ? 'border-[#2ab4c0] bg-[#2ab4c0]/5' : ($isCompleted ? 'border-gray-200 bg-white hover:border-gray-300' : 'border-dashed border-gray-300 bg-gray-50 hover:border-gray-400') }}">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs {{ $isActive ? 'bg-[#2ab4c0] text-white shadow-md shadow-[#2ab4c0]/30' : ($isCompleted ? 'bg-[#2ab4c0] text-white' : 'bg-gray-200 text-gray-500') }}">
                                @if($isCompleted && !$isActive)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="{{ $isActive ? 'text-[#2ab4c0]' : ($isCompleted ? 'text-gray-800' : 'text-gray-500') }}">
                                    {{ $shortType }} {{ $index + 1 }}
                                </span>
                                @if($isCompleted && !empty($passenger['first_name']))
                                    <span class="text-[10px] font-medium text-[#2ab4c0] uppercase tracking-widest truncate max-w-[80px]">
                                        {{ trim($passenger['first_name'] . ' ' . ($passenger['middle_name'] ?? '') . ' ' . $passenger['last_name']) }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Details Info</span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- ── Active Passenger Form area ────────────────────────────── --}}
            @if(isset($passengers[$activePassengerIndex]))
                @php
                    $index = $activePassengerIndex;
                    $passenger = $passengers[$index];
                    $type = $passenger['type'];
                    $typeLabel = match($type) {
                        'ADULT' => 'Adult (over 12 years)',
                        'CHILD' => 'Child (2-11 years)',
                        'HELD_INFANT' => 'Infant (under 2 years)',
                        default => 'Passenger'
                    };
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden" wire:key="passenger-form-{{ $index }}">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-[#2ab4c0] text-white flex items-center justify-center font-bold text-xs shadow-md shadow-[#2ab4c0]/30">{{ $index + 1 }}</span>
                            Passenger Details
                        </h2>
                        <span class="text-[10px] font-bold text-[#2ab4c0] bg-[#2ab4c0]/10 px-2 py-1 rounded-lg uppercase tracking-wider">{{ $typeLabel }}</span>
                    </div>

                    <div class="px-4 py-4 space-y-4">
                        {{-- Info notice --}}
                        <div class="flex items-start gap-2 bg-[#2ab4c0]/5 border border-[#2ab4c0]/20 rounded-lg px-3 py-2">
                            <svg class="w-3.5 h-3.5 text-[#2ab4c0] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                            <p class="text-[#2399a3] text-[10px] font-medium">To avoid boarding difficulties, enter all first and last names exactly as they appear on your Passport/ID.</p>
                        </div>

                        {{-- First / Middle / Last name --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">First Name</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="passengers.{{ $index }}.first_name"
                                    placeholder="First Name"
                                    class="w-full px-3 py-2.5 border @error('passengers.'.$index.'.first_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                />
                                @error('passengers.'.$index.'.first_name')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Middle Name <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="passengers.{{ $index }}.middle_name"
                                    placeholder="Middle Name"
                                    class="w-full px-3 py-2.5 border @error('passengers.'.$index.'.middle_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                />
                                @error('passengers.'.$index.'.middle_name')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Last Name</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="passengers.{{ $index }}.last_name"
                                    placeholder="Last Name"
                                    class="w-full px-3 py-2.5 border @error('passengers.'.$index.'.last_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                />
                                @error('passengers.'.$index.'.last_name')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Date of Birth</label>
                            <input
                                type="date"
                                wire:model.live="passengers.{{ $index }}.dob"
                                class="w-full px-3 py-2.5 border @error('passengers.'.$index.'.dob') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all bg-white"
                                min="1900-01-01"
                                max="{{ date('Y-m-d') }}"
                            />
                            @error('passengers.'.$index.'.dob')
                                <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nationality + Gender / Passport --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nationality & Gender</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <select
                                            wire:model.live="passengers.{{ $index }}.nationality"
                                            class="w-full px-2 py-2.5 border @error('passengers.'.$index.'.nationality') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                        >
                                            <option value="">Select Nationality</option>
                                            @foreach($this->countries as $c)
                                                <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('passengers.'.$index.'.nationality')
                                            <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <select
                                            wire:model.live="passengers.{{ $index }}.gender"
                                            class="w-full px-2 py-2.5 border @error('passengers.'.$index.'.gender') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                        >
                                            <option value="">Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                        @error('passengers.'.$index.'.gender')
                                            <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Passport Number (or ID)</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="passengers.{{ $index }}.passport"
                                    placeholder="Passport Number (or ID)"
                                    class="w-full px-3 py-2.5 border @error('passengers.'.$index.'.passport') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                />
                                @error('passengers.'.$index.'.passport')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Visa disclaimer --}}
                        <div class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2 mt-2">
                            <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                            <p class="text-amber-700 text-[10px] font-medium">FlightBook is not liable for any passenger who is denied boarding or entry to any destination due to visa.</p>
                        </div>

                        {{-- Next Button --}}
                        @if($index < count($passengers) - 1)
                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button
                                    type="button"
                                    wire:click="nextPassenger"
                                    class="px-5 py-2.5 bg-[#2ab4c0]/10 text-[#2ab4c0] font-bold border border-[#2ab4c0]/30 rounded-xl hover:bg-[#2ab4c0] hover:text-white transition-all shadow-sm flex items-center gap-2 text-[11px]"
                                >
                                    Save & Next Passenger
                                    <svg class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>{{-- end LEFT --}}

        {{-- ── RIGHT: Summary Sidebar (match choose-seat) ───────────────────────────────────── --}}
        <div class="w-full lg:w-80 flex-shrink-0 self-start">
            <div class="bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-200/40 overflow-hidden sticky top-6">

                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </span>
                        Price Summary
                    </h3>
                </div>

                <div class="p-5 space-y-4">
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
                            <span class="text-sm font-bold text-gray-900">{{ $currencyCode }}{{ $item['amount'] }}</span>
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <span class="text-gray-900 text-lg font-black uppercase tracking-tighter">Total</span>
                        <div class="text-right">
                            <span class="text-gray-900 text-2xl font-black">{{ $currencyCode }}{{ $this->total }}</span>
                        </div>
                    </div>
                    <p class="text-[9px] text-gray-400 font-medium">Prices include all taxes and carrier-imposed fees.</p>

                    {{-- Primary Action Buttons (same line as choose-seat) --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button 
                            x-data="{ loading: false }"
                            @click="loading = true; setTimeout(() => $wire.back(), 500)"
                            :disabled="loading"
                            :class="loading ? 'bg-neutral-400' : 'bg-white hover:bg-gray-50'"
                            class="flex-1 min-w-0 py-2 border border-gray-200 text-gray-600 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 text-[11px] disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading" class="flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                Back
                            </span>
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                        </button>
                        <button wire:click="continue" wire:loading.attr="disabled"
                                class="flex-1 min-w-0 py-2 bg-[#2ab4c0] text-white text-[11px] font-black rounded-xl hover:bg-[#2399a3] shadow-lg shadow-[#2ab4c0]/30 transition-all flex items-center justify-center gap-2 group">
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-2">
                                Continue
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <svg wire:loading wire:target="continue" class="animate-spin w-4 h-4" fill="none"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </main>
    </div>
</div>
