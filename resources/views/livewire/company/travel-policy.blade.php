<div class="px-1 py-1 w-full flex flex-col gap-3" x-data="{ filtersOpen: true }">
    @if (session('status'))
        <div class="mb-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] font-bold text-green-800 uppercase shadow-sm animate-fade-in">
            {{ session('status') }}
        </div>
    @endif

    {{-- Top Container: Title and Filters Toggle --}}
    <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200 rounded-t-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Travel Policy</h1>
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
                    <a href="{{ route('admin.travel-policy.create', ['companyId' => $companyId, 'returnUrl' => request()->getRequestUri()]) }}" 
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm uppercase tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Policy
                    </a>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="px-6 pt-2 border-b border-gray-200 bg-white">
            <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-[11px] font-semibold w-full">
                @foreach(['flight', 'hotel', 'car', 'concierge', 'general'] as $tab)
                    @php
                        $icon = match($tab) {
                            'flight' => '<svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>',
                            'hotel' => '<svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>',
                            'car' => '<svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0 1.5 1.5 0 0 1 3 0ZM18.75 18.75a1.5 1.5 0 0 1-3 0 1.5 1.5 0 0 1 3 0ZM3.75 15h16.5M4.5 9h15M3.75 15l.369-2.957A3.75 3.75 0 0 1 7.828 9h8.344a3.75 3.75 0 0 1 3.709 3.043l.369 2.957M3.75 15v3.375c0 .621.504 1.125 1.125 1.125h.375M20.25 15v3.375c0 .621-.504 1.125-1.125 1.125h-.375" /></svg>',
                            'concierge' => '<svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>',
                            'general' => '<svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
                            default => ''
                        };
                    @endphp
                    <button wire:click="$set('activeTab', '{{ $tab }}')" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg transition-all duration-200 whitespace-nowrap uppercase tracking-wider
                        {{ $activeTab === $tab ? 'bg-[#2ab4c0] text-white font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        {!! $icon !!}
                        {{ $tab }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Filters Section --}}
        <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="px-6 py-3 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex flex-col sm:flex-row items-end gap-4 animate-fade-in">
                <div class="flex-1 w-full">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Search {{ ucfirst($activeTab) }} Policies</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" class="input-field pl-10" placeholder="Search by name or description...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Container: Travel Policy Content Area --}}
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            <div class="animate-fade-in">
                @livewire('company.company-travel-policy-management', [
                    'companyId' => $companyId,
                    'policyType' => $activeTab,
                    'search' => $search
                ], key('travel-policy-'.$companyId.'-'.now()))
            </div>
        </div>
    </div>
</div>


