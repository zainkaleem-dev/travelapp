<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Billing Entity</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'billing-entity'])
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase mb-3">Billing Entity</h2>
                <p class="text-[11px] text-gray-600">Billing entity section is ready for configuration.</p>
            </div>
        </div>
    </div>
</div>

