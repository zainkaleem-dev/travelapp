<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Create Travel Policy</h1>
                </div>
                <a href="{{ route('admin.system-settings', ['activeTab' => 'travel-policy']) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 hover:bg-gray-50 uppercase tracking-wider transition-colors">
                    Back to List
                </a>
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Policy Configuration</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Policy Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="input-field" placeholder="e.g. Executive Flight Policy">
                            @error('name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Company <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js($companyId ?: ''), companies: @js($companies->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span x-text="selected === '' ? 'Select company...' : companies[selected]"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel max-h-64 overflow-y-auto">
                                    <button type="button" class="admin-menu-item" @click="selected = ''; open = false; $wire.set('companyId', '')">Select company...</button>
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': selected == '{{ $company->id }}' }"
                                            @click="selected = '{{ $company->id }}'; open = false; $wire.set('companyId', '{{ $company->id }}')">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('companyId') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Policy Type <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js($policyType) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left uppercase" @click="open = !open">
                                    <span x-text="selected"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    @foreach($policyTypes as $type)
                                        <button type="button" class="admin-menu-item uppercase" :class="{ 'is-active': selected === '{{ $type }}' }"
                                            @click="selected = '{{ $type }}'; open = false; $wire.set('policyType', '{{ $type }}')">{{ $type }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('policyType') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Description</label>
                            <textarea wire:model="description" rows="3" class="input-field pt-2" placeholder="Policy details and rules..."></textarea>
                            @error('description') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Status</label>
                            <div class="flex items-center gap-3 mt-2">
                                <button type="button" wire:click="$set('isActive', true)" 
                                    class="flex-1 px-4 py-2 rounded-lg border text-[11px] font-bold uppercase transition-all {{ $isActive ? 'bg-green-50 border-green-200 text-green-700' : 'bg-white border-gray-200 text-gray-400 hover:border-gray-300' }}">
                                    Active
                                </button>
                                <button type="button" wire:click="$set('isActive', false)" 
                                    class="flex-1 px-4 py-2 rounded-lg border text-[11px] font-bold uppercase transition-all {{ !$isActive ? 'bg-red-50 border-red-200 text-red-700' : 'bg-white border-gray-200 text-gray-400 hover:border-gray-300' }}">
                                    Inactive
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.system-settings', ['activeTab' => 'travel-policy']) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    Create Policy
                </button>
            </div>
        </form>
    </div>
</div>
