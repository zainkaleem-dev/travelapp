<div class="w-full" x-data="{ filtersOpen: true }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Airport List</h1>
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
                        <a href="{{ route('admin.airports.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                            Add Airport
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filters Section --}}
            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-3 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="w-full sm:w-44">
                            <div class="relative" x-data="{ open: false, selected: @js($cityFilter ?? '') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="open = !open">
                                    <span x-text="selected === '' ? 'All Cities' : ($el.querySelector('[data-id=\'' + selected + '\']')?.textContent || 'All Cities')"></span>
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
                                        @click="selected = ''; open = false; $wire.set('cityFilter', '')">All Cities</button>
                                    @foreach($cities as $city)
                                        <button type="button" class="admin-menu-item" data-id="{{ $city->id }}"
                                            :class="{ 'is-active': selected == '{{ $city->id }}' }"
                                            @click="selected = '{{ $city->id }}'; open = false; $wire.set('cityFilter', '{{ $city->id }}')">{{ $city->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="w-full sm:w-24 ms-auto">
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
                                        :class="{ 'is-active': selected === '10' }"
                                        @click="selected = '10'; open = false; $wire.set('perPage', '10')">10</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '20' }"
                                        @click="selected = '20'; open = false; $wire.set('perPage', '20')">20</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '50' }"
                                        @click="selected = '50'; open = false; $wire.set('perPage', '50')">50</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '100' }"
                                        @click="selected = '100'; open = false; $wire.set('perPage', '100')">100</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" class="input-field pl-10" wire:model.live.debounce.300ms="search"
                                placeholder="Search airports by name or IATA code..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="p-6">
                @if ($crudMessage)
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] font-bold text-green-800 uppercase">
                        {{ $crudMessage }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group rounded-ss-lg" wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Airport Name</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('iata_code')">
                                    <div class="flex items-center gap-2">
                                        <span>IATA</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'iata_code' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'iata_code' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'iata_code' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide">City</th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide">Country</th>
                                <th class="px-6 py-2 text-end text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($airports as $airport)
                                <tr class="border-b border-gray-200 transition-colors hover:bg-blue-50">
                                    <td class="px-6 py-2">
                                        <div class="text-[11px] font-bold text-gray-900 uppercase">
                                            <a href="{{ route('admin.airports.view', $airport->id) }}" class="text-gray-900 hover:text-[#2ab4c0] transition-colors">
                                                {{ $airport->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-2">
                                        <span class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2 py-0.5 text-[11px] font-bold text-[#1f8f98]">
                                            {{ $airport->iata_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2 text-[11px] text-gray-600 font-medium">{{ $airport->city->name }}</td>
                                    <td class="px-6 py-2 text-[11px] text-gray-600">{{ $airport->city->country->name }}</td>
                                    <td class="px-6 py-2 text-end">
                                        <div class="flex items-center justify-end gap-2">

                                            <a href="{{ route('admin.airports.edit', $airport->id) }}"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                data-action="deleteAirport" data-args='[{{ $airport->id }}]'
                                                data-confirm-title="Are you sure?"
                                                data-confirm-text="This will permanently delete this airport."
                                                data-confirm-button-text="Yes, delete it"
                                                data-done-title="Deleted!"
                                                data-done-text="Airport has been deleted."
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-[11px] text-gray-500">No airports found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                @if ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0)
                    <div class="mt-8 pt-6 border-t border-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] text-gray-500 font-medium">
                                Showing <span class="font-bold text-gray-900">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                <span class="font-bold text-gray-900">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                <span class="font-bold text-gray-900">{{ $paginationMeta['total'] }}</span> airports
                            </div>
                            @if ($paginationMeta['last_page'] > 1)
                                <div class="flex items-center gap-1.5">
                                    @if ($paginationMeta['current_page'] > 1)
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                            Previous
                                        </button>
                                    @endif

                                    @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                        <button wire:click="goToPage({{ $page }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[11px] font-bold transition-all
                                            {{ $page === $paginationMeta['current_page']
                                                ? 'bg-[#2ab4c0] text-white shadow-md'
                                                : 'border border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                                            {{ $page }}
                                        </button>
                                    @endfor

                                    @if ($paginationMeta['has_more'])
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
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
        </div>
    </div>
</div>
