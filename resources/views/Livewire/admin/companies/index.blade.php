<div x-data="{ filtersOpen: true }">
    <div class="max-w-6xl px-1 py-1">
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
                        <a href="{{ route('superadmin.companies.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-[#2ab4c0] px-6 py-2.5 text-base font-bold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Company
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <!-- Top Filter Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="w-full sm:w-44">
                            <div class="field-wrap !py-2 !px-3">
                                <select wire:model.live="typeFilter"
                                    class="field-input !p-0 !border-0 !bg-transparent text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer appearance-none">
                                    <option value="">All Types</option>
                                    <option value="Corporate">Corporate</option>
                                    <option value="TMC">TMC</option>
                                </select>
                            </div>
                        </div>

                        <div class="w-full sm:w-44">
                            <div class="field-wrap !py-2 !px-3">
                                <select wire:model.live="statusFilter"
                                    class="field-input !p-0 !border-0 !bg-transparent text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer appearance-none">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="w-full sm:w-24 ml-auto">
                            <div class="field-wrap !py-2 !px-3 flex items-center justify-center">
                                <select wire:model.live="perPage"
                                    class="field-input !p-0 !border-0 !bg-transparent text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer appearance-none text-center">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Search Row -->
                    <div class="w-full">
                        <div class="field-wrap !py-3 !px-4 flex items-center gap-3 bg-white border-gray-200 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" class="field-input text-base placeholder-gray-400" wire:model.live.debounce.300ms="search"
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

                <!-- Datatable -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">
                                    Logo
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Company</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('company_type')">
                                    <div class="flex items-center gap-2">
                                        <span>Type</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'company_type' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'company_type' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'company_type' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('status')">
                                    <div class="flex items-center gap-2">
                                        <span>Status</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'status' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'status' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'status' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 {{ $company->status === 'active' ? '' : 'opacity-60 grayscale' }}">
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
                                    <td class="px-6 py-4 {{ $company->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                        <div class="text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $company->slug }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 {{ $company->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                        <span
                                            class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2.5 py-0.5 text-xs font-bold text-[#1f8f98]">
                                            {{ $company->company_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 {{ $company->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                        <span
                                            class="inline-flex items-center rounded-md {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-xs font-semibold">
                                            {{ ucfirst($company->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('superadmin.companies.edit', $company->id) }}"
                                                class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('superadmin.companies.features', $company->id) }}"
                                                class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-[#2ab4c0]/10 hover:text-[#2ab4c0] text-xs font-semibold"
                                                title="Manage Features">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>

                                            <button type="button" x-on:click="appSwalConfirmAction({
                                                    wire: $wire,
                                                    action: 'toggleActive',
                                                    args: [{{ $company->id }}],
                                                    confirmTitle: 'Change company status?',
                                                    doneTitle: 'Company status updated'
                                                })"
                                                class="inline-flex items-center justify-center p-2 rounded-lg text-xs font-semibold {{ $company->status === 'active' ? 'bg-[#2ab4c0] text-white hover:bg-[#229aa4]' : 'bg-[#2ab4c0]/70 text-white hover:bg-[#229aa4]/70' }}"
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

                                            <button type="button" x-on:click="appSwalConfirmAction({
                                                    wire: $wire,
                                                    action: 'deleteCompany',
                                                    args: [{{ $company->id }}],
                                                    confirmTitle: 'Are you sure?',
                                                    confirmText: 'This will delete the company and its related data.',
                                                    confirmButtonText: 'Yes, delete it',
                                                    doneTitle: 'Deleted!',
                                                    doneText: 'Company has been deleted.'
                                                })"
                                                class="inline-flex items-center justify-center p-2 rounded-lg text-xs font-semibold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100"
                                                title="Delete Company">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
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
                    <div class="mt-8 pt-6 border-t border-gray-200">
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
                                            ← Previous
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
                                            Next →
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