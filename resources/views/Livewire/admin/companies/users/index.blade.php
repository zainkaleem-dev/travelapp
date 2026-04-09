<div>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Users</h1>
                        <p class="text-sm text-gray-600 mt-1">Users for selected company</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block">
                            <div class="field-wrap !py-2 !px-3">
                                <input type="text" class="field-input" wire:model.live.debounce.300ms="search" placeholder="Search users..." />
                            </div>
                        </div>
                        <button type="button" wire:click="openCreate"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                            Add User
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

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ trim(implode(' ', array_filter([$user->first_name, $user->middle_name, $user->last_name]))) ?: '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500">#{{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click="openEdit({{ $user->id }})"
                                                class="w-9 h-9 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-700 hover:bg-gray-50"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 013.182 3.182L8.25 19.463 4.5 19.5l.037-3.75L16.862 4.487z" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                wire:click="delete({{ $user->id }})"
                                                wire:confirm="Delete this user?"
                                                class="w-9 h-9 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-red-600 hover:bg-red-50"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5h6v2m-8 0l1 14h8l1-14" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @if ($createModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="text-sm font-black text-gray-900 uppercase tracking-wide">Add User</div>
                    <button type="button" wire:click="closeCreate" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <form wire:submit.prevent="create" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">First Name</label>
                                <input type="text" class="field-input" wire:model.defer="first_name" />
                            </div>
                            @error('first_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Middle Name</label>
                                <input type="text" class="field-input" wire:model.defer="middle_name" />
                            </div>
                            @error('middle_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Last Name</label>
                                <input type="text" class="field-input" wire:model.defer="last_name" />
                            </div>
                            @error('last_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Email <span class="text-red-600">*</span></label>
                                <input type="email" class="field-input" wire:model.defer="email" />
                            </div>
                            @error('email') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <div class="field-wrap">
                                <label class="field-label">Password <span class="text-red-600">*</span></label>
                                <input type="password" class="field-input" wire:model.defer="password" placeholder="Min 8 characters" />
                            </div>
                            @error('password') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-3 text-sm font-black text-white hover:bg-[#229aa4]">
                            Create User
                        </button>
                        <button type="button" wire:click="closeCreate"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if ($editModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="text-sm font-black text-gray-900 uppercase tracking-wide">Edit User</div>
                    <button type="button" wire:click="closeEdit" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <form wire:submit.prevent="update" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">First Name</label>
                                <input type="text" class="field-input" wire:model.defer="first_name" />
                            </div>
                            @error('first_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Middle Name</label>
                                <input type="text" class="field-input" wire:model.defer="middle_name" />
                            </div>
                            @error('middle_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Last Name</label>
                                <input type="text" class="field-input" wire:model.defer="last_name" />
                            </div>
                            @error('last_name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Email <span class="text-red-600">*</span></label>
                                <input type="email" class="field-input" wire:model.defer="email" />
                            </div>
                            @error('email') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <div class="field-wrap">
                                <label class="field-label">New Password <span class="text-gray-400">(Optional)</span></label>
                                <input type="password" class="field-input" wire:model.defer="password" placeholder="Leave blank to keep unchanged" />
                            </div>
                            @error('password') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-3 text-sm font-black text-white hover:bg-[#229aa4]">
                            Save Changes
                        </button>
                        <button type="button" wire:click="closeEdit"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
