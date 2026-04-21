<div class="flex flex-col md:flex-row w-full min-h-screen bg-slate-100">
    <div class="w-full md:w-72 md:min-h-screen md:sticky md:top-0 md:self-start flex-shrink-0 bg-white border-r border-gray-200 shadow-sm">
        <div class="p-4 md:py-6">
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 px-4">Management
            </div>

            <div class="flex flex-col gap-1 w-full overflow-y-auto" style="-webkit-overflow-scrolling: touch;">

                @featureOrAdmin('companies-module')
                @can('View Company')
                    <a href="{{ route('companies.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('companies.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                        Organizations
                    </a>
                @endcan
                @endfeatureOrAdmin

                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Dashboard
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Reports
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Permissions
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Subscriptions
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    System Settings
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Countries & Cities List
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Airport List
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Trip Purpose
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Integrations & API
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Audit Logs
                </a>


                @featureOrAdmin('branches-module')
                @can('View Branch')
                    <a href="{{ route('branches.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('branches.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                        Branches
                    </a>
                @endcan
                @endfeatureOrAdmin

                @featureOrAdmin('users-module')
                @can('View Users')
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('users.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        Users
                    </a>
                @endcan
                @endfeatureOrAdmin

                @featureOrAdmin('roles-permissions-module')
                @can('Manage Roles and Permissions')
                    <a href="{{ route('roles.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('roles.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                        Roles & Permissions
                    </a>
                @endcan
                @endfeatureOrAdmin

                @featureOrAdmin('feature-management-module')
                @can('Manage Features')
                    <a href="{{ route('features') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('features*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Feature Management
                    </a>
                @endcan
                @endfeatureOrAdmin

                <div class="h-px bg-gray-100 my-1"></div>

                @featureOrAdmin('flights-module')
                <a href="{{ route('flights.search') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('flights.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path
                            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                    </svg>
                    Book Trip
                </a>
                @endfeatureOrAdmin



            </div>
        </div>
    </div>

    <div class="flex-1 min-w-0 min-h-screen p-3 sm:p-4 md:p-6">
        {{ $slot }}
    </div>
</div>