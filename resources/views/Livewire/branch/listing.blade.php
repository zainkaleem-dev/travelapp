<div x-data="{ filtersOpen: true }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Branches</h1>
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
                        @can('Create Branch')
                            <a href="{{ route('branches.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                Add Branch
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="px-6 py-4 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <!-- Top Filter Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($routePrefix === 'admin')
                            <div class="w-full sm:w-64">
                                <div class="relative"
                                    x-data="{ open: false, selected: @js((string) ($companyFilter ?? '')), labels: @js($companies->pluck('name', 'id')) }"
                                    @keydown.escape.window="open = false" @click.outside="open = false">
                                    <button type="button" class="admin-menu-btn" @click="open = !open">
                                        <span
                                            x-text="selected === '' ? 'All Companies' : (labels[selected] ?? 'All Companies')"></span>
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
                                            @click="selected = ''; open = false; $wire.set('companyFilter', '')">All
                                            Companies</button>
                                        @foreach ($companies as $company)
                                            <button type="button" class="admin-menu-item"
                                                :class="{ 'is-active': selected === '{{ $company->id }}' }"
                                                @click="selected = '{{ $company->id }}'; open = false; $wire.set('companyFilter', '{{ $company->id }}')">{{ $company->name }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                placeholder="Search branches by name, code, or city..." />
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] text-green-800 uppercase font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-[11px] text-red-800 font-bold uppercase">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Datatable -->
                <div class="overflow-x-auto rounded-t-lg">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group rounded-ss-lg"
                                    wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Branch</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('company')">
                                    <div class="flex items-center gap-2">
                                        <span>Company</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'company' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'company' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'company' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('city')">
                                    <div class="flex items-center gap-2">
                                        <span>City</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'city' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'city' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'city' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('email')">
                                    <div class="flex items-center gap-2">
                                        <span>Email</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'email' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'email' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'email' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('phone')">
                                    <div class="flex items-center gap-2">
                                        <span>Phone</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'phone' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'phone' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'phone' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('status')">
                                    <div class="flex items-center gap-2">
                                        <span>Status</span>
                                        <div
                                            class="flex flex-col transition-opacity {{ $sortBy === 'status' ? 'opacity-100' : 'opacity-70' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'status' && $sortDirection === 'asc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'status' && $sortDirection === 'desc' ? 'text-white' : 'text-white/70' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-start text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($branches as $branch)
                                <tr
                                    class="border-b border-gray-200 transition-colors {{ $branch->status === 'active' ? 'hover:bg-blue-50' : 'bg-gray-100' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                                <span class="text-xs font-black text-gray-500">
                                                    {{ strtoupper(mb_substr((string) $branch->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-[11px] font-semibold text-gray-900">{{ $branch->name }}</div>
                                                <div class="text-[10px] text-gray-500 font-mono tracking-tighter">
                                                    {{ $branch->code }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-[11px] font-medium text-gray-900">{{ $branch->company->name ?? '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[11px] text-gray-900">{{ $branch->city ?: '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[11px] text-gray-900">{{ $branch->email ?: '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[11px] text-gray-900">{{ $branch->phone ?: '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md {{ $branch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-[11px] font-semibold">
                                            {{ ucfirst($branch->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1">
                                            @can('Edit Branch')
                                                <a href="{{ route('branches.edit', $branch->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                data-action="toggleActive" data-args='[{{ $branch->id }}]'
                                                data-confirm-title="Change branch status?"
                                                data-done-title="Branch status updated"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                title="{{ $branch->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                @if ($branch->status === 'active')
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
                                            @can('Delete Branch')
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteBranch" data-args='[{{ $branch->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will delete the branch and its related data."
                                                    data-confirm-button-text="Yes, delete it" data-done-title="Deleted!"
                                                    data-done-text="Branch has been deleted."
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                                    title="Delete Branch">
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
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        No branches found.
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
                            <div class="text-[11px] text-gray-600 uppercase font-semibold">
                                Showing <span class="font-bold text-gray-900">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                <span class="font-bold text-gray-900">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                <span class="font-bold text-gray-900">{{ $paginationMeta['total'] }}</span> branches
                            </div>
                            @if ($paginationMeta['last_page'] > 1)
                                <div class="flex items-center gap-2">
                                    {{-- Previous Button --}}
                                    @if ($paginationMeta['current_page'] > 1)
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-[11px] font-semibold">
                                            Previous
                                        </button>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-[11px] font-semibold opacity-50">
                                            Previous
                                        </button>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                                    <button wire:click="goToPage({{ $page }})" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-[11px] font-medium
                                                                                                                                                                                                                                                                                                                                        {{ $page === $paginationMeta['current_page']
                                        ? 'bg-[#2ab4c0] text-white'
                                        : 'border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                                        {{ $page }}
                                                    </button>
                                    @endfor

                                    {{-- Next Button --}}
                                    @if ($paginationMeta['has_more'])
                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-[11px] font-semibold">
                                            Next
                                        </button>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-[11px] font-semibold opacity-50">
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