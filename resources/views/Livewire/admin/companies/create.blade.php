<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add Company</h1>
                    <p class="text-sm text-gray-600 mt-1">Super admin only · Fields marked <span class="font-semibold text-red-600">*</span> are required</p>
                </div>
                <a href="{{ route('flights.search') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="space-y-6">
                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Company</h2>
                            <span class="text-xs font-semibold text-gray-500">Basic details</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <label class="field-label">Company Logo <span class="text-gray-400">(Optional)</span></label>
                                    @if ($company_logo)
                                        <button type="button" wire:click="$set('company_logo', null)"
                                            class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                                            Remove
                                        </button>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4">
                                    <div class="w-14 h-14 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if ($company_logo)
                                            <img src="{{ $company_logo->temporaryUrl() }}" alt="Company logo preview" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M9 3v2m6-2v2M4 8h16v13H4z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold text-gray-900">Upload logo</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Click to choose a file</div>
                                        <input type="file"
                                            class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2ab4c0] file:text-white hover:file:bg-[#229aa4]"
                                            wire:model="company_logo"
                                            accept=".jpg,.jpeg,.png,.svg,image/jpeg,image/png,image/svg+xml" />
                                        @error('company_logo') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Name <span class="text-red-600">*</span></label>
                                    <input type="text" class="field-input" wire:model.defer="company_name" placeholder="e.g. Acme Travel" />
                                </div>
                                @error('company_name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Type <span class="text-red-600">*</span></label>
                                    <select class="field-input" wire:model.defer="company_type">
                                        <option value="">Select type</option>
                                        <option value="TMC - Alma Travel">TMC - Alma Travel</option>
                                        <option value="Corporate - Nahdi">Corporate - Nahdi</option>
                                        <option value="Corporate - STC">Corporate - STC</option>
                                        <option value="TMC - Global Travel">TMC - Global Travel</option>
                                    </select>
                                </div>
                                @error('company_type') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Email <span class="text-gray-400">(Optional)</span></label>
                                    <input type="email" class="field-input" wire:model.defer="company_email" placeholder="Optional" />
                                </div>
                                @error('company_email') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Phone <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="phone" placeholder="Optional" />
                                </div>
                                @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Country <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="country" placeholder="e.g. Pakistan / UAE / PK" />
                                </div>
                                @error('country') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Admin Account</h2>
                            <span class="text-xs font-semibold text-gray-500">First user for this company</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Admin Email <span class="text-red-600">*</span></label>
                                    <input type="email" class="field-input" wire:model.defer="admin_email" placeholder="admin@company.com" />
                                </div>
                                @error('admin_email') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Admin Password <span class="text-red-600">*</span></label>
                                    <input type="password" class="field-input" wire:model.defer="admin_password" placeholder="Min 8 characters" />
                                </div>
                                @error('admin_password') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <div class="field-wrap">
                                    <label class="field-label">Admin Name <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="admin_name" placeholder="Optional" />
                                </div>
                                @error('admin_name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Limits</h2>
                            <span class="text-xs font-semibold text-gray-500">Optional controls</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Subscription Plan <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="subscription_plan" placeholder="Optional" />
                                </div>
                                @error('subscription_plan') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Limit <span class="text-gray-400">(Optional)</span></label>
                                    <input type="number" class="field-input" wire:model.defer="company_limit" placeholder="Optional" min="1" />
                                </div>
                                @error('company_limit') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-3 text-sm font-black text-white hover:bg-[#229aa4]">
                        Create Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
