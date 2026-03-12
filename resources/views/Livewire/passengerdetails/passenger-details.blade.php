<div>

    {{-- ── Teal header bar (match choose-seat) ── --}}
    <div class="w-full lg:w-[90%] mx-auto px-4 pt-6">
        <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2 shadow-lg shadow-[#2ab4c0]/20" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? 'Origin' }} → {{ $searchParams['destination'] ?? 'Destination' }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">{{ $searchParams['travelClass'] ?? 'Economy' }}</span>
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">
                        {{ ($searchParams['adultCount'] ?? 1) + ($searchParams['childCount'] ?? 0) + ($searchParams['infantCount'] ?? 0) }} Passenger(s)
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-white/90">Departure Date</p>
                <p class="text-base font-bold text-white">{{ isset($searchParams['departDate']) ? \Carbon\Carbon::parse($searchParams['departDate'])->format('l d.m.Y') : '—' }}</p>
            </div>
        </div>
    </div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="w-full lg:w-[90%] mx-auto px-4 pb-12 flex flex-col lg:flex-row gap-6">

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

            {{-- ── Passenger Forms (loop) ───────────────────────────────── --}}
            @php $typeCounts = []; @endphp
            @foreach ($passengers as $index => $passenger)
            @php
                $type = $passenger['type'];
                $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 1;
                $currentCount = $typeCounts[$type];
                
                $shortType = match($type) {
                    'ADULT' => 'Adult',
                    'CHILD' => 'Child',
                    'HELD_INFANT' => 'Infant',
                    default => 'Passenger'
                };
                $displayLabel = $shortType . ' ' . $currentCount;
                
                $typeLabel = match($type) {
                    'ADULT' => 'Adult (over 12 years)',
                    'CHILD' => 'Child (2-11 years)',
                    'HELD_INFANT' => 'Infant (under 2 years)',
                    default => 'Passenger'
                };
            @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 overflow-hidden">
                <div wire:click="setActivePassenger({{ $index }})" class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/50 cursor-pointer hover:bg-gray-100/50 transition-colors">
                    <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        @if($this->completedPassengers[$index] ?? false)
                            <span class="w-8 h-8 rounded-full bg-[#2ab4c0] text-white flex items-center justify-center font-black shadow-sm shadow-[#2ab4c0]/30 text-xs">✓</span>
                            {{ $displayLabel }}
                            @if(!empty($passenger['first_name']))
                                <span class="text-gray-500 font-medium text-[11px]">({{ $passenger['first_name'] }} {{ $passenger['last_name'] }})</span>
                            @endif
                        @else
                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-colors {{ $activePassengerIndex === $index ? 'bg-[#2ab4c0] text-white shadow-md shadow-[#2ab4c0]/30' : 'bg-gray-200 text-gray-500' }}">{{ $index + 1 }}</span>
                            <span class="{{ $activePassengerIndex === $index ? 'text-[#2ab4c0]' : 'text-gray-600' }}">{{ $displayLabel }}</span>
                        @endif
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $typeLabel }}</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform {{ $activePassengerIndex === $index ? 'rotate-180 text-[#2ab4c0]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div x-show="$wire.activePassengerIndex === {{ $index }}" x-collapse.duration.300ms x-cloak>
                    {{-- Info notice --}}
                    <div class="mx-4 mt-3 flex items-start gap-2 bg-[#2ab4c0]/5 border border-[#2ab4c0]/20 rounded-lg px-3 py-2">
                        <svg class="w-3.5 h-3.5 text-[#2ab4c0] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                        <p class="text-[#2399a3] text-[10px] font-medium">To avoid boarding difficulties, enter all first and last names exactly as they appear on your Passport/ID.</p>
                    </div>

                    <div class="px-4 py-4 space-y-4">

                        {{-- First / Last name --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                <div class="flex gap-2">
                                    <select
                                        wire:model.live="passengers.{{ $index }}.nationality"
                                        class="flex-1 px-2 py-2.5 border @error('passengers.'.$index.'.nationality') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                    >
                                        <option value="">Select Nationality</option>
                                        @foreach($this->countries as $c)
                                            <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <select
                                        wire:model.live="passengers.{{ $index }}.gender"
                                        class="flex-1 px-2 py-2.5 border @error('passengers.'.$index.'.gender') border-red-400 @else border-gray-200 @enderror rounded-lg text-sm text-gray-800 bg-white focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                    >
                                        <option value="">Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                @error('passengers.'.$index.'.nationality')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
                                @error('passengers.'.$index.'.gender')
                                    <p class="mt-0.5 text-red-500 text-[10px] font-medium">{{ $message }}</p>
                                @enderror
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

                        {{-- Next Button (Only show if not the very last passenger) --}}
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
            </div>
            @endforeach

        </div>{{-- end LEFT --}}

        {{-- ── RIGHT: Summary Sidebar (match choose-seat) ───────────────────────────────────── --}}
        <div class="w-full lg:w-80 flex-shrink-0">
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
                            <span class="text-sm font-bold text-gray-900">${{ number_format($item['amount'], 2) }}</span>
                        </div>
                    @endforeach

                    <div class="pt-5 mt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Final Total</span>
                            <span class="text-gray-900 text-2xl font-black">${{ number_format($this->total, 2) }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium">Prices include all taxes and carrier-imposed fees.</p>
                    </div>

                    {{-- Primary Action Buttons (same line as choose-seat) --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button wire:click="back" class="flex-1 min-w-0 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-[11px]">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button wire:click="continue" wire:loading.attr="disabled"
                                class="flex-1 min-w-0 py-2 bg-[#2ab4c0] text-white text-[11px] font-black rounded-xl hover:bg-[#2399a3] shadow-lg shadow-[#2ab4c0]/30 transition-all flex items-center justify-center gap-2 group">
                            <span class="flex items-center gap-2">
                                Continue
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
