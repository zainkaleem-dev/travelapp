<div class="rounded-xl border border-gray-200 p-5 space-y-6">
    <div>
        <div class="flex items-center justify-between gap-3 mb-2">
            <label class="field-label">Logo <span class="text-gray-400">(Optional)</span></label>
            @if ($logo || $existing_logo_path)
                <button type="button" wire:click="removeLogo"
                    class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                    Remove
                </button>
            @endif
        </div>

        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4">
            <div class="w-14 h-14 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                @if ($logo)
                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="w-full h-full object-cover">
                @elseif ($existing_logo_path)
                    <img src="{{ '/storage/' . ltrim($existing_logo_path, '/') }}" alt="Logo" class="w-full h-full object-contain p-1">
                @else
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h18M9 3v2m6-2v2M4 8h16v13H4z" />
                    </svg>
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <div class="text-sm font-semibold text-gray-900">Upload logo</div>
                <div class="text-xs text-gray-500 mt-0.5">JPG, PNG, or SVG · Max 2MB</div>
                <input type="file"
                    class="mt-2 block w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#2ab4c0] file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-[#229aa4]"
                    wire:model="logo" accept=".jpg,.jpeg,.png,.svg" />
                @error('logo') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <div class="field-wrap">
                <label class="field-label">Company Name <span class="text-red-600">*</span></label>
                <input type="text" class="field-input" wire:model.defer="name" placeholder="Sub company name" />
            </div>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">Company Code <span class="text-red-600">*</span></label>
                <input type="text" class="field-input" wire:model.defer="code" placeholder="Code" />
            </div>
            @error('code') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">Email <span class="text-red-600">*</span></label>
                <input type="email" class="field-input" wire:model.defer="email" placeholder="Email" />
            </div>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
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
                <label class="field-label">Country <span class="text-red-600">*</span></label>
                <input type="text" class="field-input" wire:model.defer="country" placeholder="Country" />
            </div>
            @error('country') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">City <span class="text-red-600">*</span></label>
                <input type="text" class="field-input" wire:model.defer="city" placeholder="City" />
            </div>
            @error('city') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <div class="field-wrap">
                <label class="field-label">Address <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="address" placeholder="Optional" />
            </div>
            @error('address') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <div class="field-wrap">
                <label class="field-label">Status <span class="text-red-600">*</span></label>
                <select class="field-input" wire:model.defer="is_active">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            @error('is_active') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

