<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Audit Log Details</h1>
                    <a href="{{ route('admin.audit-logs') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">User</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $this->actorLabel() }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Timestamp</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->created_at?->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Page</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->page ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Action</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->action_name ?: '-' }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Before State</p>
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap break-words">{{ json_encode($this->beforeState(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">After State</p>
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap break-words">{{ json_encode($this->afterState(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Raw Activity</p>
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap break-words">{{ json_encode($activityLog->activity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>

