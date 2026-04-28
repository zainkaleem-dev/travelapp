<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Billing Entity</h1>
                    <p class="text-sm text-gray-500 mt-1">Organization billing entity details for {{ $company->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('companies.show', $companyId) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back to Profile
                    </a>
                </div>
            </div>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'billing-entity'])

        <div class="p-6">
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase mb-3">Billing Entity</h2>
                <p class="text-sm text-gray-600">Billing entity section is ready for configuration.</p>
            </div>
        </div>
    </div>
</div>

