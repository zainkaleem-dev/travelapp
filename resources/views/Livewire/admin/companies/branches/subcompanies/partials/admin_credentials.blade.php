<div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
    <div class="text-sm font-black text-gray-900">Sub Company Admin Credentials</div>
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="field-wrap">
                <label class="field-label">Admin Name <span class="text-gray-400">(Optional)</span></label>
                <input type="text" class="field-input" wire:model.defer="admin_name" placeholder="Optional" />
            </div>
            @error('admin_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div>
            <div class="field-wrap">
                <label class="field-label">Admin Email <span class="text-red-600">*</span></label>
                <input type="email" class="field-input" wire:model.defer="admin_email" placeholder="admin@example.com" />
            </div>
            @error('admin_email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="md:col-span-2">
            <div class="field-wrap">
                <label class="field-label">Admin Password <span class="text-red-600">*</span></label>
                <input type="password" class="field-input" wire:model.defer="admin_password" placeholder="Minimum 8 characters" />
            </div>
            @error('admin_password') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

