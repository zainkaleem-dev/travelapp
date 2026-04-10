<div class="rounded-xl border border-gray-200 p-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <div class="field-wrap">
                <label class="field-label">Branch Name <span class="text-red-600">*</span></label>
                <input type="text" class="field-input" wire:model.defer="name" placeholder="Branch name" />
            </div>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">Branch Code <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="code" placeholder="Optional" />
            </div>
            @error('code') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">Country <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="country" placeholder="Optional" />
            </div>
            @error('country') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">City <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="city" placeholder="Optional" />
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

        <div>
            <div class="field-wrap">
                <label class="field-label">Phone <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="phone" placeholder="Optional" />
            </div>
            @error('phone') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="field-wrap">
                <label class="field-label">Email <span class="text-gray-400">(Optional)</span></label>
                <input type="email" class="field-input" wire:model.defer="email" placeholder="Optional" />
            </div>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
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
