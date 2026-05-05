<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Billing Entity</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'billing-entity'])
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6 space-y-8">
            <!-- Section 1: Entity Information -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Entity Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="field-label">Legal Entity Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->entity_name ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Display / Trade Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->display_name ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Registration Number</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->registration_number ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Tax / VAT Number</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->tax_number ?: '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 2: Currency & Location -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Currency & Location</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="field-label">Currency Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->currency ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Currency Code</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->currency_code ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Country</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->country ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">City</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->city ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">State / Province</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->state ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Postal Code</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->postal_code ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Address Line 1</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->address_line_1 ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Address Line 2</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->address_line_2 ?: '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 3: Contact Person -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Contact Person</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="field-label">First Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->first_name ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Middle Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->middle_name ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Last Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->last_name ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-3">
                        <label class="field-label">Email</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->email ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-1 border-r border-gray-200 pr-6">
                        <label class="field-label">Phone</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->phone ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-2 pl-6">
                        <label class="field-label">Fax</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->fax ?: '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 4: Banking Details -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Banking Details</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="field-label">Bank Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->bank_name ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Account Number</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->bank_account_number ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">IBAN</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->bank_iban ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">SWIFT / BIC</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $billing?->bank_swift ?: '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 5: Notes -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Additional Information</h2>
                </div>
                <div>
                    <label class="field-label">Internal Notes</label>
                    <textarea rows="3" class="input-field pt-2 bg-gray-50 text-gray-700" readonly>{{ $billing?->notes ?: '--' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
