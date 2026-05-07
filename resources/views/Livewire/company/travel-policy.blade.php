<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        {{-- Unified Header & Navigation --}}
        <div class="bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="px-6 py-3.5">
                <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Travel Policy</h1>
            </div>
            @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'travel-policy'])
        </div>

        {{-- Content Section --}}
        <div class="p-6 space-y-6">
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Organization Travel Policies (Read Only)</p>

            @forelse($policies as $policy)
                <div class="relative space-y-4 mb-8 last:mb-0">
                    {{-- Policy Grid --}}
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
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $policy->is_active ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                    {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Description</p>
                        <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">
                            {{ $policy->description ?: 'No description provided.' }}
                        </p>
                    </div>
                    {{-- Thin line after description table --}}
                    <hr class="border-gray-50 !mt-4">
                </div>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white px-4 py-6 text-center text-[11px] text-gray-500 font-semibold uppercase tracking-wider shadow-sm">
                    No active travel policies found for this organization.
                </div>
            @endforelse

            @if($policies->hasPages())
                <div class="mt-8 pt-6 border-t border-gray-100">
                    {{ $policies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
