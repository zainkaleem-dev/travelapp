<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Add New User</h1>
                    <p class="text-[11px] text-gray-500 mt-1">Create a login account for the selected company</p>
                </div>
                <a href="{{ route('users.index', ['companyId' => $company_id]) }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Identity & Organization
                    </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @if(count($companies) > 0)
                        <div>
                            <label class="field-label">Select Company <span class="text-red-500">*</span></label>
                            <div class="relative"
                                x-data="{ open: false, selected: @entangle('company_id').live, labels: @js($companies->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span
                                        x-text="!selected ? '-- Choose Company --' : (labels[selected] ?? '-- Choose Company --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }"
                                        @click="selected = ''; open = false">-- Choose Company --</button>
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': String(selected) === '{{ $company->id }}' }"
                                            @click="selected = '{{ $company->id }}'; open = false">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                        @endif

                        <div>
                            <label class="field-label">Select Branch <span class="text-red-500">*</span></label>
                            <div wire:key="create-branch-dropdown-{{ $company_id ?? 'none' }}-{{ count($branches) }}"
                                class="relative"
                                x-data="{ open: false, selected: @entangle('branch_id').live, labels: @js(collect($branches)->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left"
                                    @click="if (!{{ empty($branches) ? 'true' : 'false' }}) open = !open"
                                    :class="{ 'opacity-60 cursor-not-allowed': {{ empty($branches) ? 'true' : 'false' }} }">
                                    <span
                                        x-text="!selected ? '-- Choose Branch --' : (labels[selected] ?? '-- Choose Branch --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }"
                                        @click="selected = ''; open = false">-- Choose Branch --</button>
                                    @foreach($branches as $branch)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': String(selected) === '{{ $branch->id }}' }"
                                            @click="selected = '{{ $branch->id }}'; open = false">{{ $branch->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @if(empty($branches) && $company_id)
                                <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">This company has no branches
                                    yet.</p>
                            @elseif(!$company_id && auth()->user()->hasRole('Super Admin'))
                                <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">Please select a company first.
                                </p>
                            @endif
                            @error('branch_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">First Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="first_name" class="input-field" placeholder="John">
                            @error('first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="input-field" placeholder="Quincy">
                            @error('middle_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="last_name" class="input-field" placeholder="Doe">
                            @error('last_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Account Security -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Account & Security</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="input-field"
                                placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div x-data="{ showPassword: false }">
                            <label class="field-label">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" wire:model="password" class="input-field pr-10"
                                    placeholder="••••••••">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-gray-400 hover:text-gray-600"
                                    :title="showPassword ? 'Hide password' : 'Show password'">
                                    <svg x-show="!showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368M9.88 9.88a3 3 0 104.24 4.24" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6.1 6.1A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.411M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
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
                    <span wire:loading.remove>Create User</span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>