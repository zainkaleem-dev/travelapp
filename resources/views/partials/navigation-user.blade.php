<div class="max-w-7xl mx-auto px-3 sm:px-4 flex items-center justify-between">
    <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-xs font-semibold w-full">

        @featureOrAdmin('flights-module')

        <a href="{{ route('flights.search') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('flights.search') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t-lg transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>
            Book Trip
        </a>
        @endfeatureOrAdmin

        <a href="#"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('flights.list') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t-lg transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 00-2.25-2.25H18.75V8.25A2.25 2.25 0 0016.5 6H7.5A2.25 2.25 0 005.25 8.25V11.9h-1.5a2.25 2.25 0 00-2.25 2.25m16.5 0a2.25 2.25 0 01-2.25 2.25H5.25a2.25 2.25 0 01-2.25-2.25m13.5-3.75V11.9m-9 0V8.25" />
            </svg>
            My Trip
        </a>
        <a href="#"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t-lg transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 14.25v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25m-9-4.5v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25m-9-4.5v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25M3.75 20.25h16.5A2.25 2.25 0 0022.5 18V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Dashboard
        </a>
        <a href="#"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('travel.hub') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t-lg transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 6.75V15m6-6.75V15m-10.5 2.25l.75-12 4.5-2.25 4.5 2.25 4.5-2.25 .75 12-4.5 2.25-4.5-2.25-4.5 2.25z" />
            </svg>
            Travel hub
        </a>
        @if(auth()->check() && auth()->user()->hasRole(['Super Admin', 'Organization Admin', 'Branch Admin', 'Partner Admin']))
            <a href="{{ route('root') }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('root') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t-lg transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.744c0 5.052 3.823 9.213 8.712 9.637.33.029.662.043.997.043.335 0 .668-.014 1-.043 4.888-.424 8.712-8.585 8.712-9.637 0-1.332-.239-2.607-.678-3.791A11.956 11.956 0 0112 2.714z" />
                </svg>
                Admin Console
            </a>
        @endif

    </div>

    @if(request()->routeIs('flights.list'))
        <div class="ms-auto ps-4 border-s border-gray-200 flex-shrink-0">
            <button type="button" @click="searchOpen = !searchOpen"
                class="flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/60 hover:bg-[#2ab4c0]/5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/40 -translate-y-[1.5px]"
                title="Modify Search">
                <svg class="w-3.5 h-3.5 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                </svg>
            </button>
        </div>
    @endif
</div>