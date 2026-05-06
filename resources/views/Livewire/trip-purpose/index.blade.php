<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Trips</h1>
                    </div>
                    <a href="{{ route('admin.trip-purpose.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                        Add Trip
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="p-6 space-y-6">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-lg border border-gray-200/80 bg-white overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-[#2ab4c0]">
                        <tr>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-white uppercase tracking-wide">Trip Purpose</th>
                            <th class="px-4 py-2 text-right text-[11px] font-bold text-white uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tripPurposes as $purpose)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-2 text-[11px] text-gray-900 font-semibold">{{ $purpose->label }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.trip-purpose.view', $purpose->id) }}"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                            title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.trip-purpose.edit', $purpose->id) }}"
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                            data-action="deletePurpose" data-args='[{{ $purpose->id }}]'
                                            data-confirm-title="Are you sure?"
                                            data-confirm-text="This will permanently delete this trip."
                                            data-confirm-button-text="Yes, delete it"
                                            data-done-title="Deleted!"
                                            data-done-text="Trip has been deleted."
                                            class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                            title="Delete">
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
                                <td colspan="2" class="px-4 py-8 text-center text-[11px] text-gray-500">No trip purposes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

