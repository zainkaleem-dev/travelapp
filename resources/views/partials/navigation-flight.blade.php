{{-- ── Trip Type Bar (flight search only, read-only) ── --}}
<div class="max-w-[960px] mx-auto">
    <div class="flex items-center gap-3 bg-white border border-b border-gray-200 rounded-t-xl px-4 py-2.5">
        <div
            class="inline-flex max-w-full items-center gap-2 rounded-xl border border-[#2ab4c0]/45 bg-[#eaf9fb] px-3 py-1.5 shadow-[0_2px_10px_rgba(42,180,192,0.18)] ring-1 ring-[#2ab4c0]/15">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#4b5563]">Trip purpose</span>
            <span class="text-xs font-semibold text-[#1f9aa6]">sasa</span>
        </div>
    </div>
</div>
<div
    class="max-w-[960px] mx-auto bg-white rounded-b-xl rounded-t-none border-t-0  border border-gray-200 shadow-sm overflow-visible px-2 sm:px-3">

    <a href="{{ route('flights.search') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ (request()->is('flights-search') || request()->is('flights-list') || request()->is('additional-services') || request()->is('passenger-details')) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path
                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
        </svg>
        Flight
    </a>

    <a href="#"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('hotels') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        Hotel
    </a>

    <a href="#"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('cars') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h9M9 6.75h6m-7.5 0V5.25A2.25 2.25 0 0111.25 3h.75a2.25 2.25 0 012.25 2.25v1.5H18a2.25 2.25 0 012.25 2.25v7.5A2.25 2.25 0 0118 16.5h-.75M9 6.75H6.75A2.25 2.25 0 004.5 9v7.5A2.25 2.25 0 006.75 18.75H9" />
        </svg>
        Car
    </a>

    <a href="#"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('concierge') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Concierge
    </a>

</div>


<div class="max-w-[960px] mx-auto h-px bg-gray-100"></div>


{{-- <div x-cloak x-show="searchOpen" x-transition.opacity x-transition.duration.200ms
    class="{{ request()->routeIs('flights.list') ? 'w-full mt-4 mb-4 px-3 sm:px-4' : 'max-w-[960px] mx-auto mt-4 mb-4' }}">
    @livewire('quick-search')
</div> --}}