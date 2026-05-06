<div class="w-full" x-data="{ filtersOpen: true }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Countries & Cities</h1>
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
                        <div class="mt-0">
                            @if($activeTab === 'countries')
                                <a href="{{ route('admin.countries.create') }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                    Add Country
                                </a>
                            @else
                                <a href="{{ route('admin.cities.create') }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                    Add City
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Standard Sub-Navigation --}}
            <div class="px-6 pt-4 border-b border-gray-200 bg-white">
                <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-[11px] font-semibold w-full">
                    <button wire:click="setTab('countries')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t transition-colors whitespace-nowrap {{ $activeTab === 'countries' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A11.954 11.954 0 0 1 12 16.5c-2.998 0-5.74-1.1-7.843-2.918m0 0A8.959 8.959 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                        </svg>
                        Countries
                    </button>
                    <button wire:click="setTab('cities')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t transition-colors whitespace-nowrap {{ $activeTab === 'cities' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Cities
                    </button>
                </div>
            </div>

            {{-- Filters Section --}}
            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-3 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap items-center gap-3">
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
                                placeholder="Search {{ $activeTab === 'countries' ? 'countries' : 'cities' }}..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="p-6">
                @if ($crudMessage)
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ $crudMessage }}
                    </div>
                @endif

                @if($activeTab === 'countries')
                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-0">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group rounded-ss-2xl" wire:click="sort('name')">
                                        <div class="flex items-center gap-2">
                                            <span>Name</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('code')">
                                        <div class="flex items-center gap-2">
                                            <span>Code</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'code' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'code' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'code' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('dial_code')">
                                        <div class="flex items-center gap-2">
                                            <span>Dial Code</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'dial_code' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'dial_code' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'dial_code' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('created_at')">
                                        <div class="flex items-center gap-2">
                                            <span>Added on</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'created_at' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'created_at' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'created_at' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-end text-[11px] font-bold text-white uppercase tracking-wide rounded-se-2xl">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $country)
                                    <tr class="border-b border-gray-200 transition-colors hover:bg-blue-50">
                                        <td class="px-6 py-2 text-[11px] font-semibold text-gray-900">{{ $country->name }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600">{{ $country->code }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600">{{ $country->dial_code }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600 font-medium">{{ $country->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-2 text-end">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.countries.view', $country->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.countries.edit', $country->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteCountry" data-args='[{{ $country->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will permanently delete this country."
                                                    data-confirm-button-text="Yes, delete it"
                                                    data-done-title="Deleted!"
                                                    data-done-text="Country has been deleted."
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
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
                                        <td colspan="5" class="px-6 py-6 text-center text-[11px] text-gray-500">No countries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-0">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group rounded-ss-2xl" wire:click="sort('name')">
                                        <div class="flex items-center gap-2">
                                            <span>Name</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('country_id')">
                                        <div class="flex items-center gap-2">
                                            <span>Country</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'country_id' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'country_id' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'country_id' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('code')">
                                        <div class="flex items-center gap-2">
                                            <span>Code</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'code' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'code' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'code' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('created_at')">
                                        <div class="flex items-center gap-2">
                                            <span>Added on</span>
                                            <div class="flex flex-col transition-opacity {{ $sortBy === 'created_at' ? 'opacity-100' : 'opacity-40' }}">
                                                <svg class="w-3.5 h-3.5 {{ $sortBy === 'created_at' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'created_at' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 text-end text-[11px] font-bold text-white uppercase tracking-wide rounded-se-2xl">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $city)
                                    <tr class="border-b border-gray-200 transition-colors hover:bg-blue-50">
                                        <td class="px-6 py-2 text-[11px] font-semibold text-gray-900">{{ $city->name }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600">{{ $city->country->name }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600">{{ $city->code }}</td>
                                        <td class="px-6 py-2 text-[11px] text-gray-600 font-medium">{{ $city->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-2 text-end">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.cities.view', $city->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteCity" data-args='[{{ $city->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will permanently delete this city."
                                                    data-confirm-button-text="Yes, delete it"
                                                    data-done-title="Deleted!"
                                                    data-done-text="City has been deleted."
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
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
                                        <td colspan="5" class="px-6 py-6 text-center text-[11px] text-gray-500">No cities found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Pagination Controls -->
                @if ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0)
                    <div class="mt-8 pt-6 border-t border-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] text-gray-500 font-medium">
                                Showing <span class="font-bold text-gray-900">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                <span class="font-bold text-gray-900">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                <span class="font-bold text-gray-900">{{ $paginationMeta['total'] }}</span> {{ $activeTab === 'countries' ? 'countries' : 'cities' }}
                            </div>
                            @if ($paginationMeta['last_page'] > 1)
                                <div class="flex items-center gap-1.5">
                                    @if ($paginationMeta['current_page'] > 1)
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                            Previous
                                        </button>
                                    @endif

                                    @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                        <button wire:click="goToPage({{ $page }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-[11px] font-bold transition-all
                                            {{ $page === $paginationMeta['current_page']
                                                ? 'bg-[#2ab4c0] text-white shadow-md'
                                                : 'border border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                                            {{ $page }}
                                        </button>
                                    @endfor

                                    @if ($paginationMeta['has_more'])
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
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
