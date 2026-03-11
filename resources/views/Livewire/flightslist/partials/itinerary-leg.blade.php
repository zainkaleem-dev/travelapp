{{-- Single itinerary leg for vertical layout — same data as horizontal row --}}
<div class="flex flex-col gap-3 flex-1">
    {{-- Airline --}}
    <div class="flex items-center gap-2">
        <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-100 p-1 shadow-sm flex-shrink-0">
            <img src="https://www.gstatic.com/flights/airline_logos/70px/{{ $flight['airlineCode'] }}.png"
                 alt="{{ $flight['airline'] }}"
                 onerror="this.src='https://pics.avs.io/128/128/{{ $flight['airlineCode'] }}.png'"
                 class="w-full h-full object-contain">
        </div>
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter leading-none">{{ $flight['airline'] }}</span>
    </div>

    {{-- Time & Route --}}
    <div class="flex flex-col gap-0.5">
        <div class="flex items-center gap-2 text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">
            <span>{{ $itin['dep'] }}</span>
            <span class="text-gray-300 font-light">–</span>
            <span>{{ $itin['arr'] }}</span>
            @if(isset($itin['daysNext']) && $itin['daysNext'] > 0)
                <sup class="text-[#2ab4c0] text-[10px] font-bold">+{{ $itin['daysNext'] }}</sup>
            @endif
        </div>
        <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest flex-wrap">
            <span title="{{ $itin['depCity'] ?? '' }}" class="cursor-help border-b border-dotted border-gray-300">{{ $itin['depAirport'] }}</span>
            <span class="text-gray-300 font-normal">–</span>
            <span title="{{ $itin['arrCity'] ?? '' }}" class="cursor-help border-b border-dotted border-gray-300">{{ $itin['arrAirport'] }}</span>
            <span class="mx-1 text-gray-300">|</span>
            <span class="text-gray-500">{{ $itin['flightNumber'] }}</span>
            <span class="mx-1 text-gray-300">|</span>
            <span class="text-[10px] text-gray-400 font-medium">{{ $itin['aircraft'] ?? '' }}</span>
        </div>
    </div>

    {{-- Duration & Stops --}}
    <div class="py-1 border-y border-gray-50">
        <div class="text-sm font-black text-gray-800">{{ $itin['duration'] }}</div>
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">{{ $itin['stops'] }}</div>
        @if(isset($itin['technicalStops']) && $itin['technicalStops'] > 0)
            <div class="mt-1 flex items-center gap-1 text-[9px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full ring-1 ring-red-100 uppercase tracking-tighter w-fit">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $itin['technicalStops'] }} Tech Stop{{ $itin['technicalStops'] > 1 ? 's' : '' }}
            </div>
        @endif
    </div>

    {{-- Icons & Amenities tooltip + Seats left --}}
    <div class="flex flex-col items-start gap-1.5 group relative">
        <div class="flex gap-4 text-gray-400">
            <div class="cursor-pointer">
                <div class="flex gap-1.5 group-hover:text-[#2ab4c0] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                @php $allFlightAmenities = collect($flight['itineraries'] ?? [])->pluck('amenities')->flatten()->unique()->values(); @endphp
                <div class="absolute top-full left-0 mt-2 hidden group-hover:block w-52 bg-white border border-gray-100 text-gray-600 shadow-2xl rounded-xl z-[100] overflow-hidden ring-1 ring-black/5">
                    <div class="p-3 bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase tracking-widest">Flight Amenities</div>
                    <div class="p-3 space-y-2">
                        @foreach($allFlightAmenities as $am)
                            <div class="flex items-center gap-2.5 text-[11px] font-medium">
                                <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                {{ $am }}
                            </div>
                        @endforeach
                        <div class="flex items-center gap-2.5 text-[11px] font-medium">
                            <svg class="w-3.5 h-3.5 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Baggage: {{ $itin['baggage'] ?? 'Included' }}
                        </div>
                    </div>
                    <div class="absolute bottom-full left-4 -mb-1 border-4 border-transparent border-b-white"></div>
                </div>
            </div>
        </div>
        @if(isset($flight['seats']) && $flight['seats'] > 0)
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-[11px] font-black text-orange-500 uppercase tracking-tighter">{{ $flight['seats'] }} Seats Left</span>
            </div>
        @endif
    </div>
</div>
