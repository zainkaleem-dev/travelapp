<div x-data="{ filtersOpen: true }">
    <div class="max-w-6xl px-1 py-1">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Branches</h1>
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
                        <a href="{{ route('superadmin.branches.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-[0.999rem] bg-[#2ab4c0] px-3 py-2 text-[13px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                            Add Branch
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <!-- Top Filter Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="w-full sm:w-64">
                            <div class="field-wrap !py-2 !px-3">
                                <select wire:model.live="companyFilter"
                                    class="field-input !p-0 !border-0 !bg-transparent text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer appearance-none">
                                    <option value="">All Companies</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
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
                                placeholder="Search branches by name, code, or city..." />
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group rounded-tl-2xl"
                                    wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Branch</span>
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
                                    wire:click="sort('company')">
                                    <div class="flex items-center gap-2">
                                        <span>Company</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'company' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'company' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'company' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('city')">
                                    <div class="flex items-center gap-2">
                                        <span>City</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'city' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'city' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'city' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('email')">
                                    <div class="flex items-center gap-2">
                                        <span>Email</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'email' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'email' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'email' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide cursor-pointer group"
                                    wire:click="sort('phone')">
                                    <div class="flex items-center gap-2">
                                        <span>Phone</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'phone' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-2.5 h-2.5 {{ $sortBy === 'phone' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 -mt-1 {{ $sortBy === 'phone' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide rounded-tr-2xl">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($branches as $branch)
                            <tr class="border-b border-gray-200 transition-colors {{ $branch->status === 'active' ? 'hover:bg-blue-50' : 'bg-gray-100' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                            <span class="text-xs font-black text-gray-500">
                                                {{ strtoupper(mb_substr((string) $branch->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $branch->name }}</div>
                                            <div class="text-[10px] text-gray-500 font-mono tracking-tighter">
                                                {{ $branch->code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $branch->company->name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $branch->city ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $branch->email ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $branch->phone ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md {{ $branch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-xs font-semibold">
                                        {{ ucfirst($branch->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('superadmin.branches.edit', $branch->id) }}"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <button type="button" x-on:click="appSwalConfirmAction({
                                                    wire: $wire,
                                                    action: 'toggleActive',
                                                    args: [{{ $branch->id }}],
                                                    confirmTitle: 'Change branch status?',
                                                    doneTitle: 'Branch status updated'
                                                })"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
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

                                        <button type="button" x-on:click="appSwalConfirmAction({
                                                    wire: $wire,
                                                    action: 'deleteBranch',
                                                    args: [{{ $branch->id }}],
                                                    confirmTitle: 'Are you sure?',
                                                    confirmText: 'This will delete the branch and its related data.',
                                                    confirmButtonText: 'Yes, delete it',
                                                    doneTitle: 'Deleted!',
                                                    doneText: 'Branch has been deleted.'
                                                })"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                            title="Delete Branch">
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
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold">{{ $paginationMeta['from'] ?? 0 }}</span> to
                            <span class="font-semibold">{{ $paginationMeta['to'] ?? 0 }}</span> of
                            <span class="font-semibold">{{ $paginationMeta['total'] }}</span> branches
                        </div>
                        @if ($paginationMeta['last_page'] > 1)
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if ($paginationMeta['current_page'] > 1)
                            <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                                ← Previous
                            </button>
                            @else
                            <button disabled
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-semibold opacity-50">
                                ← Previous
                            </button>
                            @endif

                            {{-- Page Numbers --}}
                            @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                <button wire:click="goToPage({{ $page }})" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-sm font-medium
                                                                {{ $page === $paginationMeta['current_page']
                                        ? 'bg-[#2ab4c0] text-white'
                                        : 'border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                {{ $page }}
                                </button>
                                @endfor

                                {{-- Next Button --}}
                                @if ($paginationMeta['has_more'])
                                <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                                    Next →
                                </button>
                                @else
                                <button disabled
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-semibold opacity-50">
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