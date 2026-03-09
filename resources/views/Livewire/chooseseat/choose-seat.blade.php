<div>
    {{-- ── Outbound Header (Teal Bar) ── --}}
    <div class="max-w-7xl mx-auto px-4 pt-6">
        <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2 shadow-lg shadow-[#2ab4c0]/20" style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
            <div>
                <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? '' }} → {{ $searchParams['destination'] ?? '' }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">{{ $searchParams['travelClass'] ?? 'Economy' }}</span>
                    <span class="px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider">
                        {{ ($searchParams['adultCount'] ?? 1) + ($searchParams['childCount'] ?? 0) + ($searchParams['infantCount'] ?? 0) }} Passenger(s)
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-white/90">Departure Date</p>
                <p class="text-base font-bold text-white">{{ \Carbon\Carbon::parse($searchParams['departDate'] ?? now())->format('l d.m.Y') }}</p>
            </div>
        </div>
    </div>

    {{-- ── Main Body ── --}}
    <div class="max-w-7xl mx-auto px-4 pb-12 flex flex-col lg:flex-row gap-6">

        {{-- ── LEFT: Flight Details & Fares ── --}}
        <div class="flex-1 min-w-0 space-y-6">

            {{-- Flight Card styling --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 relative hover:z-50" 
                 x-data="{ 
                    fareOpen: {{ !empty($availableFares) ? 'true' : 'false' }}, 
                    cabin: '{{ !empty($availableFares) ? strtolower(array_key_first($availableFares)) : 'economy' }}' 
                 }">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-t-xl">
                    <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        
                        Selected Flight Details
                    </h2>
                    <button type="button" @click="fareOpen = !fareOpen" class="bg-[#2ab4c0]/10 text-[#2ab4c0] hover:bg-[#2ab4c0] hover:text-white px-3 py-1.5 rounded-lg transition-all text-xs font-bold flex items-center gap-2">
                        <span x-show="!fareOpen">Manage Fares</span>
                        <span x-show="fareOpen" style="display: none;">Close Panel</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="fareOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Unified Flight Card --}}
                <div class="flex flex-col lg:flex-row">
                    
                    {{-- Left Content: Itinerary --}}
                    <div class="lg:w-[83%] flex-1 border-r border-gray-100 p-4 sm:p-5">
                        {{-- Flight Details Column --}}
                        <div class="flex-1 space-y-1">
                            @php $segIdx = 0; @endphp
                            @foreach($selectedFlight['rawOffer']['itineraries'] ?? [] as $idx => $rawItin)
                                @php 
                                    $mappedItin = $selectedFlight['itineraries'][$idx] ?? [];
                                    $segments = $rawItin['segments'] ?? [];
                                @endphp

                                @foreach($segments as $sIdx => $segment)
                                    @php
                                        $depTime = \Carbon\Carbon::parse($segment['departure']['at'])->format('H:i');
                                        $arrTime = \Carbon\Carbon::parse($segment['arrival']['at'])->format('H:i');
                                        
                                        $durationRaw = $segment['duration'] ?? '';
                                        $segmentDuration = '0h 0m';
                                        if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $durationRaw, $matches)) {
                                            $h = (int)($matches[1] ?? 0);
                                            $m = (int)($matches[2] ?? 0);
                                            $segmentDuration = "{$h}h {$m}m";
                                        }
                                        
                                        $carrierCode = $segment['carrierCode'] ?? '';
                                        $airlineName = ($carrierCode === ($mappedItin['airlineCode'] ?? '')) ? ($mappedItin['airline'] ?? $carrierCode) : $carrierCode;
                                        $flightNumber = $carrierCode . ($segment['number'] ?? '');
                                        $aircraftCode = $segment['aircraft']['code'] ?? '';
                                    @endphp

                                    <div wire:click="changeSegment({{ $segIdx }})" 
                                         class="flex flex-col sm:grid sm:grid-cols-4 items-center gap-4 sm:gap-6 p-4 rounded-xl cursor-pointer transition-all border-2 {{ $currentSegmentIndex === $segIdx ? 'border-[#2ab4c0] bg-[#2ab4c0]/5 shadow-sm' : 'border-transparent hover:bg-gray-50' }}">
                                        {{-- Airline Column --}}
                                        <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-white border border-gray-100 p-1 shadow-sm">
                                                <img src="https://pics.avs.io/128/128/{{ $carrierCode }}.png" 
                                                     alt="{{ $airlineName }}" 
                                                     class="w-full h-full object-contain">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter leading-none">{{ $airlineName }}</span>
                                                @if($currentSegmentIndex === $segIdx)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-[#2ab4c0] text-[8px] font-black text-white uppercase tracking-widest mt-1 w-max">
                                                        <span class="w-1 h-1 rounded-full bg-white animate-pulse"></span>
                                                        Seating Now
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Time & Route --}}
                                        <div class="flex flex-col justify-center">
                                            <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                                <span>{{ $depTime }}</span>
                                                <span class="text-gray-300 font-light">–</span>
                                                <span>{{ $arrTime }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                                <span class="border-b border-dotted border-gray-300">{{ $segment['departure']['iataCode'] }}</span>
                                                <span class="text-gray-300 font-normal ml-0.5">–</span>
                                                <span class="border-b border-dotted border-gray-300 ml-0.5">{{ $segment['arrival']['iataCode'] }}</span>
                                                <span class="mx-1 text-gray-300">|</span>
                                                <span class="text-gray-500">{{ $flightNumber }}</span>
                                                <span class="mx-1 text-gray-300">|</span>
                                                <span class="text-[10px] text-gray-400 font-medium">{{ $aircraftCode }}</span>
                                            </div>
                                        </div>

                                        {{-- Duration/Stops --}}
                                        <div class="w-full text-center py-2 sm:py-0 border-y sm:border-y-0 border-gray-50">
                                            <div class="text-sm font-black text-gray-800 tracking-tighter">{{ $segmentDuration }}</div>
                                            @php
                                                $segmentChosenSeats = [];
                                                foreach($passengerSeats[$segIdx] ?? [] as $pIdx => $s) {
                                                    if(!empty($s['id'])) {
                                                        $segmentChosenSeats[] = "P" . ($pIdx+1) . ": " . $s['id'];
                                                    }
                                                }
                                            @endphp
                                            @if(!empty($segmentChosenSeats))
                                                <div class="flex flex-wrap gap-1 justify-center mt-1">
                                                    @foreach($segmentChosenSeats as $seatStr)
                                                        <span class="px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[9px] font-black border border-indigo-100 shadow-sm">{{ $seatStr }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">Non-stop</div>
                                            @endif
                                        </div>

                                        {{-- Tools/Seats --}}
                                        <div class="w-full flex flex-col items-center justify-center gap-1.5 group relative" @click.stop>
                                            <div class="flex gap-4 text-gray-400">
                                                <div class="cursor-pointer">
                                                    <div class="flex gap-1.5 hover:text-[#2ab4c0] transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                    </div>

                                                    <div class="absolute bottom-full right-0 mb-3 hidden group-hover:block w-52 bg-white border border-gray-100 text-gray-600 shadow-2xl rounded-xl z-[100] overflow-hidden ring-1 ring-black/5">
                                                        <div class="p-3 bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase tracking-widest">Flight Amenities</div>
                                                        <div class="p-3 space-y-2 text-left">
                                                            @php 
                                                                $segmentId = $segment['id'];
                                                                $fareDetails = collect($selectedFlight['rawOffer']['travelerPricings'][0]['fareDetailsBySegment'] ?? []);
                                                                $segmentFare = $fareDetails->firstWhere('segmentId', $segmentId);
                                                                
                                                                $segmentAmenities = [];
                                                                if ($segmentFare && isset($segmentFare['amenities'])) {
                                                                    foreach ($segmentFare['amenities'] as $am) {
                                                                        $segmentAmenities[] = $am['description'] ?? 'Amenity';
                                                                    }
                                                                }
                                                                
                                                                $segmentBaggage = 'No checked bags';
                                                                if ($segmentFare && isset($segmentFare['includedCheckedBags'])) {
                                                                    $qty = $segmentFare['includedCheckedBags']['quantity'] ?? null;
                                                                    $weight = $segmentFare['includedCheckedBags']['weight'] ?? null;
                                                                    if ($qty !== null) {
                                                                        $segmentBaggage = $qty . ' Check-in bag' . ($qty > 1 ? 's' : '');
                                                                    } elseif ($weight !== null) {
                                                                        $segmentBaggage = $weight . ($segmentFare['includedCheckedBags']['weightUnit'] ?? 'KG') . ' Check-in bag';
                                                                    }
                                                                }
                                                            @endphp

                                                            @forelse($segmentAmenities as $am)
                                                                <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                                                    <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> 
                                                                    {{ $am }}
                                                                </div>
                                                            @empty
                                                                <div class="text-[10px] text-gray-400 italic">No specific amenities info</div>
                                                            @endforelse

                                                            <div class="flex items-center gap-2.5 text-[11px] font-medium border-t border-gray-50 pt-2 mt-2">
                                                                <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> 
                                                                Baggage: {{ $segmentBaggage }}
                                                            </div>
                                                        </div>
                                                        <div class="absolute top-full right-4 -mt-1 border-4 border-transparent border-t-white"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Seats Indicator --}}
                                            @if(($selectedFlight['seats'] ?? 0) > 0)
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                    <span class="text-[11px] font-black text-orange-500 uppercase tracking-tighter">{{ $selectedFlight['seats'] }} Seats Left</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @php $segIdx++; @endphp

                                    {{-- Layover Information --}}
                                    @if(isset($segments[$sIdx + 1]))
                                        @php
                                            $arrAt = \Carbon\Carbon::parse($segment['arrival']['at']);
                                            $nextDepAt = \Carbon\Carbon::parse($segments[$sIdx + 1]['departure']['at']);
                                            $diff = $nextDepAt->diff($arrAt);
                                            $layoverDuration = ($diff->h > 0 ? $diff->h . 'h ' : '') . $diff->i . 'm';
                                            $layoverCity = $segment['arrival']['iataCode'];
                                        @endphp
                                        <div class="py-4 my-2 relative">
                                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                                <div class="w-full border-t border-dashed border-gray-200"></div>
                                            </div>
                                            <div class="relative flex justify-center">
                                                <span class="px-4 py-1 bg-amber-50 rounded-full text-[10px] font-black text-amber-600 uppercase tracking-widest border border-amber-100 shadow-sm flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Stopover in {{ $layoverCity }} • {{ $layoverDuration }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                @if($idx < count($selectedFlight['rawOffer']['itineraries']) - 1)
                                    <div class="relative py-6">
                                        <div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-t-2 border-gray-100"></div></div>
                                        <div class="relative flex justify-center">
                                            <span class="px-3 py-1 bg-gray-100 rounded-lg text-[9px] font-black text-gray-400 uppercase tracking-[0.3em]">Next Flight</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>                
                </div>
            </div>

            {{-- Ancillary Services Cards (Dynamic Placeholder for Baggage/Lounge) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                 {{-- Dynamic Ancillaries from API --}}
                 @foreach($availableAncillaries as $anc)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-md p-5 flex items-center gap-5 group transition-all hover:shadow-lg">
                        <div class="w-16 h-16 rounded-2xl bg-[#2ab4c0]/5 text-[#2ab4c0] flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">{{ ucwords(strtolower($anc['description'])) }}</h4>
                            <p class="text-xs text-gray-500 mb-2">{{ $anc['amenityType'] === 'PRE_RESERVED_SEAT_DURING_BOOKING' ? 'Choose prefered seats during checkout.' : 'Available for this flight.' }}</p>
                            <button wire:click="toggleAncillary('{{ $anc['code'] }}')"
                                    class="text-xs font-bold px-4 py-1.5 rounded-lg transition-all {{ isset($selectedAncillaries[$anc['code']]) ? 'bg-[#2ab4c0] text-white shadow-md' : 'bg-gray-100 text-[#2ab4c0] hover:bg-gray-200' }}">
                                {{ isset($selectedAncillaries[$anc['code']]) ? 'Added' : 'Add Service' }}
                            </button>
                        </div>
                    </div>
                 @endforeach
            </div>

             <!-- Seat Map Legend -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        @if(isset($flightInfo[$currentSegmentIndex]))
                            @php $currSeg = $flightInfo[$currentSegmentIndex]; @endphp
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                                <h3 class="text-xs font-bold text-gray-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Seating for {{ $currSeg['origin'] }} → {{ $currSeg['destination'] }}
                                    <span class="text-gray-400 font-medium font-mono text-[10px]">({{ $currSeg['flightNumber'] }})</span>
                                </h3>
                                <div class="px-2 py-0.5 rounded bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest border border-amber-100">
                                    Segment {{ $currentSegmentIndex + 1 }} of {{ count($flightInfo) }}
                                </div>
                            </div>
                        @endif
                        <div class="px-4 py-2.5 border-b border-gray-100 flex flex-wrap gap-4">
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded bg-gray-100 border border-gray-300 flex-shrink-0"
                                    style="border-radius:3px 3px 2px 2px"></div>
                                <span class="text-gray-600" style="font-size:10px">Standard Seats</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 flex-shrink-0"
                                    style="background:#a5f3fc;border:1px solid #67e8f9;border-radius:3px 3px 2px 2px">
                                </div>
                                <span class="text-gray-600" style="font-size:10px">Extra Legroom</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 flex-shrink-0"
                                    style="background:#cbd5e1;border:1px solid #94a3b8;border-radius:3px 3px 2px 2px">
                                </div>
                                <span class="text-gray-600" style="font-size:10px">Occupied</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 flex-shrink-0"
                                    style="background:#6366f1;border:1px solid #4f46e5;border-radius:3px 3px 2px 2px">
                                </div>
                                <span class="text-gray-600" style="font-size:10px">Selected</span>
                            </div>
                        </div>

                        <!-- Seat Map (dynamic) -->
                        <div class="px-4 py-4 overflow-x-auto">
                            <div class="min-w-max mx-auto" style="max-width:340px">
                                @if(empty($rows))
                                    <!-- Fallback: seatmap unavailable -->
                                    <div class="text-center py-10">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                        </svg>
                                        <p class="text-gray-500 font-medium text-sm">Seat map is not available for this
                                            flight.
                                        </p>
                                        <p class="text-gray-400 mt-1" style="font-size:10px">You can proceed without
                                            selecting a
                                            seat.</p>
                                    </div>
                                @else
                                    <!-- Column headers -->
                                    <div class="flex items-center mb-2 pl-7">
                                        <div class="flex gap-1.5 mr-4">
                                            @foreach($leftCols as $col)
                                                <div class="w-[22px] text-center text-gray-400 font-semibold"
                                                    style="font-size:9px">
                                                    {{ $col }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="w-5"></div>
                                        <div class="flex gap-1.5 ml-4">
                                            @foreach($rightCols as $col)
                                                <div class="w-[22px] text-center text-gray-400 font-semibold"
                                                    style="font-size:9px">
                                                    {{ $col }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Rows (dynamic from Amadeus seatmap API) -->
                                    <div id="seat-map" class="space-y-1">
                                        @foreach($rows as $row)
                                            {{-- Extra legroom row divider (not before first row) --}}
                                            @if($row['isExtra'] && !$loop->first)
                                                <div class="h-2 flex items-center">
                                                    <div class="flex-1 border-t border-dashed border-cyan-300 mx-7"></div>
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-1">
                                                {{-- Row number --}}
                                                <div class="w-6 text-right text-gray-400 font-medium flex-shrink-0"
                                                    style="font-size:9px">
                                                    {{ $row['number'] }}
                                                </div>

                                                {{-- Left seat group --}}
                                                <div class="flex gap-1.5">
                                                    @foreach($leftCols as $col)
                                                        @if(isset($row['seats'][$col]))
                                                            @php $seat = $row['seats'][$col]; @endphp
                                                            <button id="seat-{{ $seat['id'] }}"
                                                                wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                                @disabled($seat['state'] === 'occupied')
                                                                class="seat seat-{{ $seat['state'] === 'extra' ? 'extra-legroom' : $seat['state'] }}">
                                                                {{ $seat['state'] === 'selected' ? '✓' : '' }}
                                                            </button>
                                                        @else
                                                            <div class="w-[22px]"></div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                {{-- Aisle --}}
                                                <div class="w-5 flex-shrink-0"></div>

                                                {{-- Right seat group --}}
                                                <div class="flex gap-1.5">
                                                    @foreach($rightCols as $col)
                                                        @if(isset($row['seats'][$col]))
                                                            @php $seat = $row['seats'][$col]; @endphp
                                                            <button id="seat-{{ $seat['id'] }}"
                                                                wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                                @disabled($seat['state'] === 'occupied')
                                                                class="seat seat-{{ $seat['state'] === 'extra' ? 'extra-legroom' : $seat['state'] }}">
                                                                {{ $seat['state'] === 'selected' ? '✓' : '' }}
                                                            </button>
                                                        @else
                                                            <div class="w-[22px]"></div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Exit row labels -->
                                    <div class="flex justify-between mt-2 px-1">
                                        <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                                        <span class="text-gray-400 font-medium" style="font-size:9px">Exit Row</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    

        </div>

        {{-- ── RIGHT: Premium Summary Sidebar ── --}}
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-200/40 overflow-hidden sticky top-6">
                {{-- Summary Header --}}
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </span>
                        Price Summary
                    </h3>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Line Items --}}
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
                            <span class="text-sm font-bold text-gray-900">{{ $currencyCode }}{{ number_format($item['amount'], 2) }}</span>
                        </div>
                    @endforeach

                    {{-- Total Section --}}
                    <div class="pt-5 mt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Final Total</span>
                            <span class="text-gray-900 text-2xl font-black">{{ $currencyCode }}{{ number_format($this->total, 2) }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium">Prices include all taxes and carrier-imposed fees.</p>
                    </div>

                    {{-- Primary Action Buttons --}}
                    <div class="space-y-3 pt-2">
                        <button wire:click="continue" wire:loading.attr="disabled"
                                class="w-full py-4 bg-[#2ab4c0] text-white font-black rounded-2xl hover:bg-[#2399a3] shadow-lg shadow-[#2ab4c0]/30 transition-all flex items-center justify-center gap-3 group">
                            <span wire:loading.remove wire:target="continue" class="flex items-center gap-3">
                                Review Seating
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </span>
                            <span wire:loading wire:target="continue" class="flex items-center gap-3">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Syncing...
                            </span>
                        </button>

                        <button wire:click="back" class="w-full py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back to Flight List
                        </button>
                    </div>
                </div>

                {{-- Trust Badges --}}
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-center gap-4">
                    <div class="flex items-center gap-1 opacity-40">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Safe</span>
                    </div>
                    <div class="flex items-center gap-1 opacity-40">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Secure</span>
                    </div>
                </div>
            </div>
        </div>

          

    </div>

    <style>
        .active-fare-tab {
            color: #2ab4c0 !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes loading {
            from { transform: translateX(-100%); }
            to { transform: translateX(400%); }
        }
    </style>
</div>
