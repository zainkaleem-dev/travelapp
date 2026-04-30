<div x-data="{ filtersOpen: true }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-[26px] font-black text-gray-900 tracking-tight">Audit Logs</h1>
                    <button @click="filtersOpen = !filtersOpen"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 transition-colors"
                        :class="filtersOpen ? 'bg-[#2ab4c0]/10 text-[#2ab4c0] border-[#2ab4c0]/30' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        title="Toggle Filters">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-4 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Action Filter -->
                        <div class="w-full sm:w-44">
                            <div class="relative" x-data="{ open: false, selected: @js($actionFilter ?? '') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="open = !open">
                                    <span x-text="selected === '' ? 'All Actions' : (selected.charAt(0).toUpperCase() + selected.slice(1))"></span>
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
                                        @click="selected = ''; open = false; $wire.set('actionFilter', '')">All Actions</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'created' }"
                                        @click="selected = 'created'; open = false; $wire.set('actionFilter', 'created')">Created</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'updated' }"
                                        @click="selected = 'updated'; open = false; $wire.set('actionFilter', 'updated')">Updated</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'deleted' }"
                                        @click="selected = 'deleted'; open = false; $wire.set('actionFilter', 'deleted')">Deleted</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'viewed' }"
                                        @click="selected = 'viewed'; open = false; $wire.set('actionFilter', 'viewed')">Viewed</button>
                                </div>
                            </div>
                        </div>

                        <!-- Per Page Filter -->
                        <div class="w-full sm:w-24 ms-auto">
                            <div class="relative flex items-center justify-center"
                                x-data="{ open: false, selected: @js((string) ($perPage ?? 20)) }"
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

                    <!-- Search Bar -->
                    <div class="w-full">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-3 py-2.5 text-sm text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25 transition-all" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search by user name, page, or action..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="p-6">
{{-- 
                @if($selectionMode)
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-amber-900">
                                Bulk delete mode enabled ({{ count($selectedLogIds) }} selected)
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    wire:click="selectAllVisible"
                                    class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Select All
                                </button>
                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                    data-action="deleteSelectedLogs"
                                    data-confirm-title="Delete selected logs?"
                                    data-confirm-text="This will delete all selected audit logs."
                                    data-confirm-button-text="Yes, delete all"
                                    data-done-title="Deleted!"
                                    data-done-text="Selected audit logs have been deleted."
                                    class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                                    Delete Selected
                                </button>
                                <button type="button"
                                    wire:click="clearSelection"
                                    class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
--}}

                <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-[#2ab4c0]">
                            <tr>
{{-- 
                                @if($selectionMode)
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Select</th>
                                @endif
--}}
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-white uppercase tracking-wide">User</th>
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-white uppercase tracking-wide">Message</th>
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-white uppercase tracking-wide">Timestamp</th>
                                <th class="px-4 py-2.5 text-right text-xs font-bold text-white uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="border-t border-gray-100 align-top">
{{-- 
                                    @if($selectionMode)
                                        <td class="px-4 py-3">
                                            <input type="checkbox"
                                                wire:click="toggleSelected({{ $log->id }})"
                                                @checked(in_array($log->id, $selectedLogIds, true))
                                                class="h-4 w-4 rounded border-gray-300 text-[#2ab4c0] focus:ring-[#2ab4c0]">
                                        </td>
                                    @endif
--}}
                                    <td class="px-4 py-3 text-gray-900 font-semibold">{{ $this->actorLabel($log) }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $this->activityMessage($log) }}</td>
                                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $log->created_at?->format('d/m/Y (H:i:s)') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.audit-logs.view', $log->id) }}"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                </svg>
                                            </a>
                                            <button type="button" 
                                                x-on:click="appSwalFromDataset($el, $wire)"
                                                data-action="deleteLog({{ $log->id }})"
                                                data-confirm-title="Delete this log?"
                                                data-confirm-text="This will permanently delete this audit log entry."
                                                data-confirm-button-text="Yes, delete"
                                                data-done-title="Deleted!"
                                                data-done-text="The audit log has been deleted."
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="Delete Log">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            {{-- 
                                            <button type="button"
                                                wire:click="startBulkDeleteMode({{ $log->id }})"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="Delete Log">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No audit logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

