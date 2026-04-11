<div>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight truncate">My Sub Company</h1>
                        <p class="text-sm text-gray-600 mt-1 truncate">{{ $subCompany->company?->name ?? 'Company' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('subcompany.branches.index') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                            Branches
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="relative rounded-xl border border-gray-200 p-5">
                    <button type="button" wire:click="openEdit"
                        class="absolute right-4 top-4 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-black text-gray-700 hover:bg-gray-50"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <div class="text-lg font-black text-gray-900">{{ $subCompany->name }}</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $subCompany->code }}</div>
                    <div class="mt-3 text-sm text-gray-700">
                        {{ collect([$subCompany->address, $subCompany->city, $subCompany->country])->filter()->implode(', ') ?: '—' }}
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-md {{ $subCompany->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2 py-0.5 text-[11px] font-black">
                            {{ $subCompany->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if ($subCompany->email)
                            <span class="inline-flex items-center rounded-md bg-gray-100 text-gray-800 px-2 py-0.5 text-[11px] font-black">
                                {{ $subCompany->email }}
                            </span>
                        @endif
                        @if ($subCompany->phone)
                            <span class="inline-flex items-center rounded-md bg-gray-100 text-gray-800 px-2 py-0.5 text-[11px] font-black">
                                {{ $subCompany->phone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($editModalOpen)
    <div class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40" wire:click="closeEdit"></div>
        <div class="relative h-full w-full overflow-y-auto px-4 py-8">
            <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-black text-gray-900">Edit Sub Company</div>
                            <div class="text-xs text-gray-600 mt-0.5">{{ $subCompany->company?->name ?? '' }}</div>
                        </div>
                        <button type="button" wire:click="closeEdit"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="updateSubCompany" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <div class="field-wrap">
                                <label class="field-label">Name <span class="text-red-600">*</span></label>
                                <input type="text" class="field-input" wire:model.defer="name" placeholder="Sub company name" />
                            </div>
                            @error('name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Code <span class="text-red-600">*</span></label>
                                <input type="text" class="field-input" wire:model.defer="code" placeholder="Code" />
                            </div>
                            @error('code') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Email <span class="text-red-600">*</span></label>
                                <input type="email" class="field-input" wire:model.defer="email" placeholder="Email" />
                            </div>
                            @error('email') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Country <span class="text-red-600">*</span></label>
                                <input type="text" class="field-input" wire:model.defer="country" placeholder="Country" />
                            </div>
                            @error('country') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">City <span class="text-red-600">*</span></label>
                                <input type="text" class="field-input" wire:model.defer="city" placeholder="City" />
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
                        <div class="md:col-span-2">
                            <div class="field-wrap">
                                <label class="field-label">Phone <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" class="field-input" wire:model.defer="phone" placeholder="Optional" />
                            </div>
                            @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeEdit"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
