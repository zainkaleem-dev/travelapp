<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit User</h1>
                    <p class="text-xs text-gray-500 mt-1">Update account details for <span class="font-bold text-gray-700">{{ $user->display_name }}</span></p>
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
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Identity & Organization</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @role('Super Admin')
                        <div>
                            <label class="field-label">Select Company <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js((string) ($company_id ?? '')), labels: @js($companies->pluck('name', 'id')) }" @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="open = !open">
                                    <span x-text="selected === '' ? '-- Choose Company --' : (labels[selected] ?? '-- Choose Company --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click="selected = ''; open = false; $wire.set('company_id', '')">-- Choose Company --</button>
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '{{ $company->id }}' }" @click="selected = '{{ $company->id }}'; open = false; $wire.set('company_id', '{{ $company->id }}')">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                        @endrole

                        <div @unless(auth()->user()->hasRole('Super Admin')) class="col-span-2" @endunless>
                            <label class="field-label">Select Branch <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js((string) ($branch_id ?? '')), labels: @js(collect($branches)->pluck('name', 'id')) }" @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn" @click="if (!{{ empty($branches) ? 'true' : 'false' }}) open = !open" :class="{ 'opacity-60 cursor-not-allowed': {{ empty($branches) ? 'true' : 'false' }} }">
                                    <span x-text="selected === '' ? '-- Choose Branch --' : (labels[selected] ?? '-- Choose Branch --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click="selected = ''; open = false; $wire.set('branch_id', '')">-- Choose Branch --</button>
                                    @foreach($branches as $branch)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '{{ $branch->id }}' }" @click="selected = '{{ $branch->id }}'; open = false; $wire.set('branch_id', '{{ $branch->id }}')">{{ $branch->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @if(empty($branches) && $company_id)
                            <p class="mt-1 text-[11px] font-bold text-orange-500 uppercase">This company has no branches yet.</p>
                            @elseif(!$company_id && auth()->user()->hasRole('Super Admin'))
                            <p class="mt-1 text-[11px] font-bold text-gray-400 uppercase">Please select a company first.</p>
                            @endif
                            @error('branch_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">First Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="first_name" class="input-field" placeholder="e.g. John">
                            @error('first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="input-field" placeholder="e.g. Quincy">
                            @error('middle_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="last_name" class="input-field" placeholder="e.g. Doe">
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
                            <label class="field-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="input-field" placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>


                        <div class="md:col-span-2 pt-4 border-t border-gray-100 mt-4">
                            <label class="field-label">Change Password</label>
                            <input type="password" wire:model="password" class="input-field" placeholder="Leave blank to keep current password">
                            <p class="mt-1.5 text-[10px] text-gray-500 font-medium">Only fill this if you want to reset the user's password.</p>
                            @error('password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
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
                    <span wire:loading.remove>Update User</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>