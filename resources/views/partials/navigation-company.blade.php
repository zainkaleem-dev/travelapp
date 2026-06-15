@php
    $companyNavId = $companyId ?? request()->route('companyId') ?? request()->route('id');
    $activeCompanyTab = $activeTab ?? null;
@endphp

<div class="px-6 pt-2 border-b border-gray-200 bg-white">
    <div class="flex items-center gap-0 overflow-x-auto custom-scrollbar pb-1 text-[11px] font-semibold w-full">
        <a href="{{ $companyNavId ? route('companies.show', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'general' || ($activeCompanyTab === null && request()->routeIs('companies.show'))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.5 6.75h15m-15 5.25h15m-15 5.25h9" />
            </svg>
            General Information
        </a>

        <a href="{{ $companyNavId ? route('companies.attachments', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'attachments' || ($activeCompanyTab === null && request()->routeIs('companies.attachments'))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 6h9M7.5 10.5h9m-9 4.5h6M6 3h12a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 18 21H6A2.25 2.25 0 0 1 3.75 18.75V5.25A2.25 2.25 0 0 1 6 3z" />
            </svg>
            Attachments
        </a>

        <a href="{{ $companyNavId ? route('companies.branches', $companyNavId) : route('branches.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'branches' || ($activeCompanyTab === null && (request()->routeIs('companies.branches') || request()->routeIs('branches.*')))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
            </svg>
            Branches
        </a>

        <a href="{{ $companyNavId ? route('companies.user-roles', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'users-roles' || ($activeCompanyTab === null && (request()->routeIs('companies.user-roles') || request()->routeIs('users.*')))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0z" />
            </svg>
            User and Roles
        </a>

        @featureOrAdmin('feature-management-module')
        @can('Manage Features')
        <a href="{{ $companyNavId ? route('companies.features', $companyNavId) : route('features') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'feature-management' || ($activeCompanyTab === null && (request()->routeIs('companies.features') || request()->routeIs('features*')))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            </svg>
            Feature Management
        </a>
        @endcan
        @endfeatureOrAdmin

        <a href="{{ $companyNavId ? route('companies.roles-permissions', $companyNavId) : route('roles.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'roles-permissions' || ($activeCompanyTab === null && (request()->routeIs('companies.roles-permissions') || request()->routeIs('roles.*')))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068" />
            </svg>
            Roles Permissions
        </a>

        <a href="{{ $companyNavId ? route('companies.billing-entity', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'billing-entity' || ($activeCompanyTab === null && request()->routeIs('companies.billing-entity'))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 7.5h18M3 12h18M3 16.5h10M6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75A2.25 2.25 0 0 1 6.75 4.5z" />
            </svg>
            Billing Entity
        </a>

        @can('View Travel Policy')
        @if(!auth()->user()->hasRole('Super Admin'))
        <a href="{{ $companyNavId ? route('companies.travel-policy', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'travel-policy' || ($activeCompanyTab === null && request()->routeIs('companies.travel-policy'))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            Travel Policy
        </a>
        @endif
        @endcan

        <a href="{{ $companyNavId ? route('companies.integrations', $companyNavId) : '#' }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 rounded-t-lg {{ ($activeCompanyTab === 'integrations' || ($activeCompanyTab === null && request()->routeIs('companies.integrations'))) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
            </svg>
            Integrations & API
        </a>
    </div>
</div>

