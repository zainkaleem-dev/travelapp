<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Billing Entity</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'billing-entity'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            <!-- Section 1: Entity Information -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Entity Information</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Legal Entity Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->entity_name ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Display / Trade Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->display_name ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Registration Number</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->registration_number ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Tax / VAT Number</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->tax_number ?: '--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Currency & Location -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Currency & Location</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Currency Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->currency ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Currency Code</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-700 font-mono uppercase">{{ $billing?->currency_code ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Country</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->country ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">City</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->city ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">State / Province</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->state ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Postal Code</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->postal_code ?: '--' }}</p>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Full Address</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">
                            {{ $billing?->address_line_1 }}@if($billing?->address_line_2), {{ $billing?->address_line_2 }}@endif
                            @if(!$billing?->address_line_1 && !$billing?->address_line_2)--@endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Contact Person -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Contact Person</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Full Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">
                            {{ trim(($billing?->first_name ?? '') . ' ' . ($billing?->middle_name ?? '') . ' ' . ($billing?->last_name ?? '')) ?: '--' }}
                        </p>
                    </div>
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Email Address</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->email ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Phone</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->phone ?: '--' }}</p>
                    </div>
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Fax</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->fax ?: '--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 4: Banking Details -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Banking Details</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bank Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $billing?->bank_name ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Account Number</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $billing?->bank_account_number ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">IBAN</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $billing?->bank_iban ?: '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">SWIFT / BIC</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $billing?->bank_swift ?: '--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 5: Additional Info -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Additional Information</p>
                <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Internal Notes</p>
                    <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">{{ $billing?->notes ?: 'No internal notes.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
