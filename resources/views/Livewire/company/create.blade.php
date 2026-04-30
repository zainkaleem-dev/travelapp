<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Add Organization' : 'Add Partner' }}</h1>
                </div>
                <a href="{{ route('companies.index') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        @include('partials.navigation-company-create', ['companyId' => null, 'activeTab' => 'info'])

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
                                            {{ $companyInitials ?? strtoupper(mb_substr((string) ($company_name ?? ''), 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-bold text-gray-900 uppercase tracking-tight mb-1">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization' : 'Partner' }}
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
                            <label class="field-label">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Name' : 'Partner Name' }} <span class="text-red-500">*</span></label>
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
                            <label class="field-label">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Type' : 'Partner Type' }} <span class="text-red-500">*</span></label>
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
                                    @if(count($allowed_types) > 1)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': selected === '' }"
                                            @click="selected = ''; open = false; $wire.set('company_type', '')">Select
                                            type...</button>
                                    @endif
                                    @foreach($allowed_types as $type)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': selected === '{{ $type }}' }"
                                            @click="selected = '{{ $type }}'; open = false; $wire.set('company_type', '{{ $type }}')">{{ $type }}</button>
                                    @endforeach
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
                            <label class="field-label">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Description' : 'Partner Description' }}</label>
                            <textarea wire:model="description" rows="3" class="input-field pt-2"
                                placeholder="Tell us more about this {{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization' : 'Partner' }}..."></textarea>
                            @error('description') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Attachments</label>
                            
                            <!-- Upload Area -->
                            <div 
                                class="relative group cursor-pointer rounded-xl border-2 border-dashed border-gray-200 bg-white p-6 transition-all hover:border-[#2ab4c0] hover:bg-[#f2feff]/30"
                                x-data="{ isUploading: false, progress: 0 }"
                                x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <input type="file" wire:model="attachments" class="absolute inset-0 z-10 opacity-0 cursor-pointer" multiple>
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900">Click or drag to upload</p>
                                    <p class="text-[11px] text-gray-500 mt-1 uppercase tracking-tight">PDF, DOC, JPG or PNG (Max 20MB)</p>
                                </div>

                                <!-- Upload Progress -->
                                <div x-show="isUploading" class="mt-4">
                                    <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#2ab4c0] transition-all duration-300" :style="`width: ${progress}%` font-family: Inter, sans-serif;"></div>
                                    </div>
                                    <p class="text-[10px] font-bold text-[#2ab4c0] mt-2 uppercase text-center" x-text="`Uploading... ${progress}%`"></p>
                                </div>
                            </div>

                            @error('attachments') <p class="mt-2 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                            @error('attachments.*') <p class="mt-2 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror

                            <!-- File List -->
                            @if(count($attachments) > 0)
                                <div class="mt-6 space-y-3">
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Selected Files</h3>
                                    @foreach($attachments as $index => $file)
                                        <div class="group relative flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:border-[#2ab4c0] transition-all shadow-sm hover:shadow-md">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#f2feff]">
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0" x-data="{ editing: false, newName: '{{ $attachmentNames[$index] ?? $file->getClientOriginalName() }}' }">
                                                <div class="flex items-center gap-2" x-show="!editing">
                                                    <p class="text-sm font-bold text-gray-900 truncate tracking-tight">{{ $attachmentNames[$index] ?? $file->getClientOriginalName() }}</p>
                                                    <button type="button" @click="editing = true" class="text-gray-400 hover:text-[#2ab4c0]">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="flex items-center gap-2" x-show="editing" @click.outside="editing = false">
                                                    <input type="text" x-model="newName" class="text-sm font-bold text-gray-900 border-b border-[#2ab4c0] bg-transparent focus:outline-none py-0 px-0" 
                                                        @keydown.enter.prevent="$wire.renameAttachment({{ $index }}, newName); editing = false">
                                                    <button type="button" @click="$wire.renameAttachment({{ $index }}, newName); editing = false" class="text-[#2ab4c0]">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                </div>
                                                <p class="text-[11px] text-gray-500 uppercase tracking-tight">{{ number_format($file->getSize() / 1024, 1) }} KB • {{ strtoupper($file->getClientOriginalExtension()) }}</p>
                                            </div>

                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button type="button" wire:click="downloadAttachment({{ $index }})" class="p-2 text-gray-400 hover:text-[#2ab4c0] hover:bg-gray-50 rounded-lg transition-all" title="Download">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                </button>
                                                <button type="button" wire:click="removeAttachment({{ $index }})" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Remove">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
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
                    {{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Create Organization' : 'Create Partner' }}
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
