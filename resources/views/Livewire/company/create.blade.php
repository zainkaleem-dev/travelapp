<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add Company</h1>

                </div>
                <a href="{{ route('flights.search') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="px-6 py-4">
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            </div>
        @endif


                <div class="space-y-8">
                    <!-- Section 1: Identity & Type -->
                    <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Core Identity</h2>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#2ab4c0]/10 text-[#2ab4c0] uppercase tracking-tighter">Required</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-3">
                                <div class="flex items-center gap-6 p-4 rounded-2xl border border-dashed border-gray-200 bg-white">
                                    <div class="w-16 h-16 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if ($company_logo)
                                            <img src="{{ $company_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-tight mb-1">Company logo</label>
                                        <p class="text-[11px] text-gray-500 mb-2">JPG, PNG or SVG. Max 2MB.</p>
                                        <div class="flex items-center gap-3">
                                            <label class="cursor-pointer px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50">
                                                Choose File
                                                <input type="file" wire:model="company_logo" class="hidden" accept="image/*">
                                            </label>
                                            @if ($company_logo)
                                                <button type="button" wire:click="$set('company_logo', null)" class="text-xs font-bold text-red-500 hover:text-red-600">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="field-label">Company Name <span class="text-[#2ab4c0]">*</span></label>
                                <input type="text" wire:model.debounce.500ms="company_name" class="field-input" placeholder="e.g. Acme Travel Services">
                                @error('company_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }} @enderror
                            </div>

                            <div>
                                <label class="field-label">Slug / ID <span class="text-[#2ab4c0]">*</span></label>
                                <input type="text" wire:model="slug" class="field-input bg-gray-50 font-mono text-xs" placeholder="acme-travel">
                                @error('slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }} @enderror
                            </div>

                            <div>
                                <label class="field-label">Company Type</label>
                                <select wire:model="company_type" class="field-input">
                                    <option value="">Select type...</option>
                                    <option value="TMC">TMC (Travel Management)</option>
                                    <option value="Corporate">Corporate Client</option>
                                </select>
                            </div>

                            <div>
                                <label class="field-label">Registration No.</label>
                                <input type="text" wire:model="registration_number" class="field-input" placeholder="e.g. 12345-678">
                            </div>

                            <div>
                                <label class="field-label">Tax ID / VAT</label>
                                <input type="text" wire:model="tax_number" class="field-input" placeholder="e.g. GB12345678">
                            </div>

                            <div>
                                <label class="field-label">Founded Year</label>
                                <input type="number" wire:model="founded_year" class="field-input" placeholder="e.g. 2010" min="1900" max="{{ date('Y') }}">
                            </div>

                            <div class="md:col-span-3">
                                <label class="field-label">Company Description</label>
                                <textarea wire:model="description" rows="3" class="field-input pt-2" placeholder="Tell us more about this company..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Internal Notes & Status -->
                    <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="field-label">Status</label>
                                <select wire:model="status" class="field-input">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="field-label">Internal Notes</label>
                                <textarea wire:model="notes" rows="3" class="field-input pt-2" placeholder="Private internal notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Admin User -->
                    <div class="rounded-xl border border-[#2ab4c0]/20 bg-[#2ab4c0]/5 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-black tracking-widest text-[#2ab4c0] uppercase">Initial Administrator</h2>
                            <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="field-label">Full Name</label>
                                <input type="text" wire:model="admin_name" class="field-input" placeholder="Full name of admin">
                            </div>
                            <div>
                                <label class="field-label">Email Address <span class="text-[#2ab4c0]">*</span></label>
                                <input type="email" wire:model="admin_email" class="field-input" placeholder="admin@company.com">
                                @error('admin_email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }} @enderror
                            </div>
                            <div>
                                <label class="field-label">Access Password <span class="text-[#2ab4c0]">*</span></label>
                                <input type="password" wire:model="admin_password" class="field-input" placeholder="••••••••">
                                @error('admin_password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                    <button type="button" onclick="history.back()"
                        class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-xs font-black text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                        Cancel
                    </button>
                    <button type="submit" wire:click="save"
                        class="inline-flex items-center justify-center rounded-xl bg-[#2ab4c0] px-10 py-3 text-xs font-black text-white hover:bg-[#229aa4] shadow-lg shadow-[#2ab4c0]/20 transition-all uppercase tracking-widest active:scale-95">
                        Create Company
                    </button>
                </div>

    </div>
</div>
