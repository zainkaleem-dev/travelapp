<div class="w-full px-1 py-1 flex flex-col gap-3">
    <!-- Header & Navigation Container -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Edit Organization' : 'Edit Partner' }}</h1>
                </div>
                <a href="{{ route('companies.index') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Back
                </a>
            </div>
        </div>

        @include('partials.navigation-company-edit', ['companyId' => $companyId, 'activeTab' => 'billing-entity'])
    </div>

    <!-- Main Content Container -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        @if (session('status'))
            <div class="px-6 pt-6">
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] text-green-800 uppercase font-semibold">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="px-6 pt-6">
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-[11px] text-red-800">
                    <p class="font-bold uppercase text-[11px] mb-2">Please fix the following errors:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Entity Information -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Entity Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Legal Entity Name</label>
                            <input type="text" wire:model="entity_name" class="input-field" placeholder="Acme Corp Ltd.">
                            @error('entity_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Display / Trade Name</label>
                            <input type="text" wire:model="display_name" class="input-field" placeholder="Acme">
                            @error('display_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Registration Number</label>
                            <input type="text" wire:model="registration_number" class="input-field" placeholder="12345678">
                            @error('registration_number') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Tax / VAT Number</label>
                            <input type="text" wire:model="tax_number" class="input-field" placeholder="VAT123456">
                            @error('tax_number') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Currency & Location -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Currency & Location</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Currency Name</label>
                            <input type="text" wire:model="currency" class="input-field" placeholder="US Dollar">
                            @error('currency') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Currency Code</label>
                            <input type="text" wire:model="currency_code" class="input-field" placeholder="USD">
                            @error('currency_code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Country</label>
                            <input type="text" wire:model="country" class="input-field" placeholder="United States">
                            @error('country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">City</label>
                            <input type="text" wire:model="city" class="input-field" placeholder="New York">
                            @error('city') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">State / Province</label>
                            <input type="text" wire:model="state" class="input-field" placeholder="NY">
                            @error('state') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="input-field" placeholder="10001">
                            @error('postal_code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Address Line 1</label>
                            <input type="text" wire:model="address_line_1" class="input-field" placeholder="123 Business Ave">
                            @error('address_line_1') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Address Line 2</label>
                            <input type="text" wire:model="address_line_2" class="input-field" placeholder="Suite 400 (Optional)">
                            @error('address_line_2') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Contact Person -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Contact Person</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">First Name</label>
                            <input type="text" wire:model="first_name" class="input-field" placeholder="John">
                            @error('first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="input-field" placeholder="H. (Optional)">
                            @error('middle_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Last Name</label>
                            <input type="text" wire:model="last_name" class="input-field" placeholder="Doe">
                            @error('last_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Email</label>
                            <input type="email" wire:model="email" class="input-field" placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-1 border-r border-gray-200 pr-6">
                            <label class="field-label">Phone</label>
                            <input type="text" wire:model="phone" class="input-field" placeholder="+1 555-1234">
                            @error('phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 pl-6">
                            <label class="field-label">Fax</label>
                            <input type="text" wire:model="fax" class="input-field" placeholder="+1 555-5678 (Optional)">
                            @error('fax') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Banking Details -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Banking Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Bank Name</label>
                            <input type="text" wire:model="bank_name" class="input-field" placeholder="Global Bank Inc.">
                            @error('bank_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Account Number</label>
                            <input type="text" wire:model="bank_account_number" class="input-field" placeholder="1234567890">
                            @error('bank_account_number') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">IBAN</label>
                            <input type="text" wire:model="bank_iban" class="input-field" placeholder="US12GLBK1234567890">
                            @error('bank_iban') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">SWIFT / BIC</label>
                            <input type="text" wire:model="bank_swift" class="input-field" placeholder="GLBKUS33">
                            @error('bank_swift') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Notes -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Additional Information</h2>
                    </div>
                    <div>
                        <label class="field-label">Internal Notes</label>
                        <textarea wire:model="notes" rows="3" class="input-field pt-2" placeholder="Private internal notes..."></textarea>
                        @error('notes') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
