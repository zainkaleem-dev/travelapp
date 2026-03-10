<div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Main Form ─────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">

                {{-- Amadeus Error Alert --}}
                @if (session()->has('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-start gap-2 mb-4">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <strong class="font-semibold block mb-0.5">Booking failed</strong>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                {{-- ── Contact Details ──────────────────────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Contact Details</h2>
                    </div>
                    <div class="px-4 py-3">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            {{-- Email --}}
                            <div class="sm:col-span-2">
                                <label class="block text-gray-500 mb-1" style="font-size:10px">E-Mail</label>
                                <input
                                    type="email"
                                    wire:model.live.debounce.400ms="contactEmail"
                                    placeholder="E-Mail"
                                    class="w-full px-3 py-2 border @error('contactEmail') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 transition-colors"
                                />
                                @error('contactEmail')
                                    <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Phone --}}
                            <div>
                                <label class="block text-gray-500 mb-1" style="font-size:10px">Phone Number</label>
                                <div class="flex gap-1.5">
                                    <select
                                        wire:model="phoneCode"
                                        class="px-2 py-2 border @error('phoneCode') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 w-16 flex-shrink-0 bg-white"
                                    >
                                        <option value="+1">+1</option>
                                        <option value="+44">+44</option>
                                        <option value="+90">+90</option>
                                        <option value="+49">+49</option>
                                    </select>
                                    <input
                                        type="tel"
                                        wire:model.live.debounce.400ms="phoneNumber"
                                        placeholder="Phone number"
                                        class="flex-1 min-w-0 px-3 py-2 border @error('phoneNumber') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700"
                                    />
                                </div>
                                @error('phoneNumber')
                                    <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Passenger Forms (loop) ───────────────────────────────── --}}
                @foreach ($passengers as $index => $passenger)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div wire:click="setActivePassenger({{ $index }})" class="flex items-center justify-between px-4 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors">
                        <h2 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                            @if($this->completedPassengers[$index] ?? false)
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black shadow-sm" style="font-size:10px">✓</span>
                                Passenger {{ $index + 1 }}
                                @if(!empty($passenger['first_name']))
                                    <span class="text-gray-400 font-medium" style="font-size:11px">({{ $passenger['first_name'] }} {{ $passenger['last_name'] }})</span>
                                @endif
                            @else
                                <span class="w-5 h-5 rounded-full {{ $activePassengerIndex === $index ? 'bg-indigo-600 shadow-md shadow-indigo-600/30' : 'bg-gray-200 text-gray-500' }} text-white flex items-center justify-center font-bold transition-colors" style="font-size:10px">{{ $index + 1 }}</span>
                                <span class="{{ $activePassengerIndex === $index ? 'text-indigo-900' : 'text-gray-600' }}">Passenger {{ $index + 1 }}</span>
                            @endif
                        </h2>
                        @php
                            $typeLabel = match($passenger['type']) {
                                'ADULT' => 'Adult (over 12 years)',
                                'CHILD' => 'Child (2-11 years)',
                                'HELD_INFANT' => 'Infant (under 2 years)',
                                default => 'Passenger'
                            };
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400" style="font-size:10px">{{ $typeLabel }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform {{ $activePassengerIndex === $index ? 'rotate-180 text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div x-show="$wire.activePassengerIndex === {{ $index }}" x-collapse.duration.300ms x-cloak>
                        {{-- Info notice --}}
                        <div class="mx-4 mt-3 flex items-start gap-2 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">
                            <svg class="w-3.5 h-3.5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                            <p class="text-indigo-600" style="font-size:10px">To avoid boarding difficulties, enter all first and last names exactly as they appear on your Passport/ID.</p>
                        </div>

                        <div class="px-4 py-4 space-y-4">

                            {{-- First / Last name --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-500 mb-1" style="font-size:10px">First Name</label>
                                    <input
                                        type="text"
                                        wire:model.live.debounce.400ms="passengers.{{ $index }}.first_name"
                                        placeholder="First Name"
                                        class="w-full px-3 py-2 border @error('passengers.'.$index.'.first_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    />
                                    @error('passengers.'.$index.'.first_name')
                                        <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-500 mb-1" style="font-size:10px">Last Name</label>
                                    <input
                                        type="text"
                                        wire:model.live.debounce.400ms="passengers.{{ $index }}.last_name"
                                        placeholder="Last Name"
                                        class="w-full px-3 py-2 border @error('passengers.'.$index.'.last_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    />
                                    @error('passengers.'.$index.'.last_name')
                                        <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label class="block text-gray-500 mb-1" style="font-size:10px">Date of Birth</label>
                                <div class="flex gap-2">
                                    {{-- Day --}}
                                    <select
                                        wire:model.live="passengers.{{ $index }}.dob_day"
                                        class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_day') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    >
                                        <option value="">Day</option>
                                        @foreach ($this->days as $day)
                                            <option value="{{ $day }}">{{ $day }}</option>
                                        @endforeach
                                    </select>
                                    {{-- Month --}}
                                    <select
                                        wire:model.live="passengers.{{ $index }}.dob_month"
                                        class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_month') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    >
                                        <option value="">Month</option>
                                        @foreach ($this->months as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                    {{-- Year --}}
                                    <select
                                        wire:model.live="passengers.{{ $index }}.dob_year"
                                        class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_year') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    >
                                        <option value="">Year</option>
                                        @foreach ($this->years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('passengers.'.$index.'.dob_day')
                                    <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nationality + Gender / Passport --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-500 mb-1" style="font-size:10px">Nationality & Gender</label>
                                    <div class="flex gap-2">
                                        <select
                                            wire:model.live="passengers.{{ $index }}.nationality"
                                            class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.nationality') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                        >
                                            <option value="">Select Nationality</option>
                                            <option>Turkish</option>
                                            <option>German</option>
                                            <option>American</option>
                                            <option>British</option>
                                            <option>Emirati</option>
                                        </select>
                                        <select
                                            wire:model.live="passengers.{{ $index }}.gender"
                                            class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.gender') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                        >
                                            <option value="">Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                    @error('passengers.'.$index.'.nationality')
                                        <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                    @enderror
                                    @error('passengers.'.$index.'.gender')
                                        <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-500 mb-1" style="font-size:10px">Passport Number (or ID)</label>
                                    <input
                                        type="text"
                                        wire:model.live.debounce.400ms="passengers.{{ $index }}.passport"
                                        placeholder="Passport Number (or ID)"
                                        class="w-full px-3 py-2 border @error('passengers.'.$index.'.passport') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all"
                                    />
                                    @error('passengers.'.$index.'.passport')
                                        <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Visa disclaimer --}}
                            <div class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2 mt-2">
                                <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                                <p class="text-amber-700" style="font-size:10px">FlightBook is not liable for any passenger who is denied boarding or entry to any destination due to visa.</p>
                            </div>

                            {{-- Next Button (Only show if not the very last passenger) --}}
                            @if($index < count($passengers) - 1)
                                <div class="pt-4 border-t border-gray-50 flex justify-end">
                                    <button
                                        type="button"
                                        wire:click="nextPassenger"
                                        class="px-5 py-2.5 bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center gap-2 text-[11px]"
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

            {{-- ── RIGHT: Summary Sidebar ───────────────────────────────────── --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-[1rem] border border-gray-100 shadow-2xl shadow-gray-100/50 overflow-hidden sticky top-16">
                    
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900 text-xl">Summary</h2>
                        <span class="text-gray-400 font-medium" style="font-size:11px">{{ count($passengers) }} Passenger{{ count($passengers) > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        @foreach ($summaryItems as $i => $item)
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-400 font-medium leading-tight" style="font-size:11px">{{ $item['label'] }}</span>
                                    @if ($item['removable'])
                                        <button
                                            wire:click="removeItem({{ $i }})"
                                            class="text-indigo-500 hover:underline text-left"
                                            style="font-size:10px"
                                        >Remove</button>
                                    @endif
                                </div>
                                <span class="font-bold text-gray-900 flex-shrink-0" style="font-size:13px">${{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach

                        <div class="pt-4 mt-2 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-gray-900 font-bold text-base">Total</span>
                            <span class="text-gray-900 font-bold text-2xl">${{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="px-6 pb-6 flex gap-3">
                        <button
                            wire:click="back"
                            class="flex-1 py-3 border border-gray-200 text-indigo-600 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-xs"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button
                            wire:click="continue"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 text-xs"
                        >
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-2">
                                Continue <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-2">
                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
