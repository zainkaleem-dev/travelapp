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

                        Choose Seats
                    </h2>
                   
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
                                         wire:key="segment-{{ $segIdx }}"
                                         wire:loading.class="opacity-50 pointer-events-none"
                                         class="flex flex-col sm:grid sm:grid-cols-3 items-center gap-4 sm:gap-6 p-4 rounded-xl cursor-pointer transition-all border-2 {{ $currentSegmentIndex === $segIdx ? 'border-[#2ab4c0] bg-[#2ab4c0]/5 shadow-sm' : 'border-transparent hover:bg-gray-50' }}">
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
                                        <div class="flex flex-col justify-center gap-1.5">
                                            {{-- Flight number centered --}}
                                            <div class="flex justify-center">
                                                <span class="text-[10px] text-gray-500 tracking-[0.22em] uppercase">{{ $flightNumber }}</span>
                                            </div>

                                            {{-- Times --}}
                                            <div class="flex items-center gap-3 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-bold text-gray-400 tracking-[0.18em] uppercase">Depart</span>
                                                    <span>{{ $depTime }}</span>
                                                </div>
                                                <div class="flex items-center justify-center flex-1 mx-1">
                                                    <div class="w-full max-w-[140px] h-[2px] bg-gray-200 rounded-full relative">
                                                        <div class="absolute inset-y-[-3px] left-1/2 -translate-x-1/2 flex items-center justify-center">
                                                            <span class="px-1.5 py-0.5 rounded-full bg-white border border-gray-200 text-[9px] font-black text-gray-500 tracking-[0.18em] uppercase">
                                                                Non‑stop
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <span class="text-[10px] font-bold text-gray-400 tracking-[0.18em] uppercase">Arrive</span>
                                                    <span>{{ $arrTime }}</span>
                                                </div>
                                            </div>

                                            {{-- Route trail --}}
                                            <div class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.22em] text-gray-500">
                                                <div class="flex items-center gap-1.5 w-full">
                                                    <span class="px-2 py-0.5 rounded-full bg-white border border-gray-200 text-gray-700">
                                                        {{ $segment['departure']['iataCode'] }}
                                                    </span>
                                                    <span class="flex items-center gap-2 text-gray-300 flex-1">
                                                        <span class="flex-1 h-[2px] bg-gray-300 rounded-full"></span>
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6" />
                                                        </svg>
                                                        <span class="flex-1 h-[2px] bg-gray-300 rounded-full"></span>
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-full bg-white border border-gray-200 text-gray-700">
                                                        {{ $segment['arrival']['iataCode'] }}
                                                    </span>
                                                </div>


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

            {{-- Passenger Selection Tabs --}}
            <div class="flex items-center gap-3 overflow-x-auto pb-2 no-scrollbar">
                @for ($i = 0; $i < $passengerCount; $i++)
                    @php
                        $hasSeat = !empty($passengerSeats[$currentSegmentIndex][$i]['id']);
                        $isActive = $currentPassengerIndex === $i;
                    @endphp
                    <button wire:click="selectPassenger({{ $i }})"
                            class="flex items-center gap-3 px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm min-w-max {{ $isActive ? 'border-[#2ab4c0] bg-[#2ab4c0]/5' : ($hasSeat ? 'border-gray-200 bg-white hover:border-gray-300' : 'border-dashed border-gray-300 bg-gray-50 hover:border-gray-400') }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs {{ $isActive ? 'bg-[#2ab4c0] text-white shadow-md shadow-[#2ab4c0]/30' : ($hasSeat ? 'bg-[#2ab4c0] text-white' : 'bg-gray-200 text-gray-500') }}">
                            @if($hasSeat && !$isActive)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="{{ $isActive ? 'text-[#2ab4c0]' : ($hasSeat ? 'text-gray-800' : 'text-gray-500') }}">Passenger {{ $i + 1 }}</span>
                            @if($hasSeat)
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $isActive ? 'text-[#2ab4c0]' : 'text-[#2ab4c0]' }}">Seat {{ $passengerSeats[$currentSegmentIndex][$i]['id'] }}</span>
                            @else
                                <span class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Select Seat</span>
                            @endif
                        </div>
                    </button>
                @endfor
            </div>

            <!-- Enhanced Seat Map UI -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mt-4">
                @if(isset($flightInfo[$currentSegmentIndex]))
                    @php $currSeg = $flightInfo[$currentSegmentIndex]; @endphp
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div>
                            <h3 class="text-sm font-black text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Seating for {{ $currSeg['origin'] }} → {{ $currSeg['destination'] }}
                            </h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Flight {{ $currSeg['flightNumber'] }} • Segment {{ $currentSegmentIndex + 1 }} of {{ count($flightInfo) }}</p>
                        </div>
                        <div class="hidden md:flex gap-4">
                             <!-- Legend inside header -->
                             <div class="flex flex-col gap-1.5">
                                 <div class="flex items-center gap-2">
                                     <div class="w-3.5 h-3.5 rounded-sm bg-gray-100 border border-gray-200"></div>
                                     <span class="text-[10px] font-bold text-gray-500 uppercase">Standard</span>
                                 </div>
                                 <div class="flex items-center gap-2">
                                     <div class="w-3.5 h-3.5 rounded-sm bg-sky-100 border border-sky-200 relative"><div class="absolute -top-1 left-1.5 w-1 h-2 bg-sky-400 rounded-full"></div></div>
                                     <span class="text-[10px] font-bold text-gray-500 uppercase">Extra Legroom</span>
                                 </div>
                             </div>
                             <div class="flex flex-col gap-1.5">
                                 <div class="flex items-center gap-2">
                                     <div class="w-3.5 h-3.5 rounded-sm bg-gray-50 border border-gray-100 text-gray-300 flex items-center justify-center text-[10px] font-bold">×</div>
                                     <span class="text-[10px] font-bold text-gray-400 uppercase">Occupied</span>
                                 </div>
                                 <div class="flex items-center gap-2">
                                     <div class="w-3.5 h-3.5 rounded-sm bg-[#2ab4c0] shadow-sm shadow-[#2ab4c0]/30 text-white flex items-center justify-center text-[10px] font-bold">14A</div>
                                     <span class="text-[10px] font-bold text-[#2ab4c0] uppercase">Selected</span>
                                 </div>
                             </div>
                        </div>
                    </div>
                @endif

                <div class="px-4 py-8 bg-gray-50 min-h-[400px]">
                    <div class="min-w-max mx-auto fuselage-container" style="max-width:380px;">
                        @if(empty($rows))
                            <div class="text-center py-20">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                </svg>
                                <p class="text-gray-800 font-bold text-lg">Seat map unavailable</p>
                                <p class="text-gray-500 text-sm mt-1">You can proceed without selecting a seat.</p>
                            </div>
                        @else
                            <!-- Column headers -->
                            <div class="flex items-center mb-6 pl-8">
                                <div class="flex gap-2 mr-5">
                                    @foreach($leftCols as $col)
                                        <div class="w-[40px] text-center text-gray-400 font-black text-xs uppercase">{{ $col }}</div>
                                    @endforeach
                                </div>
                                <div class="w-6"></div>
                                <div class="flex gap-2 ml-5">
                                    @foreach($rightCols as $col)
                                        <div class="w-[40px] text-center text-gray-400 font-black text-xs uppercase">{{ $col }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Rows -->
                            <div class="space-y-3 pb-8 relative z-10">
                                @foreach($rows as $row)
                                    @if($row['isExtra'] && !$loop->first)
                                        <div class="h-6 flex items-center justify-center mt-6 mb-4">
                                            <div class="px-4 py-1 bg-sky-50 text-sky-600 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-sky-100 flex items-center gap-2 shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                Extra Legroom
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center">
                                        <div class="w-8 text-center text-gray-400 font-black text-xs flex-shrink-0 relative focus-within:z-50">
                                            {{ $row['number'] }}
                                        </div>

                                        <div class="flex gap-2">
                                            @foreach($leftCols as $col)
                                                @if(isset($row['seats'][$col]))
                                                    @php $seat = $row['seats'][$col]; @endphp
                                                    <button wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                            @disabled($seat['state'] === 'occupied')
                                                            class="seat-btn seat-{{ $seat['state'] }}" title="{{ $seat['id'] }} - {{ $currencyCode }}{{ number_format($seat['price'], 2) }}">
                                                            <span>{{ $seat['id'] }}</span>
                                                    </button>
                                                @else
                                                    <div class="w-[40px]"></div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="w-10 flex-shrink-0 flex items-center justify-center">
                                            <div class="w-0.5 h-12 bg-gray-200/50 rounded-full"></div>
                                        </div>

                                        <div class="flex gap-2">
                                            @foreach($rightCols as $col)
                                                @if(isset($row['seats'][$col]))
                                                    @php $seat = $row['seats'][$col]; @endphp
                                                    <button wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                            @disabled($seat['state'] === 'occupied')
                                                            class="seat-btn seat-{{ $seat['state'] }}" title="{{ $seat['id'] }} - {{ $currencyCode }}{{ number_format($seat['price'], 2) }}">
                                                            <span>{{ $seat['id'] }}</span>
                                                    </button>
                                                @else
                                                    <div class="w-[40px]"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
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

                    {{-- Primary Action Buttons (same line) --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button wire:click="back" class="flex-1 min-w-0 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-[11px]">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button wire:click="goToPassengerDetails" wire:loading.attr="disabled"
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

        /* Premium Seat Map Styles */
        .seat-btn {
            width: 40px;
            height: 44px;
            border-radius: 8px 8px 4px 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 900;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid transparent;
            position: relative;
        }
        .seat-btn span {
            z-index: 10;
        }
        .seat-btn:not(:disabled):hover {
            color: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .seat-available { background: #ffffff; border-color: #e2e8f0; color: #94a3b8; }
        .seat-available:hover { background: #f8fafc; border-color: #cbd5e1; color: #64748b; }

        .seat-extra { background: #f0f9ff; border-color: #bae6fd; color: #7dd3fc; }
        .seat-extra::after {
            content: ''; position: absolute; top: -4px; left: 50%; transform: translateX(-50%);
            width: 14px; height: 3px; background: #38bdf8; border-radius: 2px;
        }
        .seat-extra:hover { background: #e0f2fe; border-color: #7dd3fc; color: #0284c7; }

        .seat-occupied { background: #f8fafc; border-color: #f1f5f9; cursor: not-allowed; opacity: 0.6; }
        .seat-occupied::before {
            content: '×'; color: #cbd5e1; font-size: 18px; font-weight: 300; position: absolute; line-height: 1;
        }

        .seat-selected { background: #2ab4c0; border-color: #239ba6; color: white !important; box-shadow: 0 4px 10px rgba(42,180,192,0.3); transform: translateY(-3px); }
        .seat-selected:hover {
            color: white !important;
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(42,180,192,0.4);
        }

        .fuselage-container {
            background: #ffffff;
            border: 4px solid #f1f5f9;
            border-radius: 120px 120px 0 0;
            padding: 70px 20px 40px;
            margin: 0 auto;
            position: relative;
            box-shadow: inset 0 0 40px rgba(0,0,0,0.02), 0 10px 40px rgba(0,0,0,0.03);
            border-bottom: none;
        }
        .fuselage-container::before {
            content: ''; position: absolute; top: 15px; left: 50%; transform: translateX(-50%);
            width: 70px; height: 120px; border: 2px solid #e2e8f0; border-radius: 100px; opacity: 0.4;
        }
    </style>
</div>
