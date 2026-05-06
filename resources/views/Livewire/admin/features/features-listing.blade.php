<div>
    <div class="px-1 py-1 w-full">
        @if($isCompanyRoute)
            <div class="mb-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $activeCompany->name ?? 'Feature Management' }}</h1>
                </div>
                @include('partials.navigation-company', ['companyId' => $selectedCompanyId, 'activeTab' => 'feature-management'])
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 bg-transparent min-h-[calc(100vh-200px)]">

            {{-- ── Sidebar: Company List ─────────────────────────────── --}}
            @if(!$isCompanyContext)
            <div class="w-full lg:w-60 flex-shrink-0">
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-gray-100 bg-[#f9faf6]">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                            <h2 class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">Feature Management</h2>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="input-field block w-full pl-10 pr-3"
                                placeholder="Search companies...">
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar" style="max-height: 600px;">
                        @forelse($sidebarCompanies as $company)
                            <button wire:click="selectCompany({{ $company->id }})"
                                class="w-full text-left p-2 rounded-lg border transition-all duration-200 group
                                    {{ $selectedCompanyId === $company->id
                                        ? 'bg-white border-[#2ab4c0] shadow-md ring-1 ring-[#2ab4c0]/10'
                                        : 'bg-transparent border-transparent hover:bg-gray-50 hover:border-gray-200' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black transition-transform group-hover:scale-110
                                        {{ $selectedCompanyId === $company->id ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-100 text-gray-400' }}">
                                        {{ substr($company->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="text-[11px] font-bold text-gray-900 truncate">{{ $company->name }}</h3>
                                            @if($companyStats[$company->id]['is_any_active'])
                                                <div class="w-1 h-1 rounded-full bg-green-500 flex-shrink-0"></div>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-medium tracking-tight">
                                            {{ $companyStats[$company->id]['active'] }}/{{ $companyStats[$company->id]['total'] }} modules active
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="py-12 text-center text-gray-400">
                                <p class="text-sm">No companies found.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
            @endif

            {{-- ── Main Content ──────────────────────────────────────── --}}
            <div class="flex-1 min-w-0">
                @if($activeCompany)
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-visible flex flex-col">

                        {{-- Header --}}
                        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-br from-white to-[#fafbfc]">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-lg bg-[#f2feff] border border-[#2ab4c0]/20 flex items-center justify-center text-xl font-black text-[#2ab4c0]">
                                    {{ substr($activeCompany->name, 0, 1) }}
                                </div>
                                <div>
                                    <h2 class="text-[21px] font-black text-gray-900">{{ $activeCompany->name }}</h2>
                                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $activeCompany->slug }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2 min-w-[160px]">
                                <div class="flex gap-2">
                                    <span class="px-3 py-1 bg-green-50 text-green-700 text-[11px] font-black uppercase rounded-full border border-green-100">{{ $onCount }} on</span>
                                    <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[11px] font-black uppercase rounded-full border border-gray-100">{{ $offCount }} off</span>
                                </div>
                                <div class="w-full">
                                    <div class="flex justify-between text-[10px] font-bold text-gray-400 mb-1">
                                        <span>MODULES ENABLED</span>
                                        <span class="text-[#2ab4c0]">{{ $activePercentage }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] transition-all duration-700" style="width: {{ $activePercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Feature Body --}}
                        <div class="p-6 bg-[#fdfdfc]/50">

                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Available Features</span>
                                </div>
                                
                                <div class="relative flex items-center justify-center"
                                    x-data="{ open: false, selected: @js((string) ($perPage ?? 10)) }"
                                    @keydown.escape.window="open = false" @click.outside="open = false">
                                    <button type="button" class="admin-menu-btn justify-center" @click="open = !open">
                                        <span x-text="selected"></span>
                                        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': selected == '5' }"
                                            @click="selected = '5'; open = false; $wire.set('perPage', 5)">5</button>
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': selected == '10' }"
                                            @click="selected = '10'; open = false; $wire.set('perPage', 10)">10</button>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm bg-white mb-6">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#f9faf6] border-b border-gray-100">
                                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest w-1/2">Module Name</th>
                                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center w-1/4">Status</th>
                                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-right w-1/4">Configuration</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($paginatedFeatures as $key => $feature)
                                            <tr class="group hover:bg-gray-50/50 transition-colors"
                                                @if(($feature['type'] ?? 'toggle') === 'quantity') x-data="{ qty: {{ is_numeric($activeFeatures[$key] ?? 0) ? (int)($activeFeatures[$key] ?? 0) : 0 }} }" @endif>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0
                                                            {{ (($feature['type'] ?? 'toggle') === 'toggle' ? ($activeFeatures[$key] ?? false) : true) ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-50 text-gray-400' }}">
                                                            @if($feature['icon'] === 'plane')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                                                            @elseif($feature['icon'] === 'building')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                            @elseif($feature['icon'] === 'car')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zm-4-7H5l2-5h10l2 5z"/></svg>
                                                            @elseif($feature['icon'] === 'bell')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                                            @elseif($feature['icon'] === 'office-building')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                            @elseif($feature['icon'] === 'branch')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h5l2 4h6l2-4h3M3 7l2 8h14l2-8M3 7h18"/></svg>
                                                            @elseif($feature['icon'] === 'users')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                            @elseif($feature['icon'] === 'shield')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                                            @elseif($feature['icon'] === 'hash')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                                            @else
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h4 class="text-[11px] font-bold {{ (($feature['type'] ?? 'toggle') === 'toggle' ? ($activeFeatures[$key] ?? false) : true) ? 'text-gray-900' : 'text-gray-500' }} transition-colors leading-tight">{{ $feature['label'] }}</h4>
                                                            <p class="text-[10px] {{ (($feature['type'] ?? 'toggle') === 'toggle' ? ($activeFeatures[$key] ?? false) : true) ? 'text-[#2ab4c0]' : 'text-gray-400' }} font-bold tracking-widest mt-0.5 transition-colors">
                                                                {{ $feature['description'] ?? '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if(($feature['type'] ?? 'toggle') === 'toggle')
                                                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                                            <input type="checkbox"
                                                                    wire:click="toggleFeature({{ $activeCompany->id }}, '{{ $key }}')"
                                                                    {{ ($activeFeatures[$key] ?? false) ? 'checked' : '' }}
                                                                    class="sr-only peer">
                                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2ab4c0] shadow-inner transition-colors duration-200"></div>
                                                        </label>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if(($feature['type'] ?? 'toggle') === 'quantity')
                                                        <div class="flex items-center justify-end gap-3">
                                                            <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-1 border border-gray-100">
                                                                <button type="button" @click="qty = Math.max(0, qty - 1)"
                                                                    class="w-7 h-7 rounded bg-white border border-gray-200 text-gray-500 hover:text-[#2ab4c0] flex items-center justify-center font-black transition-colors text-[11px]">−</button>
                                                                <input type="number" x-model.number="qty" min="0"
                                                                    class="input-field w-12 text-center text-[11px] font-black text-gray-800 !bg-transparent !border-0 p-0 focus:ring-0">
                                                                <button type="button" @click="qty = qty + 1"
                                                                    class="w-7 h-7 rounded bg-white border border-gray-200 text-gray-500 hover:text-[#2ab4c0] flex items-center justify-center font-black transition-colors text-[11px]">+</button>
                                                            </div>
                                                            <button type="button"
                                                                @click="$wire.updateQuantity({{ $activeCompany->id }}, '{{ $key }}', qty)"
                                                                class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest text-[#2ab4c0] bg-white hover:bg-[#2ab4c0] hover:text-white border border-[#2ab4c0]/40 rounded-lg transition-all shadow-sm active:scale-95">
                                                                Update
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @if(empty($paginatedFeatures))
                                            <tr>
                                                <td colspan="3" class="px-6 py-10 text-center text-[11px] text-gray-500">No features available.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Controls (Under Main Table) -->
                            @if (isset($paginationMeta) && ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0))
                                <div class="pt-2 border-t border-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="text-[11px] text-gray-500 font-medium">
                                            Showing <span class="font-bold text-gray-900">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                            <span class="font-bold text-gray-900">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                            <span class="font-bold text-gray-900">{{ $paginationMeta['total'] }}</span> features
                                        </div>
                                        @if ($paginationMeta['last_page'] > 1)
                                            <div class="flex items-center gap-1.5">
                                                @if ($paginationMeta['current_page'] > 1)
                                                    <button wire:click="gotoPage({{ $paginationMeta['current_page'] - 1 }})"
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                                        Previous
                                                    </button>
                                                @endif

                                                @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                                    <button wire:click="gotoPage({{ $page }})" 
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[11px] font-bold transition-all
                                                        {{ $page === $paginationMeta['current_page']
                                                            ? 'bg-[#2ab4c0] text-white shadow-md'
                                                            : 'border border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                                                        {{ $page }}
                                                    </button>
                                                @endfor

                                                @if ($paginationMeta['has_more'])
                                                    <button wire:click="gotoPage({{ $paginationMeta['current_page'] + 1 }})"
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                                        Next
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- Footer --}}
                        <!-- <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-3xl flex items-center justify-between">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Session: {{ substr(session()->getId(), 0, 8) }}</p>
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Live Sync Active</span>
                            </div>
                        </div> -->
                    </div>

                @else
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm flex items-center justify-center p-20 h-full">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-5 text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Select a Company</h3>
                            <p class="text-sm text-gray-400 mt-1">Pick a company from the left to manage its feature set.</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
