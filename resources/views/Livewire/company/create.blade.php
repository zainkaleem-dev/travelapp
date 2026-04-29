@php($isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System'))
<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $isSuperAdmin ? 'Add Organization' : 'Add Partner' }}</h1>
                </div>
                <a href="{{ route('companies.index') }}"
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

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Identity & Type -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Core Identity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3">
                            <div
                                class="flex items-center gap-6 p-4 rounded-2xl border border-dashed border-gray-200 bg-white">
                                @php
                                    $companyInitials = strtoupper(
                                        collect(preg_split('/\s+/', trim((string) $company_name)))
                                            ->filter()
                                            ->take(2)
                                            ->map(fn ($word) => mb_substr($word, 0, 1))
                                            ->implode('')
                                    );
                                    $companyInitials = $companyInitials !== '' ? $companyInitials : strtoupper(mb_substr((string) $company_name, 0, 2));
                                @endphp
                                <div
                                    class="w-16 h-16 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if ($company_logo)
                                        <img src="{{ $company_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-lg font-black text-gray-500">
                                            {{ $companyInitials }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-bold text-gray-900 uppercase tracking-tight mb-1">{{ $isSuperAdmin ? 'Organization' : 'Partner' }}
                                        logo <span class="text-red-500">*</span></label>
                                    <p class="text-[11px] text-gray-500 mb-2">JPG, PNG or SVG. Max 2MB.</p>
                                    <div class="flex items-center gap-3">
                                        <label
                                            class="cursor-pointer px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50">
                                            Choose File
                                            <input type="file" wire:model="company_logo" class="hidden"
                                                accept="image/*">
                                        </label>
                                        @if ($company_logo)
                                            <button type="button" wire:click="$set('company_logo', null)"
                                                class="text-xs font-bold text-red-500 hover:text-red-600">Remove</button>
                                        @endif
                                    </div>
                                    @error('company_logo') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                        {{ $message }}
                                    </p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">{{ $isSuperAdmin ? 'Organization Name' : 'Partner Name' }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="company_name" class="input-field"
                                placeholder="Acme Travel Services">
                            @error('company_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Slug / ID <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="slug" class="input-field font-mono"
                                placeholder="acme-travel">
                            @error('slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Organization Type <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js($company_type ?? '') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span
                                        x-text="selected === '' ? 'Select type...' : (selected === 'TMC' ? 'TMC' : 'Corporate')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === '' }"
                                        @click="selected = ''; open = false; $wire.set('company_type', '')">Select
                                        type...</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'TMC' }"
                                        @click="selected = 'TMC'; open = false; $wire.set('company_type', 'TMC')">TMC</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'Corporate' }"
                                        @click="selected = 'Corporate'; open = false; $wire.set('company_type', 'Corporate')">Corporate</button>
                                </div>
                            </div>
                            @error('company_type') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Registration No. <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="registration_number" class="input-field"
                                readonly
                                Disabled
                                placeholder="12345-678">
                            @error('registration_number') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Founded Year <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="founded_year" class="input-field" placeholder="2010"
                                min="1900" max="{{ date('Y') }}">
                            @error('founded_year') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Organization Description</label>
                            <textarea wire:model="description" rows="3" class="input-field pt-2"
                                placeholder="Tell us more about this Organization..."></textarea>
                            @error('description') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Add Attachment</label>
                            <input type="file" wire:model="attachments" class="input-field !pl-3" multiple>
                            <p class="mt-1 text-[11px] text-gray-500">Maximum file size: 20MB each.</p>
                            @error('attachments') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                            @error('attachments.*') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Branches (Dynamic Repeater) -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Branches</h2>
                            <span class="px-2 py-0.5 rounded-full bg-[#2ab4c0]/10 text-[10px] font-bold text-[#2ab4c0] uppercase tracking-tight">Dynamic</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="create_branch" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#2ab4c0]"></div>
                                <span class="ml-3 text-xs font-bold text-gray-700">Enable Branching</span>
                            </label>
                            @if($create_branch)
                                <button type="button" wire:click="addBranch" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-white border border-gray-200 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Add Another Branch
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($create_branch)
                        <div class="space-y-6">
                            @foreach($branches as $index => $branch)
                                <div class="relative p-6 rounded-2xl border border-gray-100 bg-white shadow-sm animate-in fade-in slide-in-from-top-1 duration-300" wire:key="branch-{{ $index }}">
                                    @if($index > 0)
                                        <button type="button" wire:click="removeBranch({{ $index }})" class="absolute top-4 right-4 p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif

                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center text-[10px] font-black text-gray-400">{{ $index + 1 }}</span>
                                        <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">
                                            @if($index === 0) Main Branch @else Branch Details @endif
                                        </h3>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="md:col-span-2">
                                            <label class="field-label">Branch Name <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.live.debounce.500ms="branches.{{ $index }}.name" class="input-field" placeholder="Headquarters">
                                            @error('branches.'.$index.'.name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">Branch Code <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.code" class="input-field uppercase font-mono" placeholder="HQ001">
                                            @error('branches.'.$index.'.code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">Branch Slug <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.slug" class="input-field font-mono" placeholder="headquarters">
                                            @error('branches.'.$index.'.slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">Contact Email <span class="text-red-500">*</span></label>
                                            <input type="email" wire:model="branches.{{ $index }}.email" class="input-field" placeholder="hq@acme.com">
                                            @error('branches.'.$index.'.email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">Contact Phone <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.phone" class="input-field" placeholder="+123456789">
                                            @error('branches.'.$index.'.phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="field-label">Address Line 1 <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.address_line_1" class="input-field" placeholder="123 Business St">
                                            @error('branches.'.$index.'.address_line_1') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">City <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.city" class="input-field" placeholder="London">
                                            @error('branches.'.$index.'.city') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">State/Province <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.state" class="input-field" placeholder="Greater London">
                                            @error('branches.'.$index.'.state') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="field-label">Country <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="branches.{{ $index }}.country" class="input-field" placeholder="United Kingdom">
                                            @error('branches.'.$index.'.country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addBranch" class="w-full flex items-center justify-center gap-2 py-4 border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 hover:text-[#2ab4c0] hover:border-[#2ab4c0] hover:bg-[#f2feff] transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-xs font-black uppercase tracking-widest">Add Another Branch</span>
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Branching is disabled</p>
                        </div>
                    @endif
                </div>

                <!-- Section 3: Internal Notes & Status -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Status <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js($status ?? 'active') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span x-text="selected.charAt(0).toUpperCase() + selected.slice(1)"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'active' }"
                                        @click="selected = 'active'; open = false; $wire.set('status', 'active')">Active</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'inactive' }"
                                        @click="selected = 'inactive'; open = false; $wire.set('status', 'inactive')">Inactive</button>
                                </div>
                            </div>
                            @error('status') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="field-label">Internal Notes</label>
                            <textarea wire:model="notes" rows="3" class="input-field pt-2"
                                placeholder="Private internal notes..."></textarea>
                            @error('notes') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="history.back()"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    {{ $isSuperAdmin ? 'Create Organization' : 'Create Partner' }}
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
