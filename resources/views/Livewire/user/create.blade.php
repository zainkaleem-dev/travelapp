<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add New User</h1>
                    <p class="text-xs text-gray-500 mt-1">Create a login account for the selected company</p>
                </div>
                <a href="{{ route('superadmin.users') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back to List
                </a>
            </div>
        </div>
 
        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Identity & Name</h2>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#2ab4c0]/10 text-[#2ab4c0] uppercase tracking-tighter">Required</span>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">First Name <span class="text-[#2ab4c0]">*</span></label>
                            <input type="text" wire:model="first_name" class="field-input" placeholder="e.g. John">
                            @error('first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
 
                        <div>
                            <label class="field-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="field-input" placeholder="e.g. Quincy">
                            @error('middle_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
 
                        <div>
                            <label class="field-label">Last Name <span class="text-[#2ab4c0]">*</span></label>
                            <input type="text" wire:model="last_name" class="field-input" placeholder="e.g. Doe">
                            @error('last_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
 
                <!-- Section 2: Account Security -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Account & Security</h2>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Email Address <span class="text-[#2ab4c0]">*</span></label>
                            <input type="email" wire:model="email" class="field-input" placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
 
                        <div>
                            <label class="field-label">Password <span class="text-[#2ab4c0]">*</span></label>
                            <input type="password" wire:model="password" class="field-input" placeholder="••••••••">
                            @error('password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
 
                    </div>
                </div>
            </div>
 
            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-xs font-black text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-[#2ab4c0] px-10 py-3 text-xs font-black text-white hover:bg-[#229aa4] shadow-lg shadow-[#2ab4c0]/20 transition-all uppercase tracking-widest active:scale-95">
                    <span wire:loading.remove>Create User</span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
