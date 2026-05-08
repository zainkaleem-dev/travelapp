<div class="px-1 py-1 w-full flex flex-col gap-3" x-data="{ filtersOpen: true }">
    @if (session('status'))
        <div class="mb-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] font-bold text-green-800 uppercase shadow-sm animate-fade-in">
            {{ session('status') }}
        </div>
    @endif

    {{-- Top Container: Title, Nav, and Filters --}}
    <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200 rounded-t-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">System Settings</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="filtersOpen = !filtersOpen"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 transition-colors"
                        :class="filtersOpen ? 'bg-[#2ab4c0]/10 text-[#2ab4c0] border-[#2ab4c0]/30' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        title="Toggle Filters">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    @if($activeTab === 'travel-policy')
                        <a href="{{ route('admin.travel-policy.create') }}" 
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm uppercase tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Policy
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="px-6 pt-2 border-b border-gray-200 bg-white">
            <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-[11px] font-semibold w-full">
                <button wire:click="$set('activeTab', 'endpoints')" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t-lg transition-colors whitespace-nowrap
                    {{ $activeTab === 'endpoints' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                    API Endpoints
                </button>
                <button wire:click="$set('activeTab', 'travel-policy')" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t-lg transition-colors whitespace-nowrap
                    {{ $activeTab === 'travel-policy' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" />
                    </svg>
                    Travel Policy
                </button>
            </div>
        </div>

        {{-- Filters Section (Tab Dependent) --}}
        <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="px-6 py-3 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            @if($activeTab === 'endpoints')
                <div class="flex flex-col sm:flex-row items-end gap-4 animate-fade-in">
                    <div class="w-full sm:w-64">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Select Company</label>
                        <div class="relative" wire:ignore.self x-data="{ open: false, selected: @js($companyId ?: ''), companies: @js($companies->pluck('name', 'id')), get selectedName() { return this.selected && this.companies[this.selected] ? this.companies[this.selected] : 'All Companies'; } }" @keydown.escape.window="open = false" @click.outside="open = false">
                            <button type="button" class="admin-menu-btn" @click.stop="open = !open">
                                <span x-text="selectedName"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel max-h-64 overflow-y-auto">
                                <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click.stop="selected = ''; open = false; $wire.set('companyId', '')">All Companies</button>
                                @foreach($companies as $company)
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected == '{{ $company->id }}' }" @click.stop="selected = '{{ $company->id }}'; open = false; $wire.set('companyId', '{{ $company->id }}')">{{ $company->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Search Endpoints</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" class="input-field pl-10" placeholder="Search by name, link or description...">
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'travel-policy')
                <div class="flex flex-col sm:flex-row items-end gap-4 animate-fade-in">
                    <div class="w-full sm:w-64">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Select Company</label>
                        <div class="relative" wire:ignore.self x-data="{ open: false, selected: @js($companyId ?: ''), companies: @js($companies->pluck('name', 'id')), get selectedName() { return this.selected && this.companies[this.selected] ? this.companies[this.selected] : 'All Companies'; } }" @keydown.escape.window="open = false" @click.outside="open = false">
                            <button type="button" class="admin-menu-btn" @click.stop="open = !open">
                                <span x-text="selectedName"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel max-h-64 overflow-y-auto">
                                <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click.stop="selected = ''; open = false; $wire.set('companyId', '')">All Companies</button>
                                @foreach($companies as $company)
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected == '{{ $company->id }}' }" @click.stop="selected = '{{ $company->id }}'; open = false; $wire.set('companyId', '{{ $company->id }}')">{{ $company->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="w-full sm:w-48">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Policy Type</label>
                        <div class="relative" x-data="{ open: false, selected: @js($policyType ?: '') }" @keydown.escape.window="open = false" @click.outside="open = false">
                            <button type="button" class="admin-menu-btn capitalize" @click.stop="open = !open">
                                <span x-text="selected === '' ? 'All Types' : selected"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click.stop="selected = ''; open = false; $wire.set('policyType', '')">All Types</button>
                                @foreach(['flight', 'car', 'hotel', 'concierge', 'general'] as $type)
                                    <button type="button" class="admin-menu-item capitalize" :class="{ 'is-active': selected === '{{ $type }}' }" @click.stop="selected = '{{ $type }}'; open = false; $wire.set('policyType', '{{ $type }}')">{{ $type }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Search Policies</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" class="input-field pl-10" placeholder="Search by name or description...">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom Container: Table Content --}}
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            @if($activeTab === 'endpoints')
                <div class="animate-fade-in">
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr class="bg-[#2ab4c0] border-b-2 border-gray-200">
                                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-ss-lg">API Endpoint</th>
                                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">API Link</th>
                                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Description</th>
                                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg text-right">Verification</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($endpoints as $endpoint)
                                    <tr class="group hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-[11px] font-bold text-gray-900 uppercase">{{ $endpoint->endpoint_name }}</span>
                                                <span class="text-[10px] text-[#2ab4c0] font-bold uppercase tracking-tight">{{ $endpoint->company->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[11px] font-medium text-gray-600">{{ $endpoint->endpoint_link }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[11px] text-gray-500">{{ $endpoint->description }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" wire:click="toggleVerified({{ $endpoint->id }})"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                title="{{ $endpoint->is_verified ? 'Unverify' : 'Verify' }}">
                                                @if($endpoint->is_verified)
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14h4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-[11px] text-gray-500">No endpoints found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($endpoints->hasPages())
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                {{ $endpoints->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($activeTab === 'travel-policy')
                <div class="animate-fade-in">
                    @livewire('system-settings.travel-policy-management', [
                        'companyId' => $companyId,
                        'policyType' => $policyType,
                        'search' => $search
                    ], key('travel-policy-'.now()))
                </div>
            @endif
        </div>
    </div>
</div>
