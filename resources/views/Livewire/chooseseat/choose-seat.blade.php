<div>

    <div class="w-full py-4 flex flex-col lg:flex-row gap-6">
        <main class="flex-1 min-w-0">
            {{-- ── Outbound Header (Teal Bar) ── --}}
            <div class="bg-[#2ab4c0] text-white rounded-xl px-4 py-3 mb-6 flex items-center justify-between flex-wrap gap-2 shadow-lg shadow-[#2ab4c0]/20"
                style="text-shadow: 0 1px 2px rgba(0,0,0,0.15);">
                <div>
                    @if ($searchParams['isMulti'] ?? false && !empty($searchParams['segments']))
                        <p class="text-base font-bold text-white">Multi-City Trip</p>
                        <p class="text-xs font-medium text-white/90">
                            {{ count($searchParams['segments']) }} Flights:
                            {{ $searchParams['segments'][0]['origin'] }} →
                            {{ $searchParams['segments'][count($searchParams['segments']) - 1]['destination'] }}
                        </p>
                    @elseif(($searchParams['tripType'] ?? '') === 'return')
                        <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? '' }} →
                            {{ $searchParams['destination'] ?? '' }}
                        </p>
                        <p class="text-xs font-medium text-white/90">Return Trip</p>
                    @elseif(($searchParams['tripType'] ?? '') === 'oneway')
                        <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? '' }} →
                            {{ $searchParams['destination'] ?? '' }}
                        </p>
                        <p class="text-xs font-medium text-white/90">One-way Trip</p>
                    @else
                        <p class="text-base font-bold text-white">{{ $searchParams['origin'] ?? '' }} →
                            {{ $searchParams['destination'] ?? '' }}
                        </p>
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

            {{-- ── Main Body ── --}}
            <div class=" flex flex-col lg:flex-row gap-6 rounded-xl mb-3 overflow-hidden">
                <style>
                    .tab {
                        position: relative;
                        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    .tab.active {
                        color: #2ab4c0 !important;
                        font-weight: 800 !important;
                    }

                    .tab.active::after {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 20%;
                        right: 20%;
                        height: 2px;
                        background: #2ab4c0;
                        border-radius: 2px;
                    }

                    .tab:hover:not(.active) {
                        background: #f9fafb;
                    }
                </style>
                {{-- ── LEFT: Flight Details & Fares ── --}}
                <div class="flex-1 min-w-0 space-y-6">

                    {{-- Flight Card styling --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-md shadow-gray-200/50 relative"
                        x-data="{
                    fareOpen: {{ !empty($availableFares) ? 'true' : 'false' }},
                    cabin: '{{ !empty($availableFares) ? strtolower(array_key_first($availableFares)) : 'economy' }}'
                 }">
                        <div
                            class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-t-xl">
                            <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">

                                Choose Seats
                            </h2>

                        </div>

                        {{-- Unified Flight Card --}}
                        <div class="flex flex-col lg:flex-row">

                            {{-- Left Content: Itinerary --}}
                            <div
                                class="relative lg:w-[83%] flex-1 border-b border-gray-100 p-3 sm:p-5 overflow-hidden lg:border-b-0 lg:border-r">
                                {{-- Flight Details Column --}}
                                <div x-data="{
                                canScrollLeft: false,
                                canScrollRight: false,
                                updateButtons() {
                                    const el = this.$refs.scroller;
                                    if (!el) return;
                                    this.canScrollLeft = el.scrollLeft > 0;
                                    this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - 1;
                                },
                                scrollCards(direction) {
                                    const el = this.$refs.scroller;
                                    if (!el) return;
                                    el.scrollBy({ left: direction * 288, behavior: 'smooth' });
                                    setTimeout(() => this.updateButtons(), 250);
                                }
                            }" x-init="$nextTick(() => updateButtons())" @resize.window="updateButtons()"
                                    class="relative" wire:loading.class="opacity-70 pointer-events-none"
                                    wire:target="changeSegment">
                                    <button type="button" x-show="canScrollLeft" x-transition.opacity
                                        @click.prevent="scrollCards(-1)"
                                        class="absolute left-0 top-1/2 z-50 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-md hover:bg-gray-50 lg:flex"
                                        aria-label="Scroll left">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>

                                    <button type="button" x-show="canScrollRight" x-transition.opacity
                                        @click.prevent="scrollCards(1)"
                                        class="absolute right-0 top-1/2 z-50 hidden h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-md hover:bg-gray-50 lg:flex"
                                        aria-label="Scroll right">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    <div wire:loading wire:target="changeSegment"
                                        class="absolute left-1/2 top-1/2 z-[60] -translate-x-1/2 -translate-y-1/2 bg-white/25 backdrop-blur-[1px] rounded-xl p-2 pointer-events-none">
                                        <svg class="animate-spin w-6 h-6 text-[#2ab4c0]" fill="none" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                        </svg>
                                    </div>

                                    <div x-ref="scroller" @scroll.passive="updateButtons()"
                                        class="flex flex-nowrap items-stretch gap-2 overflow-x-auto px-0 py-2 pr-3 scroll-smooth no-scrollbar touch-pan-x sm:pr-0 lg:px-2">
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
                                                        $h = (int) ($matches[1] ?? 0);
                                                        $m = (int) ($matches[2] ?? 0);
                                                        $segmentDuration = "{$h}h {$m}m";
                                                    }

                                                    $carrierCode = $segment['carrierCode'] ?? '';
                                                    $airlineName = ($carrierCode === ($mappedItin['airlineCode'] ?? '')) ? ($mappedItin['airline'] ?? $carrierCode) : $carrierCode;
                                                    $flightNumber = $carrierCode . ($segment['number'] ?? '');
                                                    $aircraftCode = $segment['aircraft']['code'] ?? '';
                                                @endphp

                                                <div wire:click="changeSegment({{ $segIdx }})" wire:key="segment-{{ $segIdx }}"
                                                    wire:loading.class="opacity-50 pointer-events-none"
                                                    class="w-[240px] min-w-[240px] sm:w-[280px] sm:min-w-[280px] flex-shrink-0 rounded-2xl border-2 bg-white px-2 py-1.5 cursor-pointer transition-all {{ $currentSegmentIndex === $segIdx ? 'border-[#2ab4c0] shadow-md shadow-[#2ab4c0]/30' : 'border-gray-200 hover:border-[#2ab4c0]/40 hover:shadow-sm' }}">

                                                    {{-- Time & Route --}}
                                                    <div class="flex items-center w-full rounded-xl bg-gray-50/80 px-2 py-1.5">
                                                        <div class="flex items-center w-full gap-1 text-gray-900">
                                                            <div class="flex min-w-0 flex-1 flex-col">
                                                                <span
                                                                    class="leading-tight font-black text-base sm:text-inherit">{{ $segment['departure']['iataCode'] }}</span>
                                                                <span
                                                                    class="text-[11px] font-semibold leading-tight text-gray-600">{{ $depTime }}</span>
                                                            </div>

                                                            <div
                                                                class="flex min-w-0 flex-[1.15] flex-col justify-center text-center">
                                                                <span
                                                                    class="truncate text-[10px] font-bold uppercase tracking-[0.18em] text-gray-500">{{ $flightNumber }}</span>
                                                            </div>

                                                            <div
                                                                class="flex flex-shrink-0 items-center justify-center text-gray-300">
                                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400 mx-auto flex-shrink-0"
                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 12h14M13 6l6 6-6 6" />
                                                                </svg>
                                                            </div>

                                                            <div
                                                                class="flex min-w-0 flex-[1.05] flex-col justify-center text-center">
                                                                <span
                                                                    class="truncate text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-400">{{ $segmentDuration }}</span>
                                                            </div>

                                                            <div class="flex min-w-0 flex-1 flex-col items-end text-right">
                                                                <span
                                                                    class="leading-tight font-black text-base sm:text-inherit">{{ $segment['arrival']['iataCode'] }}</span>
                                                                <span
                                                                    class="text-[11px] font-semibold leading-tight text-gray-600">{{ $arrTime }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                @php $segIdx++; @endphp

                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ancillary Services Cards (Dynamic Placeholder for Baggage/Lounge) --}}

                    {{-- <div class="grid grid-cols-1 md:grid-cols-2 ">

                        @foreach($availableAncillaries as $anc)
                        <div
                            class="bg-white rounded-xl border border-gray-200 shadow-md p-5 flex items-center gap-5 group transition-all hover:shadow-lg">
                            <div
                                class="w-16 h-16 rounded-2xl bg-[#2ab4c0]/5 text-[#2ab4c0] flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">{{ ucwords(strtolower($anc['description'])) }}</h4>
                                <p class="text-xs text-gray-500 mb-2">
                                    {{ $anc['amenityType'] === 'PRE_RESERVED_SEAT_DURING_BOOKING' ? 'Choose prefered
                                    seats during checkout.' : 'Available for this flight.' }}
                                </p>
                                <button wire:click="toggleAncillary('{{ $anc['code'] }}')"
                                    class="text-xs font-bold px-4 py-1.5 rounded-lg transition-all {{ isset($selectedAncillaries[$anc['code']]) ? 'bg-[#2ab4c0] text-white shadow-md' : 'bg-gray-100 text-[#2ab4c0] hover:bg-gray-200' }}">
                                    {{ isset($selectedAncillaries[$anc['code']]) ? 'Added' : 'Add Service' }}
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div> --}}

                    {{-- Passenger Selection Tabs --}}
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
                        class="relative mt-4 group/passengers bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                        {{-- Scroll Buttons --}}
                        <button type="button" x-show="canScrollLeft" x-transition.opacity
                            @click.prevent="scrollPassenger(-1)"
                            class="absolute left-2 top-1/2 z-50 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-[#2ab4c0] shadow-sm hover:bg-white hover:shadow-md transition-all backdrop-blur-sm"
                            aria-label="Scroll left">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <button type="button" x-show="canScrollRight" x-transition.opacity
                            @click.prevent="scrollPassenger(1)"
                            class="absolute right-2 top-1/2 z-50 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-[#2ab4c0] shadow-sm hover:bg-white hover:shadow-md transition-all backdrop-blur-sm"
                            aria-label="Scroll right">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div x-ref="passengerScroller" @scroll.passive="updateButtons()"
                            class="flex items-center gap-3 overflow-x-auto px-4 py-3 pb-2 no-scrollbar touch-pan-x scroll-smooth">
                            @for ($i = 0; $i < $passengerCount; $i++)
                                @php
                                    $hasSeat = !empty($passengerSeats[$currentSegmentIndex][$i]['id']);
                                    $isActive = $currentPassengerIndex === $i;
                                @endphp
                                <button wire:click="selectPassenger({{ $i }})"
                                    class="flex items-center gap-3 px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm min-w-max {{ $isActive ? 'border-[#2ab4c0] bg-[#2ab4c0]/5' : ($hasSeat ? 'border-gray-200 bg-white hover:border-gray-300' : 'border-dashed border-gray-300 bg-gray-50 hover:border-gray-400') }}">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs {{ $isActive ? 'bg-[#2ab4c0] text-white shadow-md shadow-[#2ab4c0]/30' : ($hasSeat ? 'bg-[#2ab4c0] text-white' : 'bg-gray-200 text-gray-500') }}">
                                        @if($hasSeat && !$isActive)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <span
                                            class="{{ $isActive ? 'text-[#2ab4c0]' : ($hasSeat ? 'text-gray-800' : 'text-gray-500') }}">Passenger
                                            {{ $i + 1 }}</span>
                                        @if($hasSeat)
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest {{ $isActive ? 'text-[#2ab4c0]' : 'text-[#2ab4c0]' }}">Seat
                                                {{ $passengerSeats[$currentSegmentIndex][$i]['id'] }}</span>
                                        @else
                                            <span class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Select
                                                Seat</span>
                                        @endif
                                    </div>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <!-- Enhanced Seat Map UI -->
                    <div
                        class="mt-4 overflow-hidden rounded-[28px] border border-gray-200/80 bg-white shadow-[0_18px_45px_-20px_rgba(15,23,42,0.28)] ring-1 ring-gray-100">
                        @if(isset($flightInfo[$currentSegmentIndex]))
                            @php $currSeg = $flightInfo[$currentSegmentIndex]; @endphp
                            <div
                                class="flex items-center justify-between border-b border-gray-100/80 bg-gradient-to-r from-white via-slate-50 to-gray-50 px-5 py-4">
                                <div class="min-w-0">
                                    <h3 class="flex items-center gap-2 text-sm font-black text-gray-800">
                                        <span
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#2ab4c0]/10 text-[#2ab4c0] ring-1 ring-[#2ab4c0]/15">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </span>
                                        Seating for {{ $currSeg['origin'] }} → {{ $currSeg['destination'] }}
                                    </h3>
                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.22em] text-gray-400">Flight
                                        {{ $currSeg['flightNumber'] }} • {{ $currSeg['type'] }} • Segment
                                        {{ $currentSegmentIndex + 1 }}
                                    </p>
                                </div>
                                <div class="hidden md:flex items-start gap-3">
                                    <!-- Legend inside header -->
                                    <div
                                        class="flex flex-col gap-2 rounded-2xl border border-gray-200/80 bg-white/90 px-3 py-2 shadow-sm shadow-gray-200/40">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="inline-flex h-3.5 w-3.5 flex-shrink-0 items-center justify-center rounded-sm border border-gray-200 bg-gray-100">
                                            </div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-[0.18em] text-gray-500">Standard</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="relative inline-flex h-3.5 w-3.5 flex-shrink-0 items-center justify-center rounded-sm border border-sky-200 bg-sky-100">
                                                <div class="absolute -top-1 left-1.5 w-1 h-2 rounded-full bg-sky-400"></div>
                                            </div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-[0.18em] text-gray-500">Extra
                                                Legroom</span>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col gap-2 rounded-2xl border border-gray-200/80 bg-white/90 px-3 py-2 shadow-sm shadow-gray-200/40">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="inline-flex h-3.5 w-3.5 flex-shrink-0 items-center justify-center rounded-sm bg-gray-100 text-[10px] font-bold text-white rounded-sm border border-gray-200">
                                            </div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-[0.18em] text-gray-400">Occupied</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="inline-flex h-3.5 w-3.5 flex-shrink-0 items-center justify-center rounded-sm bg-[#2ab4c0] text-[10px] font-bold text-white">
                                            </div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#2ab4c0]">Selected</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div
                            class="min-h-[400px] bg-gradient-to-b from-slate-50 via-gray-50 to-white px-4 py-8 sm:px-5">
                            <div class="mx-auto min-w-max fuselage-container" style="max-width:380px;">
                                @if(empty($rows))
                                    <div class="text-center py-20">
                                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                        </svg>
                                        <p class="text-gray-800 font-bold text-lg">Seat map unavailable</p>
                                        <p class="text-gray-500 text-sm mt-1">You can proceed without selecting a seat.</p>
                                    </div>
                                @else
                                    <!-- Column headers -->
                                    <div
                                        class="mb-6 flex items-center rounded-full bg-white/80 px-3 py-2 shadow-sm ring-1 ring-gray-100 backdrop-blur-sm">
                                        <div
                                            class="w-8 text-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-300">
                                            Row</div>
                                        <div class="flex gap-2 mr-5">
                                            @foreach($leftCols as $col)
                                                <div
                                                    class="w-[40px] text-center text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                                    {{ $col }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="w-6"></div>
                                        <div class="flex gap-2 ml-5">
                                            @foreach($rightCols as $col)
                                                <div
                                                    class="w-[40px] text-center text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                                    {{ $col }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Rows -->
                                    <div class="relative z-10 space-y-3 pb-8">
                                        @foreach($rows as $row)
                                            @if($row['isExtra'] && !$loop->first)
                                                <div class="mt-6 mb-4 flex h-6 items-center justify-center">
                                                    <div
                                                        class="flex items-center gap-2 rounded-full border border-sky-100 bg-white px-4 py-1 text-[9px] font-black uppercase tracking-[0.2em] text-sky-600 shadow-sm shadow-sky-100/70">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                        Extra Legroom
                                                    </div>
                                                </div>
                                            @endif

                                            <div
                                                class="flex items-center rounded-2xl px-1.5 py-1 transition-colors hover:bg-white/70">
                                                <div
                                                    class="relative flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-white text-center text-xs font-black text-gray-400 shadow-sm ring-1 ring-gray-100 focus-within:z-50">
                                                    {{ $row['number'] }}
                                                </div>

                                                <div class="flex gap-2">
                                                    @foreach($leftCols as $col)
                                                        @if(isset($row['seats'][$col]))
                                                            @php $seat = $row['seats'][$col]; @endphp
                                                            <button wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                                @disabled($seat['state'] === 'occupied')
                                                                class="seat-btn seat-{{ $seat['state'] }}"
                                                                title="{{ $seat['id'] }} - {{ $currencyCode }}{{ $seat['price'] }}">
                                                                <span>{{ $seat['id'] }}</span>
                                                            </button>
                                                        @else
                                                            <div class="w-[40px]"></div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <div class="flex w-10 flex-shrink-0 items-center justify-center">
                                                    <div
                                                        class="h-12 w-px rounded-full bg-gradient-to-b from-transparent via-gray-200 to-transparent">
                                                    </div>
                                                </div>

                                                <div class="flex gap-2">
                                                    @foreach($rightCols as $col)
                                                        @if(isset($row['seats'][$col]))
                                                            @php $seat = $row['seats'][$col]; @endphp
                                                            <button wire:click="selectSeat('{{ $seat['id'] }}', {{ $seat['price'] }})"
                                                                @disabled($seat['state'] === 'occupied')
                                                                class="seat-btn seat-{{ $seat['state'] }}"
                                                                title="{{ $seat['id'] }} - {{ $currencyCode }}{{ $seat['price'] }}">
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
                <div class="w-full lg:w-80 flex-shrink-0 self-start">
                    <div
                        class="bg-white rounded-xl border border-gray-200 shadow-xl shadow-gray-200/40 overflow-hidden sticky top-6">
                        {{-- Summary Header --}}
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2ab4c0]/10 text-[#2ab4c0]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </span>
                                Price Summary
                            </h3>
                        </div>

                        <div class="p-5 space-y-4">
                            {{-- Line Items --}}
                            @foreach ($summaryItems as $i => $item)
                                <div class="flex items-start justify-between gap-3 group">
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-[11px] font-bold text-gray-500 uppercase tracking-tight leading-tight truncate">{{ $item['label'] }}</span>
                                        @if ($item['removable'])
                                            <button wire:click="removeItem({{ $i }})"
                                                class="text-[10px] font-bold text-red-400 hover:text-red-600 flex items-center gap-0.5 mt-0.5">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                    <span
                                        class="text-sm font-bold text-gray-900">{{ $currencyCode }}{{ $item['amount'] }}</span>
                                </div>
                            @endforeach

                            {{-- Total Section --}}
                            <div class="pt-5 mt-2 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Final
                                        Total</span>
                                    <span
                                        class="text-gray-900 text-2xl font-black">{{ $currencyCode }}{{ $this->total }}</span>
                                </div>
                                <p class="text-[9px] text-gray-400 font-medium">Prices include all taxes and
                                    carrier-imposed fees.</p>
                            </div>

                            {{-- Primary Action Buttons (same line) --}}
                            <div class="flex flex-wrap gap-3 pt-2">
                                <button wire:click="back" wire:loading.attr="disabled" wire:target="back"
                                    class="flex-1 min-w-0 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-[11px]">
                                    <span wire:loading.remove wire:target="back" class="flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Back
                                    </span>
                                    <svg wire:loading wire:target="back" class="animate-spin w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </button>
                                <button wire:click="goToPassengerDetails" wire:loading.attr="disabled"
                                    class="flex-1 min-w-0 py-2 bg-[#2ab4c0] text-white text-[11px] font-black rounded-xl hover:bg-[#2399a3] shadow-lg shadow-[#2ab4c0]/30 transition-all flex items-center justify-center gap-2 group">
                                    <span wire:loading.remove wire:target="goToPassengerDetails"
                                        class="flex items-center gap-2">
                                        Continue
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                    <svg wire:loading wire:target="goToPassengerDetails" class="animate-spin w-4 h-4"
                                        fill="none" viewBox="0 0 24 24" aria-hidden="true">
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
    <style>
        .active-fare-tab {
            color: #2ab4c0 !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes loading {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(400%);
            }
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .seat-available {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #94a3b8;
        }

        .seat-available:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }

        .seat-extra {
            background: #f0f9ff;
            border-color: #bae6fd;
            color: #7dd3fc;
        }

        .seat-extra::after {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 14px;
            height: 3px;
            background: #38bdf8;
            border-radius: 2px;
        }

        .seat-extra:hover {
            background: #e0f2fe;
            border-color: #7dd3fc;
            color: #0284c7;
        }

        .seat-occupied {
            background: #f8fafc;
            border-color: #f1f5f9;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .seat-occupied::before {
            content: '×';
            color: #cbd5e1;
            font-size: 18px;
            font-weight: 300;
            position: absolute;
            line-height: 1;
        }

        .seat-selected {
            background: #2ab4c0;
            border-color: #239ba6;
            color: white !important;
            box-shadow: 0 4px 10px rgba(42, 180, 192, 0.3);
            transform: translateY(-3px);
        }

        .seat-selected:hover {
            color: white !important;
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(42, 180, 192, 0.4);
        }

        .fuselage-container {
            background: #ffffff;
            border: 4px solid #f1f5f9;
            border-radius: 120px 120px 0 0;
            padding: 70px 20px 40px;
            margin: 0 auto;
            position: relative;
            box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.02), 0 10px 40px rgba(0, 0, 0, 0.03);
            border-bottom: none;
        }
    </style>
</div>