<div class="px-1 py-1 w-full">
    <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">System Settings</h1>
                        <p class="text-[11px] font-bold text-gray-500 uppercase mt-1">Manage API Endpoints</p>
                    </div>
                </div>

                {{-- Filters integrated into the first container --}}
                <div class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="w-full sm:w-64">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Select Company</label>
                        <div class="relative" 
                            wire:ignore.self
                            x-data="{ 
                                open: false, 
                                selected: @js($companyId ?: ''),
                                companies: @js($companies->pluck('name', 'id')),
                                get selectedName() {
                                    return this.selected && this.companies[this.selected] ? this.companies[this.selected] : 'All Companies';
                                }
                            }"
                            @keydown.escape.window="open = false" @click.outside="open = false">
                            <button type="button" class="admin-menu-btn" @click.stop="open = !open">
                                <span x-text="selectedName"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel max-h-64 overflow-y-auto">
                                <button type="button" class="admin-menu-item"
                                    :class="{ 'is-active': selected === '' }"
                                    @click.stop="selected = ''; open = false; $wire.set('companyId', '')">All Companies</button>
                                @foreach($companies as $company)
                                    <button type="button" class="admin-menu-item" data-id="{{ $company->id }}"
                                        :class="{ 'is-active': selected == '{{ $company->id }}' }"
                                        @click.stop="selected = '{{ $company->id }}'; open = false; $wire.set('companyId', '{{ $company->id }}')">{{ $company->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 block">Search Endpoints</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" class="input-field pl-10" placeholder="Search by name, link or description...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-[#2ab4c0] border-b-2 border-gray-200">
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-ss-lg">API Endpoint</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">API Link</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Description</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg">Verified</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($endpoints as $endpoint)
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-gray-900 uppercase">{{ $endpoint->endpoint_name }}</span>
                                <span class="text-[10px] text-[#2ab4c0] font-bold uppercase tracking-tight">{{ $endpoint->company->name }} ({{ $endpoint->company->company_type }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-medium text-gray-600">{{ $endpoint->endpoint_link }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] text-gray-500">{{ $endpoint->description }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($endpoint->is_verified)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 uppercase">Verified</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 uppercase">Pending</span>
                            @endif
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
