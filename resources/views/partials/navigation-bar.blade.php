{{-- resources/views/partials/navigation-bar.blade.php --}}
@php 
        $user = auth()->user();

    // Authorization Flags
    $isSuperAdmin = $user && $user->hasRole('Super Admin');
    $isCompanyAdmin = $user && $user->hasRole('Company Admin');

    // Area Flags
    $isSuperAdminArea = $isSuperAdmin && request()->is('super-admin*');
    $isCompanyAdminArea = $isCompanyAdmin && request()->is('company*');

    // Navigation View Logic
    $isAdminView = $isSuperAdminArea || $isCompanyAdminArea;

    // Orientation
    $isVertical = $vertical ?? false;
@endphp

<div class="{{ $isVertical ? 'flex flex-col gap-1 w-full' : 'max-w-[960px] mx-auto flex items-center overflow-x-auto no-scrollbar gap-0 min-w-0' }}" style="{{ $isVertical ? '' : '-webkit-overflow-scrolling: touch;' }}">
    @if ($isSuperAdminArea)
        {{-- Super Admin Navigation --}}
        <a href="{{ route('superadmin.companies.index') }}"
            class="inline-flex items-center gap-1.5 {{ $isVertical ? 'px-4 py-2.5 w-full rounded-lg' : 'px-3 py-2 sm:px-4 flex-shrink-0' }} {{ request()->routeIs('superadmin.companies.*') && !request()->routeIs('superadmin.companies.features') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} {{ $isVertical ? '' : 'rounded-t' }} text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            Companies
        </a>
        <a href="{{ route('superadmin.branches') }}"
            class="inline-flex items-center gap-1.5 {{ $isVertical ? 'px-4 py-2.5 w-full rounded-lg' : 'px-3 py-2 sm:px-4 flex-shrink-0' }} {{ request()->routeIs('superadmin.branches*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} {{ $isVertical ? '' : 'rounded-t' }} text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
            </svg>
            Branches
        </a>
        <a href="{{ route('superadmin.users') }}"
            class="inline-flex items-center gap-1.5 {{ $isVertical ? 'px-4 py-2.5 w-full rounded-lg' : 'px-3 py-2 sm:px-4 flex-shrink-0' }} {{ request()->routeIs('superadmin.users*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} {{ $isVertical ? '' : 'rounded-t' }} text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            Users
        </a>
        <a href="{{ route('superadmin.roles.index') }}"
            class="inline-flex items-center gap-1.5 {{ $isVertical ? 'px-4 py-2.5 w-full rounded-lg' : 'px-3 py-2 sm:px-4 flex-shrink-0' }} {{ request()->routeIs('superadmin.roles.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} {{ $isVertical ? '' : 'rounded-t' }} text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            Roles & Permissions
        </a>
        <a href="{{ route('superadmin.features') }}"
            class="inline-flex items-center gap-1.5 {{ $isVertical ? 'px-4 py-2.5 w-full rounded-lg' : 'px-3 py-2 sm:px-4 flex-shrink-0' }} {{ request()->routeIs('superadmin.features') || request()->routeIs('superadmin.companies.features') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} {{ $isVertical ? '' : 'rounded-t' }} text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Feature Management
        </a>
    @elseif ($isCompanyAdminArea)
        {{-- Company Admin Navigation --}}
        <a href="{{ route('company.companies.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('company.companies.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            Company
        </a>
        <a href="{{ route('company.roles.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('company.roles.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            Roles & Permissions
        </a>
    @else
    {{-- Standard User Navigation (Flight Search, etc.) --}}
    @feature('flights-module')
    <a href="{{ route('flights.search') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ (request()->is('flights-search') || request()->is('flights-list') || request()->is('additional-services') || request()->is('passenger-details')) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path
                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
        </svg>
        Flight
    </a>
    @endfeature
    @feature('hotels-module')
    <a href="{{ route('hotels') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('hotels') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        Hotel
    </a>
    @endfeature
    @feature('cars-module')
    <a href="{{ route('cars') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('cars') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h9M9 6.75h6m-7.5 0V5.25A2.25 2.25 0 0111.25 3h.75a2.25 2.25 0 012.25 2.25v1.5H18a2.25 2.25 0 012.25 2.25v7.5A2.25 2.25 0 0118 16.5h-.75M9 6.75H6.75A2.25 2.25 0 004.5 9v7.5A2.25 2.25 0 006.75 18.75H9" />
        </svg>
        Car
    </a>
    @endfeature
    @feature('concierge-module')
    <a href="{{ route('concierge') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('concierge') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Concierge
    </a>
    @endfeature
    @feature('travel-hub-module')
    <a href="{{ route('travel.hub') }}"
        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('travel-hub') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
        </svg>
        Hub
    </a>
    @endfeature
    @endif

    {{-- Right-side search toggle (frontend only) --}}
    @unless ($isAdminView)
        <button type="button" @click="searchOpen = !searchOpen"
            class="ml-auto my-1 flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/60 shadow-sm flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
            </svg>
        </button>
    @endunless
</div>