<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add Branch</h1>
                    <p class="text-xs text-gray-500 mt-1">Create a new branch for a company</p>
                </div>
                <a href="{{ route('superadmin.branches') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back to List
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

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Branch Identity</h2>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#2ab4c0]/10 text-[#2ab4c0] uppercase tracking-tighter">Required</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Branch Name <span class="text-[#2ab4c0]">*</span></label>
                            <input type="text" wire:model.live.debounce.500ms="name" class="field-input" placeholder="e.g. Dubai Main Office">
                            @error('name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Slug / URL <span class="text-[#2ab4c0]">*</span></label>
                            <input type="text" wire:model="slug" class="field-input bg-gray-50 font-mono text-xs" placeholder="dubai-office">
                            @error('slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Branch Code <span class="text-[#2ab4c0]">*</span></label>
                            <input type="text" wire:model="code" class="field-input" placeholder="e.g. DXB-001">
                            @error('code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Parent Company <span class="text-[#2ab4c0]">*</span></label>
                            <select wire:model="company_id" class="field-input">
                                <option value="">Select Company...</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Status</label>
                            <select wire:model="status" class="field-input">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_main" class="w-4 h-4 rounded border-gray-300 text-[#2ab4c0] focus:ring-[#2ab4c0]">
                                <span class="text-sm font-semibold text-gray-700">Set as Main Branch for this Company</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Information -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Contact Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" wire:model="email" class="field-input" placeholder="branch@company.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Primary Phone</label>
                            <input type="text" wire:model="phone" class="field-input" placeholder="+1234567890">
                            @error('phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Secondary Phone</label>
                            <input type="text" wire:model="phone_secondary" class="field-input" placeholder="+1234567890">
                            @error('phone_secondary') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Fax Number</label>
                            <input type="text" wire:model="fax" class="field-input" placeholder="+1234567890">
                            @error('fax') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">WhatsApp</label>
                            <input type="text" wire:model="whatsapp" class="field-input" placeholder="+1234567890">
                            @error('whatsapp') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Physical Address -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Location & Address</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Address Line 1</label>
                            <input type="text" wire:model="address_line_1" class="field-input" placeholder="Street address, P.O. box">
                            @error('address_line_1') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Address Line 2</label>
                            <input type="text" wire:model="address_line_2" class="field-input" placeholder="Apartment, suite, unit, building, floor">
                            @error('address_line_2') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">City</label>
                            <input type="text" wire:model="city" class="field-input" placeholder="e.g. Dubai">
                            @error('city') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">State / Province</label>
                            <input type="text" wire:model="state" class="field-input" placeholder="e.g. Dubai">
                            @error('state') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="field-input" placeholder="e.g. 12345">
                            @error('postal_code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Country</label>
                            <input type="text" wire:model="country" class="field-input" placeholder="e.g. United Arab Emirates">
                            @error('country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Latitude</label>
                            <input type="text" wire:model="latitude" class="field-input font-mono text-xs" placeholder="e.g. 25.2048">
                            @error('latitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Longitude</label>
                            <input type="text" wire:model="longitude" class="field-input font-mono text-xs" placeholder="e.g. 55.2708">
                            @error('longitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Internal Notes</label>
                            <textarea wire:model="notes" rows="3" class="field-input pt-2" placeholder="Private internal notes about this branch..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-6 py-3 text-xs font-black text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-[0.999rem] bg-[#2ab4c0] px-3 py-2 text-[13px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    <span wire:loading.remove>Save Branch</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>