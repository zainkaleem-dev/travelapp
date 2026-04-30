<div class="flex flex-col md:flex-row w-full min-h-screen bg-slate-100">
    <div class="w-full md:w-72 md:min-h-screen md:sticky md:top-0 md:self-start flex-shrink-0 bg-white border-r border-gray-200 shadow-sm">
        <div class="p-4 md:py-6">

            @php($sidebarUser = auth()->user())
            @php($isGlobalSuperAdmin = false)
            @if($sidebarUser)
                @php(
                    $isGlobalSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
                        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('model_has_roles.model_id', $sidebarUser->id)
                        ->where('model_has_roles.model_type', \App\Models\User::class)
                        ->where('roles.name', 'Super Admin')
                        ->whereNull('model_has_roles.company_id')
                        ->exists()
                )
            @endif
            @php($isOrganizationAdmin = auth()->check() && !$isGlobalSuperAdmin && auth()->user()->hasRole('Organization Admin'))
            @php($sidebarRole = $isGlobalSuperAdmin ? 'Super Admin' : ($sidebarUser?->getRoleNames()?->first() ?? 'User'))
            @php($firstName = trim((string) ($sidebarUser?->first_name ?? '')))
            @php($middleName = trim((string) ($sidebarUser?->middle_name ?? '')))
            @php($lastName = trim((string) ($sidebarUser?->last_name ?? '')))
            @php($firstInitial = $firstName !== '' ? strtoupper(mb_substr($firstName, 0, 1)) . '.' : '')
            @php($middleInitial = $middleName !== '' ? strtoupper(mb_substr($middleName, 0, 1)) . '.' : '')
            @php($displayName = $sidebarUser?->name ?: 'User')
            @if($firstName !== '' && $lastName !== '')
                @if($middleName === '')
                    @php($fullName = trim($firstName . ' ' . $lastName))
                    @if(mb_strlen($fullName) > 17)
                        @php($displayName = trim($firstInitial . ' ' . $lastName))
                    @else
                        @php($displayName = $fullName)
                    @endif
                @else
                    @php($nameWithMiddleInitial = trim($firstName . ' ' . $middleInitial . ' ' . $lastName))
                    @if(mb_strlen($nameWithMiddleInitial) > 17)
                        @php($displayName = trim($firstInitial . ' ' . $middleInitial . ' ' . $lastName))
                    @else
                        @php($displayName = $nameWithMiddleInitial)
                    @endif
                @endif
            @endif

            <div class="mb-4 w-full rounded-lg bg-[#2ab4c0] px-4 py-2.5 text-white shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-semibold truncate">{{ $displayName }}</p>
                    <p class="text-[11px] font-semibold text-white/90 whitespace-nowrap">{{ $sidebarRole }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-1 w-full overflow-y-auto" style="-webkit-overflow-scrolling: touch;">

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('dashboard') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                    Dashboard
                </a>

                @if($isOrganizationAdmin)
                    @featureOrAdmin('companies-module')
                    @can('View Company')
                        <a href="{{ route('companies.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('companies.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            {{ $isGlobalSuperAdmin ? 'Organizations' : 'Partner List' }}
                        </a>
                    @endcan
                    @endfeatureOrAdmin

                    <a href="#"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Reports
                    </a>

                    @if(request()->route('id'))
                    <div class="h-px bg-gray-100 my-1"></div>

                    <div x-data="{ openPartner: false, openCorporate: false, openTmc: false, openCorpNotifications: false, openCorpIntegrations: false, openTmcServices: false, openTmcIntegrations: false }"
                        class="flex flex-col gap-1">
                        <button type="button"
                            class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                            :class="openPartner ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                            @click="openPartner = !openPartner">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 6h9M7.5 10.5h9m-9 4.5h6M6 3h12a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 18 21H6A2.25 2.25 0 0 1 3.75 18.75V5.25A2.25 2.25 0 0 1 6 3z" />
                                </svg>
                                Partner List 
                            </span>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': openPartner }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openPartner" x-cloak class="ml-3 flex flex-col gap-1">
                            <button type="button"
                                class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                :class="openCorporate ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                @click="openCorporate = !openCorporate">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15" />
                                    </svg>
                                    Corporate Configuration
                                </span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': openCorporate }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="openCorporate" x-cloak class="ml-3 flex flex-col gap-1">
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg>
                                    Profile (Logo - Name - Billing Accounts)
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4m10 0H7" /></svg>
                                    Manage TMC (Associate)
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h12M3 17h8" /></svg>
                                    Trip Purpose &amp; Travel Services
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" /></svg>
                                    Travel Policy
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-8 0v2M9 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0z" /></svg>
                                    Employees (Users)
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h7" /></svg>
                                    Grades / Positions
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5" /></svg>
                                    Divisions
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M6 12h12M6 17h8" /></svg>
                                    Departments
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" /></svg>
                                    Approval Flow
                                </a>
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4zM8 10h8M8 14h5" /></svg>
                                    Custom Fields
                                </a>

                                <button type="button"
                                    class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                    :class="openCorpNotifications ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                    @click="openCorpNotifications = !openCorpNotifications">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0h6z" /></svg>
                                        Notifications
                                    </span>
                                    <svg class="w-3 h-3 text-gray-500 transition-transform" :class="{ 'rotate-180': openCorpNotifications }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openCorpNotifications" x-cloak class="ml-3 flex flex-col gap-1">
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z" /></svg>Mail Notifications</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M3 5h18v14H3z" /></svg>Message of the Day</a>
                                </div>

                                <button type="button"
                                    class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                    :class="openCorpIntegrations ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                    @click="openCorpIntegrations = !openCorpIntegrations">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                                        Integrations &amp; API
                                    </span>
                                    <svg class="w-3 h-3 text-gray-500 transition-transform" :class="{ 'rotate-180': openCorpIntegrations }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openCorpIntegrations" x-cloak class="ml-3 flex flex-col gap-1">
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18" /></svg>HR Integration</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 1 0-8 0v4h8z" /></svg>SSO / Authentication</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10M12 7l5 5-5 5" /></svg>Approval Integration</a>
                                </div>

                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12" /></svg>Reports</a>
                            </div>

                            <button type="button"
                                class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                :class="openTmc ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                @click="openTmc = !openTmc">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 5h16v14H4zM8 9h8M8 13h5" />
                                    </svg>
                                    TMC Configuration
                                </span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': openTmc }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="openTmc" x-cloak class="ml-3 flex flex-col gap-1">
                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg>Profile (Logo - Name - Billing Accounts)</a>

                                <button type="button"
                                    class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                    :class="openTmcServices ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                    @click="openTmcServices = !openTmcServices">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h12M3 17h8" /></svg>
                                        Travel Service Configuration
                                    </span>
                                    <svg class="w-3 h-3 text-gray-500 transition-transform" :class="{ 'rotate-180': openTmcServices }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openTmcServices" x-cloak class="ml-3 flex flex-col gap-1">
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" /></svg>Flight</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 0 1 2 2v10H3V8a2 2 0 0 1 2-2z" /></svg>Hotel</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h18M7 13V9a2 2 0 1 1 4 0v4m0 0h6v3H3v-3h8z" /></svg>Car</a>
                                </div>

                                <button type="button"
                                    class="inline-flex items-center justify-between gap-1.5 px-4 py-2.5 w-full rounded-lg text-xs whitespace-nowrap transition-colors"
                                    :class="openTmcIntegrations ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                    @click="openTmcIntegrations = !openTmcIntegrations">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                                        Integrations &amp; API
                                    </span>
                                    <svg class="w-3 h-3 text-gray-500 transition-transform" :class="{ 'rotate-180': openTmcIntegrations }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openTmcIntegrations" x-cloak class="ml-3 flex flex-col gap-1">
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg>Provider Accounts</a>
                                    <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4zM8 9h8M8 13h5" /></svg>Back Office System</a>
                                </div>

                                <a href="#" class="admin-menu-item inline-flex items-center gap-1.5"><svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.2 0-4 .9-4 2s1.8 2 4 2 4 .9 4 2-1.8 2-4 2m0-10V6m0 12v-2" /></svg>Manage Service Fees &amp; Markup</a>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 my-1"></div>
                    @endif
                @else
                    @featureOrAdmin('companies-module')
                    @can('View Company')
                        <a href="{{ route('companies.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('companies.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            {{ $isGlobalSuperAdmin ? 'Organizations' : 'Partner List' }}
                        </a>
                    @endcan
                    @endfeatureOrAdmin
                    <a href="#"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Reports
                    </a>
                @endif

                @if(!$isOrganizationAdmin)
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 11v-1a2 2 0 114 0v1m-4 0h4v3a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3z" />
                    </svg>
                    Permissions
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    Subscriptions
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg text-gray-600 hover:bg-gray-50 text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    System Settings
                </a>
                <a href="{{ route('admin.countries-and-cities') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('admin.countries-and-cities*', 'admin.countries.*', 'admin.cities.*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A11.954 11.954 0 0 1 12 16.5c-2.998 0-5.74-1.1-7.843-2.918m0 0A8.959 8.959 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                    </svg>
                    Countries & Cities List
                </a>
                <a href="{{ route('admin.airports') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('admin.airports*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                    </svg>
                    Airport List
                </a>
                <a href="{{ route('admin.trip-purpose') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('admin.trip-purpose*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 0 0-2.25-2.25H4.875c-.621 0-1.125.504-1.125 2.25m16.5 0V9.45c0-.621-.504-1.125-1.125-1.125H16.5M3.75 14.15V9.45c0-.621.504-1.125 1.125-1.125H7.5m9 0V3.75A1.125 1.125 0 0 0 15.375 2.625h-6.75A1.125 1.125 0 0 0 7.5 3.75v4.575m9 0H7.5" />
                    </svg>
                    Trip Purpose
                </a>
                <a href="{{ route('admin.integrations-api') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('admin.integrations-api*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    Integrations & API
                </a>
                <a href="{{ route('admin.audit-logs') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 w-full rounded-lg {{ request()->routeIs('admin.audit-logs*') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:bg-gray-50' }} text-xs whitespace-nowrap transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.081.263.283.626.469 1.025.469h1.402c.399 0 .762-.186 1.025-.469.263-.283.425-.666.425-1.081 0-.231-.035-.454-.1-.664m-5.801 0A48.221 48.221 0 0 1 12 2.25c1.652 0 3.26.154 4.821.449m-9.351 0a2.25 2.25 0 0 0-1.921 2.221v14.07h17.25V4.67a48.221 48.221 0 0 0-1.123-.08" />
                    </svg>
                    Audit Logs
                </a>
                @endif


                <!-- @featureOrAdmin('branches-module')
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
                @endfeatureOrAdmin -->

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

                <!-- @featureOrAdmin('roles-permissions-module')
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
                @endfeatureOrAdmin -->

                <!-- <div class="h-px bg-gray-100 my-1"></div>

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
                @endfeatureOrAdmin -->



            </div>
        </div>
    </div>

    <div class="flex-1 min-w-0 min-h-screen p-3 sm:p-4 md:p-6">
        @include('partials.page-flow')
        {{ $slot }}
    </div>
</div>
