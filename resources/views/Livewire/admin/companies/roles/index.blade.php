<div x-data="{ createModalOpen: false }" x-on:role-created.window="createModalOpen = false">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Roles</h1>
                        <p class="text-sm text-gray-600 mt-1">Manage role definitions and permissions for super admin users.</p>
                    </div>
                    <button type="button"
                        @click="createModalOpen = true"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                        Create
                    </button>
                </div>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Guard</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Created</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $role->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $role->guard_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ optional($role->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                wire:click="openEdit({{ $role->id }})"
                                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                                Edit
                                            </button>
                                            <button type="button"
                                                onclick="if(!confirm('Delete this role?')) return false;"
                                                wire:click="deleteRole({{ $role->id }})"
                                                class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No roles found. Click Create to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($editModalOpen)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" wire:click="closeEdit"></div>

            <div class="relative h-full w-full overflow-y-auto px-4 py-8">
                <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-black text-gray-900">Edit Role</div>
                                <div class="text-xs text-gray-600 mt-0.5">Update role name.</div>
                            </div>
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="updateRole" class="p-6">
                        <div>
                            <div class="field-wrap">
                                <label class="field-label">Name <span class="text-red-600">*</span></label>
                                <input type="text" class="field-input" wire:model.defer="editName" placeholder="Role name" />
                            </div>
                            @error('editName') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div x-cloak x-show="createModalOpen" class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40" @click="createModalOpen = false"></div>

        <div class="relative h-full w-full overflow-y-auto px-4 py-8">
            <div class="mx-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-black text-gray-900">Create Role</div>
                            <div class="text-xs text-gray-600 mt-0.5">Add your role inputs here.</div>
                        </div>
                        <button type="button"
                            @click="createModalOpen = false"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="saveRole" class="p-6">
                    <div>
                        <div class="field-wrap">
                            <label class="field-label">Name <span class="text-red-600">*</span></label>
                            <input type="text" class="field-input" wire:model.defer="name" placeholder="Role name" />
                        </div>
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button"
                            @click="createModalOpen = false"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                            Save Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

