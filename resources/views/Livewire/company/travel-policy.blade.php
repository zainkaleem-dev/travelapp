<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Travel Policy</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'travel-policy'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            <div class="space-y-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Organization Travel Policies (Read Only)</p>

                @forelse($policies as $policy)
                    <div class="relative p-6 rounded-lg border border-gray-200 bg-gray-50/30 shadow-sm mb-6 last:mb-0">
                        <div class="mb-6 flex items-center justify-between gap-3">
                            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest border-l-4 border-[#2ab4c0] pl-3">
                                {{ $policy->name ?: 'Travel Policy' }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#1f8f98] uppercase tracking-tight">
                                    {{ ucfirst($policy->policy_type) }}
                                </span>
                                <span class="inline-flex items-center rounded-md {{ $policy->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-tight">
                                    {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Policy Name</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->name }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Policy Type</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->policy_type }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Current Status</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $policy->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>

                            <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Description</p>
                                <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">{{ $policy->description ?: 'No description provided.' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-6 text-center text-[11px] text-gray-500 font-semibold uppercase tracking-wider shadow-sm">
                        No active travel policies found for this organization.
                    </div>
                @endforelse
            </div>

            @if($policies->hasPages())
                <div class="mt-8 pt-6 border-t border-gray-100">
                    {{ $policies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
