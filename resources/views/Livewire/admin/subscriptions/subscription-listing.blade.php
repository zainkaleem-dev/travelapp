<div class="px-1 py-1 w-full">
    <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Subscriptions</h1>
                    <p class="text-[11px] font-bold text-gray-500 uppercase mt-1">Manage company plans and feature entitlements</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.subscriptions.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm uppercase">
                        Add Subscription
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex flex-col gap-4">
                <div class="w-full">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" class="input-field pl-10" wire:model.live.debounce.300ms="search"
                            placeholder="Search subscriptions by plan name or company..." />
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
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">Company</th>
                            <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">Plan Name</th>
                            <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">Price</th>
                            <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-500 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($subscriptions as $sub)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-[11px] font-bold text-gray-900 uppercase">{{ $sub->company->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[11px] font-bold text-gray-700 uppercase">{{ $sub->plan_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[11px] font-bold text-gray-700 uppercase">{{ number_format($sub->price, 2) }} {{ session('currency', 'USD') }}</div>
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.subscriptions.view', $sub->id) }}"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                            title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                <circle cx="12" cy="13.5" r="3" stroke-width="2" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.subscriptions.edit', $sub->id) }}"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button 
                                            onclick="appSwalConfirmAction({
                                                wire: @this,
                                                action: 'deleteSubscription',
                                                args: [{{ $sub->id }}],
                                                confirmTitle: 'Delete Subscription?',
                                                confirmText: 'This will remove the subscription for {{ $sub->company->name }}.',
                                                doneTitle: 'Deleted!',
                                                doneText: 'Subscription has been removed.',
                                                confirmButtonText: 'Yes, delete it'
                                            })"
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
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-[11px] font-bold uppercase">No subscriptions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subscriptions->hasPages())
                <div class="mt-6 border-t border-gray-100 pt-6">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
