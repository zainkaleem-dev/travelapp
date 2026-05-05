@php
    $companyNavId = $companyId ?? null;
    $activeTab = $activeTab ?? 'info';
    $isSuperAdmin = auth()->user()?->can('Manage Global System') ?? false;
    $hasBranches = $companyNavId ? \App\Models\Branch::where('company_id', $companyNavId)->exists() : false;
    $hasBilling = $companyNavId ? \App\Models\BillingDetail::where('company_id', $companyNavId)->exists() : false;
@endphp

<div class="px-6 pt-4 border-b border-gray-200 bg-white">
    <div class="flex items-center gap-0 overflow-x-auto custom-scrollbar pb-1 text-xs font-semibold w-full">
        <a href="{{ route('companies.create') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t {{ $activeTab === 'info' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.5 6.75h15m-15 5.25h15m-15 5.25h9" />
            </svg>
            {{ $isSuperAdmin ? 'Organization Information' : 'Partner Information' }}
        </a>

        @if($companyNavId)
            <a href="{{ route('companies.create-branches', ['id' => $companyNavId]) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t {{ $activeTab === 'branches' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                </svg>
                Branches
            </a>
            @if($hasBranches)
                <a href="{{ route('companies.create-billing', ['id' => $companyNavId]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t {{ $activeTab === 'billing-entity' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7.5h18M3 12h18M3 16.5h10M6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75A2.25 2.25 0 0 1 6.75 4.5z" />
                    </svg>
                    Billing Details
                </a>
                @if($hasBilling)
                    <a href="{{ route('companies.create-integrations', ['id' => $companyNavId]) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t {{ $activeTab === 'integrations' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>
                        Integrations & API
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please save billing details first">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>
                        Integrations & API
                    </span>
                @endif
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please save branches first">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7.5h18M3 12h18M3 16.5h10M6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75A2.25 2.25 0 0 1 6.75 4.5z" />
                    </svg>
                    Billing Details
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please save branches first">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                    Integrations & API
                </span>
            @endif
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please create organization first">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                </svg>
                Branches
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please create organization first">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 7.5h18M3 12h18M3 16.5h10M6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75A2.25 2.25 0 0 1 6.75 4.5z" />
                </svg>
                Billing Details
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t text-gray-300 cursor-not-allowed whitespace-nowrap" title="Please create organization first">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                </svg>
                Integrations & API
            </span>
        @endif
    </div>
</div>
