<div>

    {{-- ─── Navbar ──────────────────────────────────────────────────────────── --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">
            <div class="flex items-center gap-2 flex-shrink-0">
                <button wire:click="back" class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
                </button>
                <button wire:click="continue" class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-indigo-200 text-indigo-600 font-medium hover:bg-indigo-50 transition-colors">
                    Next<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <button class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Find Reservation
                </button>
                <button class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden sm:inline">Login / Register</span>
                    <span class="sm:hidden">Login</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ─── Step Progress ───────────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Flight Tickets / Flight on Passenger Details</div>
            <div class="flex items-center overflow-x-auto">
                <div class="flex items-center gap-1.5 px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                    <span class="hidden sm:inline">Select Flight</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap" style="background:#2ab4c0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Passenger Details
                </div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap">Additional Services</div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden sm:block">Choice Seat</div>
                <div class="px-3 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap ml-auto hidden sm:block">Payment</div>
            </div>
        </div>
    </div>

    {{-- ─── Page Body ───────────────────────────────────────────────────────── --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── LEFT: Main Form ─────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-3">

                {{-- Flight Details --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Flight Details</h2>
                    </div>

                    {{-- Outbound --}}
                    @if(isset($selectedFlight['rawOffer']['itineraries'][0]))
                    <div class="px-4 py-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-semibold mb-3" style="background:#2ab4c0; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            Outbound Flight
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-7 h-7 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold flex-shrink-0 relative overflow-hidden">
                                <img src="https://pics.avs.io/64/64/{{ $selectedFlight['airlineCode'] ?? '??' }}.png" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $selectedFlight['dep'] ?? '00:00' }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $selectedFlight['depAirport'] ?? '---' }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">{{ $selectedFlight['stops'] ?? 'Direct' }}</div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-gray-400" style="font-size:9px">{{ $selectedFlight['duration'] ?? '--' }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $selectedFlight['arr'] ?? '00:00' }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $selectedFlight['arrAirport'] ?? '---' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($selectedFlight['rawOffer']['itineraries'][1]))
                    <div class="mx-4 border-t border-dashed border-gray-200"></div>

                    {{-- Return --}}
                    <div class="px-4 py-3">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white font-semibold mb-3" style="background:#6366f1; font-size:10px">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                            Return Flight
                        </div>
                        @php
                            $retItin = $selectedFlight['rawOffer']['itineraries'][1];
                            $retFirst = $retItin['segments'][0];
                            $retLast = end($retItin['segments']);
                            $retDep = date('H:i', strtotime($retFirst['departure']['at']));
                            $retArr = date('H:i', strtotime($retLast['arrival']['at']));
                            $retDuration = str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $retItin['duration']);
                            $retStopsCount = count($retItin['segments']) - 1;
                            $retStops = $retStopsCount === 0 ? 'Direct' : $retStopsCount . ' Stop' . ($retStopsCount > 1 ? 's' : '');
                        @endphp
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-7 h-7 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold flex-shrink-0 relative overflow-hidden">
                                <img src="https://pics.avs.io/64/64/{{ $retFirst['carrierCode'] ?? '??' }}.png" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $retDep }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $retFirst['departure']['iataCode'] }}</p>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-0.5 min-w-0">
                                        <div class="relative w-full flight-line flex items-center justify-between">
                                            <div class="flight-dot"></div>
                                            <div class="bg-white border border-gray-200 rounded px-1.5 py-0.5 relative z-10 text-gray-500" style="font-size:9px">{{ $retStops }}</div>
                                            <div class="flight-dot"></div>
                                        </div>
                                        <p class="text-gray-400" style="font-size:9px">{{ $retDuration }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm">{{ $retArr }}</p>
                                        <p class="text-gray-500" style="font-size:9px">{{ $retLast['arrival']['iataCode'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

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
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-16">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-sm">Summary</h2>
                        <span class="text-gray-500" style="font-size:10px">{{ count($passengers) }} Passenger{{ count($passengers) > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="px-4 py-3 space-y-2">
                        @foreach ($summaryItems as $i => $item)
                            <div class="flex items-center justify-between {{ $loop->last ? 'pt-1' : '' }} {{ $loop->index === count($summaryItems) - 2 ? 'pb-2 border-b border-gray-100' : '' }}">
                                <div class="flex items-center gap-1">
                                    <span class="text-gray-500" style="font-size:10px">{{ $item['label'] }}</span>
                                    @if ($item['removable'])
                                        <button
                                            wire:click="removeItem({{ $i }})"
                                            class="ml-1 text-indigo-500 hover:underline"
                                            style="font-size:10px"
                                        >Remove</button>
                                    @endif
                                </div>
                                <span class="{{ $loop->last ? 'font-bold text-gray-900 text-lg' : 'font-semibold text-gray-800' }}">${{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-between pt-1 border-t border-gray-100">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-gray-900 text-lg">${{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>
                    <div class="px-4 pb-4 flex gap-2">
                        <button
                            wire:click="back"
                            class="flex-1 py-2 border border-indigo-200 text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition-colors flex items-center justify-center gap-1"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back
                        </button>
                        <button
                            wire:click="continue"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-1"
                        >
                            <span wire:loading.remove wire:target="continue">
                                Continue<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-1">
                                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Saving…
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
