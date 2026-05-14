<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Edit Grade</h1>
                    <p class="text-[11px] text-gray-500 mt-1">Modify the existing grade or position</p>
                </div>
                <a href="{{ route('grades.index', ['companyId' => $companyId]) }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <form wire:submit.prevent="update" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Basic Information -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Grade Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="field-label">Select Department</label>
                            <div class="relative"
                                x-data="{ open: false, selected: @entangle('department_id').live, labels: @js(collect($departments)->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span x-text="!selected ? '-- Choose Department --' : (labels[selected] ?? '-- Choose Department --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }" @click="selected = ''; open = false">-- Choose Department --</button>
                                    @foreach($departments as $department)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': String(selected) === '{{ $department->id }}' }" @click="selected = '{{ $department->id }}'; open = false">{{ $department->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('department_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Grade Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="input-field" placeholder="e.g. Senior Manager">
                            @error('name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 mb-6">
                        <div>
                            <label class="field-label">Description</label>
                            <textarea wire:model="description" class="input-field min-h-[100px] py-3" placeholder="Brief description of this grade..."></textarea>
                            @error('description') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full sm:w-1/3">
                            <label class="field-label">Status <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @entangle('status').live }" @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left capitalize" @click="open = !open">
                                    <span x-text="selected"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === 'active' }" @click="selected = 'active'; open = false">Active</button>
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === 'inactive' }" @click="selected = 'inactive'; open = false">Inactive</button>
                                </div>
                            </div>
                            @error('status') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    <span wire:loading.remove>Update Grade</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
