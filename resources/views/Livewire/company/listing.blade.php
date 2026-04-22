<div x-data="{ filtersOpen: true }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Companies</h1>
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
                        @can('Create Company')
                            <a href="{{ route('companies.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                Add Organization
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-4 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <!-- Top Filter Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="w-full sm:w-44">
                            <div class="relative" x-data="{ open: false, selected: @js($typeFilter ?? '') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="open = !open">
                                    <span x-text="selected === '' ? 'All Types' : selected"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '' }"
                                        @click="selected = ''; open = false; $wire.set('typeFilter', '')">All
                                        Types</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'Corporate' }"
                                        @click="selected = 'Corporate'; open = false; $wire.set('typeFilter', 'Corporate')">Corporate</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'TMC' }"
                                        @click="selected = 'TMC'; open = false; $wire.set('typeFilter', 'TMC')">TMC</button>
                                </div>
                            </div>
                        </div>

                        <div class="w-full sm:w-44">
                            <div class="relative" x-data="{ open: false, selected: @js($statusFilter ?? '') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="open = !open">
                                    <span
                                        x-text="selected === '' ? 'All Statuses' : (selected.charAt(0).toUpperCase() + selected.slice(1))"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '' }"
                                        @click="selected = ''; open = false; $wire.set('statusFilter', '')">All
                                        Statuses</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'active' }"
                                        @click="selected = 'active'; open = false; $wire.set('statusFilter', 'active')">Active</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'inactive' }"
                                        @click="selected = 'inactive'; open = false; $wire.set('statusFilter', 'inactive')">Inactive</button>
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

                    <!-- Search Row -->
                    <div class="w-full">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" class="input-field pl-10" wire:model.live.debounce.300ms="search"
                                placeholder="Search companies by name, slug, or type..." />
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 font-bold">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Datatable -->
                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th
                                    class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide rounded-ss-2xl">
                                    Logo
                                </th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Company</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('company_type')">
                                    <div class="flex items-center gap-2">
                                        <span>Type</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'company_type' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'company_type' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'company_type' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('status')">
                                    <div class="flex items-center gap-2">
                                        <span>Status</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'status' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'status' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'status' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide rounded-se-2xl">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr
                                    class="border-b border-gray-200 transition-colors {{ $company->status === 'active' ? 'hover:bg-blue-50' : 'bg-gray-100' }}">
                                    <td class="px-6 py-4">
                                        <div
                                            class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if ($company->settings['logo_path'] ?? null)
                                                <img src="{{ asset('storage/' . $company->settings['logo_path']) }}"
                                                    alt="{{ $company->name }} logo" class="w-full h-full object-contain p-1">
                                            @else
                                                <span class="text-xs font-black text-gray-500">
                                                    {{ strtoupper(mb_substr((string) $company->name, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $company->slug }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2.5 py-0.5 text-xs font-bold text-[#1f8f98]">
                                            {{ $company->company_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-xs font-semibold">
                                            {{ ucfirst($company->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1">
                                            @can('View Company')
                                                <a href="{{ route('companies.show', $company->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="View Profile">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('Edit Company')
                                                <a href="{{ route('companies.edit', $company->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('Manage Features')
                                                <a href="{{ route('companies.features', $company->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Manage Features">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                data-action="toggleActive" data-args='[{{ $company->id }}]'
                                                data-confirm-title="Change company status?"
                                                data-confirm-text="Are you sure you want to change the active status of this company?"
                                                data-confirm-button-text="Yes, change it" data-done-title="Updated!"
                                                data-done-text="Company status has been updated."
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="{{ $company->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                @if ($company->status === 'active')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10 14h4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                            @can('Delete Company')
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteCompany" data-args='[{{ $company->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will delete the company and its related data."
                                                    data-confirm-button-text="Yes, delete it" data-done-title="Deleted!"
                                                    data-done-text="Company has been deleted."
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Delete Company">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        No companies found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                @if ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0)
                    <div class="mt-8 pt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Showing <span class="font-semibold">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                <span class="font-semibold">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                <span class="font-semibold">{{ $paginationMeta['total'] }}</span> companies
                            </div>
                            @if ($paginationMeta['last_page'] > 1)
                                <div class="flex items-center gap-2">
                                    @if ($paginationMeta['current_page'] > 1)
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                                            Previous
                                        </button>
                                    @endif

                                    @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                                    <button wire:click="goToPage({{ $page }})" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-sm font-medium
                                                                                                                                                                                                                                                                                                                                                                                                                                         {{ $page === $paginationMeta['current_page']
                                        ? 'bg-[#2ab4c0] text-white'
                                        : 'border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                                        {{ $page }}
                                                    </button>
                                    @endfor

                                    @if ($paginationMeta['has_more'])
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
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