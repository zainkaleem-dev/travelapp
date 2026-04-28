<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Audit Logs</h1>
            </div>

            <div class="p-6">
                <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-[#2ab4c0]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">User</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Message</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Timestamp</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-white uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="border-t border-gray-100 align-top">
                                    <td class="px-4 py-3 text-gray-900 font-semibold">{{ $this->actorLabel($log) }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $this->activityMessage($log) }}</td>
                                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
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

