<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight truncate">Branches</h1>
                    <p class="text-sm text-gray-600 mt-1 truncate">{{ $company->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.companies.index') }}"
                        class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-black text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                    <button type="button" wire:click="openCreate"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                        Add Branch
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
                @forelse ($branches as $branch)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow {{ $branch->is_active ? '' : 'opacity-60 grayscale' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-base font-black text-gray-900 truncate">{{ $branch->name }}</div>
                                <div class="mt-1 flex items-center gap-2">
                                    @if ($branch->code)
                                        <div class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-bold text-gray-700">
                                            {{ $branch->code }}
                                        </div>
                                    @endif
                                    <div class="inline-flex items-center rounded-md {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2 py-0.5 text-[11px] font-black">
                                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 whitespace-nowrap pt-1">
                                {{ optional($branch->created_at)->format('Y-m-d') }}
                            </div>
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-gray-700">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold tracking-wide uppercase text-gray-500">Email</span>
                                <span class="font-semibold text-gray-900 truncate">{{ $branch->email ?: '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold tracking-wide uppercase text-gray-500">Phone</span>
                                <span class="font-semibold text-gray-900 truncate">{{ $branch->phone ?: '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold tracking-wide uppercase text-gray-500">Country</span>
                                <span class="font-semibold text-gray-900 truncate">{{ $branch->country ?: '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold tracking-wide uppercase text-gray-500">City</span>
                                <span class="font-semibold text-gray-900 truncate">{{ $branch->city ?: '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold tracking-wide uppercase text-gray-500">Address</span>
                                <span class="font-semibold text-gray-900 truncate">{{ $branch->address ?: '—' }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <button type="button" wire:click="openEdit({{ $branch->id }})"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-black text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>

                            <button type="button" wire:click="toggleActive({{ $branch->id }})"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-black {{ $branch->is_active ? 'bg-gray-900 text-white hover:bg-black' : 'bg-[#2ab4c0] text-white hover:bg-[#229aa4]' }}">
                                {{ $branch->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 py-10 text-center text-gray-500">
                        No branches found.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    @if ($createModalOpen)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" wire:click="closeCreate"></div>
            <div class="relative h-full w-full overflow-y-auto px-4 py-8">
                <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-black text-gray-900">Add Branch</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $company->name }}</div>
                            </div>
                            <button type="button" wire:click="closeCreate"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="createBranch" class="p-6 space-y-6">
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
                                        <label class="field-label">Status</label>
                                        <select class="field-input" wire:model.defer="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    @error('is_active') <span class="field-error">{{ $message }}</span> @enderror
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
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeCreate"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                                Save Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($editModalOpen)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" wire:click="closeEdit"></div>
            <div class="relative h-full w-full overflow-y-auto px-4 py-8">
                <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-black text-gray-900">Edit Branch</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $company->name }}</div>
                            </div>
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="updateBranch" class="p-6 space-y-6">
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
                                        <label class="field-label">Status</label>
                                        <select class="field-input" wire:model.defer="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    @error('is_active') <span class="field-error">{{ $message }}</span> @enderror
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
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                                Update Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

