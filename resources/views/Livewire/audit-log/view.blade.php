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
                <div class="rounded-xl border border-gray-200 bg-white p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">User</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $this->actorLabel() }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Organization</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->company?->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Branch</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->branch?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Timestamp</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activityLog->created_at?->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Action</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ ucwords($activityLog->action_name ?: '-') }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Action Page</p>
                    <p class="text-sm text-gray-900">
                        {{ $this->pageName() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Action Detail</p>
                    <p class="text-sm text-gray-800 leading-6">{{ $this->detailedMessage() }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-50">Before State</p>
                        <div class="space-y-3">
                            @forelse($this->beforeState() as $label => $value)
                                <div class="flex justify-between items-start gap-4">
                                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-tight">{{ $label }}</span>
                                    <span class="text-sm text-gray-800 font-medium text-right">{{ $value }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic">No previous data captured.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-50">After State</p>
                        <div class="space-y-3">
                            @forelse($this->afterState() as $label => $value)
                                <div class="flex justify-between items-start gap-4">
                                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-tight">{{ $label }}</span>
                                    <span class="text-sm text-[#2ab4c0] font-bold text-right">{{ $value }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic">No new data captured.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

