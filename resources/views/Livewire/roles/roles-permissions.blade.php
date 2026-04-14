<div class="max-w-6xl px-1 py-1">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Roles & Permissions</h1>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">System Access Control</p>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                {{-- Roles Column --}}
                <div class="md:col-span-4 space-y-6">
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4">Roles</h3>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <div wire:key="role-{{ $role->id }}" 
                                     wire:click="selectRole({{ $role->id }})"
                                     class="group flex items-center justify-between p-3 rounded-xl border transition-all cursor-pointer {{ $selectedRoleId == $role->id ? 'bg-[#2ab4c0]/10 border-[#2ab4c0] shadow-sm' : 'bg-white border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm {{ $selectedRoleId == $role->id ? 'bg-[#2ab4c0] text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                            {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-bold {{ $selectedRoleId == $role->id ? 'text-[#1f8f98]' : 'text-gray-700' }}">
                                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                        </span>
                                    </div>
                                    @if($role->name !== 'super_admin')
                                        <button wire:click.stop="deleteRole({{ $role->id }})" 
                                                wire:confirm="Deleting this role will remove all its permission assignments. Proceed?"
                                                class="opacity-0 group-hover:opacity-100 p-1.5 text-gray-400 hover:text-red-600 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Add New Role</h4>
                        <form wire:submit.prevent="createRole" class="space-y-3">
                            <div class="field-wrap !py-2 !px-3">
                                <input type="text" wire:model="newRoleName" class="field-input !text-xs" placeholder="Role name (e.g. editor)..." />
                            </div>
                            @error('newRoleName') <span class="text-[10px] text-red-600 font-bold ml-1">{{ $message }}</span> @enderror
                            <button type="submit" class="w-full bg-[#2ab4c0] text-white text-xs font-black py-2.5 rounded-xl hover:bg-[#229aa4] transition-colors shadow-sm">
                                Create Role
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Permissions Column --}}
                <div class="md:col-span-8">
                    <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden h-full flex flex-col">
                        <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">
                                    Permissions for: <span class="text-[#2ab4c0]">{{ $selectedRole ? ucwords(str_replace('_', ' ', $selectedRole->name)) : 'None' }}</span>
                                </h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight mt-0.5">Toggle to assign or revoke access</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="field-wrap !py-1.5 !px-3 !bg-gray-50 !border-gray-100 w-48">
                                    <input type="text" wire:model.live.debounce.300ms="searchPermissions" class="field-input !text-xs !bg-transparent" placeholder="Search..." />
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-grow overflow-y-auto max-h-[600px]">
                            @if($selectedRole)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @forelse($permissions as $permission)
                                        <div wire:key="perm-{{ $permission->id }}" 
                                             wire:click="togglePermission('{{ $permission->name }}')"
                                             class="flex items-center justify-between p-3 rounded-xl border transition-all cursor-pointer {{ in_array($permission->name, $currentRolePermissions) ? 'bg-white border-[#2ab4c0] shadow-sm ring-1 ring-[#2ab4c0]/20' : 'bg-white border-gray-100 hover:border-gray-300' }}">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    @if(in_array($permission->name, $currentRolePermissions))
                                                        <div class="w-5 h-5 rounded-md bg-[#2ab4c0] flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-5 h-5 rounded-md border-2 border-gray-200"></div>
                                                    @endif
                                                </div>
                                                <span class="text-xs font-bold {{ in_array($permission->name, $currentRolePermissions) ? 'text-gray-900' : 'text-gray-500' }}">
                                                    {{ $permission->name }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full py-10 text-center text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                            <p class="text-sm font-bold">No permissions match your search.</p>
                                        </div>
                                    @endforelse
                                </div>
                            @else
                                <div class="py-20 text-center text-gray-400">
                                    <p class="text-sm font-bold italic">Select a role on the left to manage its permissions.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto p-4 bg-white border-t border-gray-100">
                            <form wire:submit.prevent="createPermission" class="flex gap-2">
                                <div class="field-wrap !py-2 !px-3 !bg-gray-50 !border-gray-100 flex-grow">
                                    <input type="text" wire:model="newPermissionName" class="field-input !text-xs !bg-transparent" placeholder="New permission name (e.g. view_reports)..." />
                                </div>
                                <button type="submit" class="bg-gray-900 text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-black transition-colors uppercase tracking-widest shadow-sm">
                                    Add Permission
                                </button>
                            </form>
                            @error('newPermissionName') <span class="text-[10px] text-red-600 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
