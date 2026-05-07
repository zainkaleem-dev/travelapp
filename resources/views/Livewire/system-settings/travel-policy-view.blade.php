<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Travel Policy Details</h1>
                <a href="{{ route('admin.system-settings', ['activeTab' => 'travel-policy']) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 hover:bg-gray-50 uppercase tracking-wider transition-colors shadow-sm">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Policy Information</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Policy Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->name }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Associated Company</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->company->name }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Policy Type</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->policy_type }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Current Status</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $policy->is_active ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                {{ $policy->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">System Reference</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-400 uppercase font-mono">ID: {{ str_pad($policy->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Last Modified</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->updated_at?->format('d/M/Y - H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Policy Description</p>
                <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Internal Description</p>
                    <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">
                        {{ $policy->description ?: 'No description provided for this policy.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
