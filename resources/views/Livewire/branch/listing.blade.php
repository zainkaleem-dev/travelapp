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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </button>
                        <a href="{{ route('superadmin.branches.create') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                            Add Branch
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="filtersOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="w-full sm:w-64">
                        <div class="field-wrap !py-2 !px-3">
                            <input type="text" class="field-input" wire:model.live.debounce.300ms="search" placeholder="Search branches..." />
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Branch</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Company</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">City</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($branches as $branch)
                            <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                            <span class="text-xs font-black text-gray-500">
                                                {{ strtoupper(mb_substr((string) $branch->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $branch->name }}</div>
                                            <div class="text-[10px] text-gray-500 font-mono tracking-tighter">{{ $branch->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <span class="text-sm font-medium text-gray-900">{{ $branch->company->name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <span class="text-sm text-gray-900">{{ $branch->city ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <span class="text-sm text-gray-900">{{ $branch->email ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <span class="text-sm text-gray-900">{{ $branch->phone ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4 {{ $branch->status === 'active' ? '' : 'opacity-60 grayscale' }}">
                                    <span class="inline-flex items-center rounded-md {{ $branch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-xs font-semibold">
                                        {{ ucfirst($branch->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('superadmin.branches.edit', $branch->id) }}"
                                            class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <button type="button"
                                            x-on:click="confirmBranchStatusToggle($wire, {{ $branch->id }}, @js($branch->status), @js($branch->name))"
                                            class="inline-flex items-center justify-center p-2 rounded-lg text-xs font-semibold {{ $branch->status === 'active' ? 'bg-[#2ab4c0] text-white hover:bg-[#229aa4]' : 'bg-[#2ab4c0]/70 text-white hover:bg-[#229aa4]/70' }}"
                                            title="{{ $branch->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            @if ($branch->status === 'active')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14h4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            @endif
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
                                <button
                                wire:click="goToPage({{ $page }})"
                                class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium
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

@once
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.confirmBranchStatusToggle = async function(wire, branchId, currentStatus, branchName) {
            const isActive = currentStatus === 'active';
            const actionVerb = isActive ? 'deactivate' : 'activate';

            if (!window.Swal) {
                if (window.confirm(`Are you sure you want to ${actionVerb} ${branchName}?`)) {
                    await wire.toggleActive(branchId);
                }
                return;
            }

            const result = await Swal.fire({
                title: 'Are you sure?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-100 shadow-2xl',
                    title: 'text-gray-900 font-black',
                    htmlContainer: 'text-gray-600',
                    actions: 'gap-3',
                    confirmButton: 'inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-bold text-white hover:bg-[#229aa4]',
                    cancelButton: 'inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-50'
                }
            });

            if (!result.isConfirmed) return;

            await wire.toggleActive(branchId);

            await Swal.fire({
                title: 'Done',
                icon: 'success',
                timer: 1400,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-100 shadow-xl',
                    title: 'text-gray-900 font-black',
                    htmlContainer: 'text-gray-600'
                }
            });
        };
    </script>
@endonce
