<div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Main Form ─────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">



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
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold" style="font-size:10px">{{ $index + 1 }}</span>
                            Passenger {{ $index + 1 }}
                        </h2>
                        @php
                            $typeLabel = match($passenger['type']) {
                                'ADULT' => 'Adult (over 12 years)',
                                'CHILD' => 'Child (2-11 years)',
                                'HELD_INFANT' => 'Infant (under 2 years)',
                                default => 'Passenger'
                            };
                        @endphp
                        <span class="text-gray-400" style="font-size:10px">{{ $typeLabel }}</span>
                    </div>

                    {{-- Info notice --}}
                    <div class="mx-4 mt-3 flex items-start gap-2 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">
                        <svg class="w-3.5 h-3.5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                        <p class="text-indigo-600" style="font-size:10px">To avoid boarding difficulties, enter all first and last names exactly as they appear on your Passport/ID.</p>
                    </div>

                    <div class="px-4 py-3 space-y-3">

                        {{-- First / Last name --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-gray-500 mb-1" style="font-size:10px">First Name</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="passengers.{{ $index }}.first_name"
                                    placeholder="First Name"
                                    class="w-full px-3 py-2 border @error('passengers.'.$index.'.first_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700"
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
                                    class="w-full px-3 py-2 border @error('passengers.'.$index.'.last_name') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700"
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
                                    wire:model="passengers.{{ $index }}.dob_day"
                                    class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_day') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white"
                                >
                                    <option value="">Day</option>
                                    @foreach ($this->days as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                {{-- Month --}}
                                <select
                                    wire:model="passengers.{{ $index }}.dob_month"
                                    class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_month') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white"
                                >
                                    <option value="">Month</option>
                                    @foreach ($this->months as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                                {{-- Year --}}
                                <select
                                    wire:model="passengers.{{ $index }}.dob_year"
                                    class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.dob_year') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white"
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-gray-500 mb-1" style="font-size:10px">Nationality</label>
                                <div class="flex gap-2">
                                    <select
                                        wire:model="passengers.{{ $index }}.nationality"
                                        class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.nationality') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white"
                                    >
                                        <option value="">Select</option>
                                        <option>Turkish</option>
                                        <option>German</option>
                                        <option>American</option>
                                        <option>British</option>
                                    </select>
                                    <select
                                        wire:model="passengers.{{ $index }}.gender"
                                        class="flex-1 px-2 py-2 border @error('passengers.'.$index.'.gender') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700 bg-white"
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
                                    class="w-full px-3 py-2 border @error('passengers.'.$index.'.passport') border-red-400 @else border-gray-200 @enderror rounded-lg text-xs text-gray-700"
                                />
                                @error('passengers.'.$index.'.passport')
                                    <p class="mt-0.5 text-red-500" style="font-size:10px">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Visa disclaimer --}}
                        <div class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                            <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4M12 16h.01"/></svg>
                            <p class="text-amber-700" style="font-size:10px">Erkan is not liable for any passenger who is denied boarding or entry to any destination due to visa.</p>
                        </div>

                    </div>
                </div>
                @endforeach

                {{-- ── Baggage Info ─────────────────────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Cabin --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Cabin Baggage and Hand Bag</h3>
                        <p class="text-gray-500 mb-3" style="font-size:10px">Includes items carried in your own bag and items allowed under the rules set by the contracted airline.</p>
                        <div class="flex items-end gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-teal-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="10" y="12" width="28" height="30" rx="3"/>
                                    <path stroke-linecap="round" d="M18 12V8a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                    <line x1="24" y1="18" x2="24" y2="36"/>
                                    <line x1="16" y1="27" x2="32" y2="27"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Cabin Bag</span>
                                <span class="text-gray-400" style="font-size:9px">Cabin Bag (7 kg / 15lbs)</span>
                            </div>
                            <div class="flex flex-col items-center gap-1 ml-2">
                                <svg class="w-8 h-8 text-indigo-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="8" y="16" width="32" height="26" rx="3"/>
                                    <path d="M17 16V12a2 2 0 012-2h10a2 2 0 012 2v4"/>
                                    <line x1="24" y1="22" x2="24" y2="36"/>
                                    <line x1="16" y1="29" x2="32" y2="29"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Personal Item</span>
                                <span class="text-gray-400" style="font-size:9px">Under Seat (4kg)</span>
                            </div>
                        </div>
                    </div>
                    {{-- Checked --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Checked Baggage Included In Your Flight</h3>
                        <p class="text-gray-500 mb-3" style="font-size:10px">These are the luggage delivered to the airline that will be carried in the aircraft's hold compartment.</p>
                        <div class="flex items-end gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-teal-400" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="10" y="10" width="28" height="32" rx="3"/>
                                    <path d="M18 10V6a2 2 0 012-2h8a2 2 0 012 2v4"/>
                                    <line x1="24" y1="18" x2="24" y2="34"/>
                                    <line x1="16" y1="26" x2="32" y2="26"/>
                                    <line x1="16" y1="38" x2="32" y2="38"/>
                                </svg>
                                <span class="text-gray-600" style="font-size:10px">1 Checked Baggage</span>
                                <span class="text-gray-400" style="font-size:9px">1 Checked Baggage (23kg)</span>
                            </div>
                        </div>
                    </div>
                </div>

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
```
