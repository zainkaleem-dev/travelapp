<div>
    <div class="px-1 py-1 w-full">
        @if($isCompanyRoute)
            <div class="mb-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $this->activeCompany->name ?? 'Roles Permissions' }}</h1>
                </div>
                @include('partials.navigation-company', ['companyId' => $contextCompanyId, 'activeTab' => 'roles-permissions'])
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 bg-transparent min-h-[calc(100vh-200px)]">
            
            {{-- Sidebar: Access Control (Super Admin sees Context, Company Admin sees their own) --}}
            <div class="w-full lg:w-60 flex-shrink-0">
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 bg-[#f9faf6] border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-2 rounded-full bg-[#2ab4c0] shadow-[0_0_8px_rgba(42,180,192,0.6)]"></div>
                            <h2 class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">Access Control</h2>
                        </div>
                        
                        {{-- Mode Selector --}}
                        <div class="flex bg-gray-100 p-1 rounded-lg mb-4 overflow-hidden">
                            <button wire:click="setViewMode('roles')" class="flex-1 py-1.5 text-[11px] font-bold rounded-lg transition-all {{ $viewMode === 'roles' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                Roles
                            </button>
                            <button wire:click="setViewMode('users')" class="flex-1 py-1.5 text-[11px] font-bold rounded-lg transition-all {{ $viewMode === 'users' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                Users
                            </button>
                        </div>

                        {{-- Context Selector (Visible to Super Admin or any Admin with manageable companies) --}}
                        @if($isSuperAdmin || count($companies) > 0)
                        <div class="mb-4">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Company Context</label>
                            <div class="relative" x-data="{ open: false, selected: @js((string) ($contextCompanyId ?? '')), labels: @js($companies->pluck('name', 'id')) }" @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="admin-menu-btn w-full" @click="open = !open">
                                    <span x-text="selected === '' ? 'Global System' : (labels[selected] ?? 'Global System')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    @if($isSuperAdmin)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '' }" @click="selected = ''; open = false; $wire.set('contextCompanyId', '')">Global System</button>
                                    @endif
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '{{ $company->id }}' }" @click="selected = '{{ $company->id }}'; open = false; $wire.set('contextCompanyId', '{{ $company->id }}')">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-focus-within:text-[#2ab4c0] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            @if($viewMode === 'roles')
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    class="input-field block w-full pl-10 pr-3"
                                    placeholder="Search roles...">
                            @else
                                <input type="text" wire:model.live.debounce.300ms="searchUsers"
                                    class="input-field block w-full pl-10 pr-3"
                                    placeholder="Search users...">
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar" style="max-height: 600px;">
                        @if($viewMode === 'roles')
                            @forelse($sidebarRoles as $role)
                                <button wire:click="selectRole({{ $role->id }})"
                                    class="w-full text-left p-2 rounded-lg border transition-all duration-200 group
                                        {{ $selectedRoleId === $role->id 
                                            ? 'bg-white border-[#2ab4c0] shadow-md ring-1 ring-[#2ab4c0]/10' 
                                            : 'bg-transparent border-transparent hover:bg-gray-50 hover:border-gray-200' }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black transition-transform group-hover:scale-110
                                            {{ $selectedRoleId === $role->id ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-100 text-gray-400' }}">
                                            {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <h3 class="text-[11px] font-bold text-gray-900 truncate">{{ $role->name }}</h3>
                                                @if($selectedRoleId === $role->id)
                                                    <div class="w-1 h-1 rounded-full bg-[#2ab4c0]"></div>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-500 font-medium tracking-tight">
                                                {{ $role->company ? $role->company->name : 'Global System' }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="py-12 text-center text-gray-400">
                                    <p class="text-sm font-medium">No roles found.</p>
                                </div>
                            @endforelse
                        @else
                            @forelse($sidebarUsers as $user)
                                <button wire:click="selectUser({{ $user->id }})"
                                    class="w-full text-left p-2 rounded-lg border transition-all duration-200 group
                                        {{ $selectedUserId === $user->id 
                                            ? 'bg-white border-[#2ab4c0] shadow-md ring-1 ring-[#2ab4c0]/10' 
                                            : 'bg-transparent border-transparent hover:bg-gray-50 hover:border-gray-200' }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-transform group-hover:scale-110
                                            {{ $selectedUserId === $user->id ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-100 text-gray-400' }}">
                                            {{ strtoupper(substr($user->first_name ?? $user->email, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <h3 class="text-[11px] font-bold text-gray-900 truncate">{{ $user->display_name ?? $user->first_name }}</h3>
                                                @if($selectedUserId === $user->id)
                                                    <div class="w-1 h-1 rounded-full bg-[#2ab4c0]"></div>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-500 font-medium truncate">
                                                {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="py-12 text-center text-gray-400">
                                    <p class="text-sm font-medium">No users found.</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Content: Roles & Permissions --}}
            <div class="flex-1 min-h-0">
                {{-- Global Alerts --}}
                @if (session('status'))
                    <div class="mb-4 p-3 bg-teal-50 border border-teal-100 rounded-lg flex items-center gap-3 text-teal-700 shadow-sm animate-fade-in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-bold uppercase">{{ session('status') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-100 rounded-lg flex items-center gap-3 text-red-700 shadow-sm animate-fade-in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-bold uppercase">{{ session('error') }}</span>
                    </div>
                @endif

                @if($viewMode === 'roles')
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden h-full min-h-0 flex flex-col">
                        {{-- Header / Create Role --}}
                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-br from-white to-[#fafbfc] rounded-t-lg">
                            <div class="flex items-center gap-5 group">
                                <div class="w-14 h-14 rounded-lg bg-[#f2feff] border border-[#2ab4c0]/20 flex items-center justify-center text-[21px] font-black text-[#2ab4c0] shadow-sm">
                                    {{ $selectedRole ? strtoupper(substr($selectedRole->name, 0, 1)) : 'R' }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-[21px] font-black text-gray-900 tracking-tight">
                                            {{ $selectedRole ? $selectedRole->name : 'Role Architecture' }}
                                        </h2>
                                        @php
                                            $protectedRoles = ['Super Admin', 'Company Admin', 'Organization Admin', 'Branch Admin', 'Agent', 'User'];
                                        @endphp
                                        @if($selectedRole && !in_array($selectedRole->name, $protectedRoles))
                                            <button wire:click="editRole({{ $selectedRole->id }})" class="opacity-0 group-hover:opacity-100 p-1.5 text-gray-400 hover:text-[#2ab4c0] hover:bg-[#2ab4c0]/5 rounded-lg transition-all" title="Rename Role">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                        @if($selectedRole)
                                            {{ $selectedRole->company ? $selectedRole->company->name : 'Global System Role' }}
                                        @else
                                            Manage Roles
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="px-4 py-2 bg-gray-100/80 border border-gray-100 rounded-lg text-[11px] font-black text-gray-500 uppercase tracking-widest text-center shadow-sm">
                                    {{ empty($contextCompanyId) || $contextCompanyId === 'global' ? 'Global Context' : (($selectedRole->company->name ?? 'Company') . ' Context') }}
                                </div>
                            </div>
                        </div>

                        {{-- Permissions Matrix --}}
                        <div class="flex-1 min-h-0 overflow-y-auto p-6 no-scrollbar">
                            @if($selectedRole)
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-[21px] font-black text-gray-900">Manage Permissions</h3>
                                        <p class="text-[11px] text-gray-500 font-medium">Assigning capabilities to <span class="bg-[#2ab4c0]/10 text-[#2ab4c0] px-1.5 py-0.5 rounded-md font-bold">{{ $selectedRole->name }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="relative w-64">
                                            <input type="text" wire:model.live.debounce.300ms="searchPermissions"
                                                class="input-field w-full pl-9 pr-4 transition-all font-medium"
                                                placeholder="Filter permissions...">
                                            <svg class="absolute left-3 top-2.5 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>

                                        {{-- Per Page Dropdown --}}
                                        <div class="relative flex items-center justify-center w-20"
                                            x-data="{ open: false, selected: @js((string) ($perPage ?? 10)) }"
                                            @keydown.escape.window="open = false" @click.outside="open = false">
                                            <button type="button" class="admin-menu-btn justify-center h-[34px]" @click="open = !open">
                                                <span x-text="selected"></span>
                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel !w-20">
                                                @foreach(['10', '20', '50', '100'] as $val)
                                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': selected === '{{ $val }}' }" @click="selected = '{{ $val }}'; open = false; $wire.set('perPage', '{{ $val }}')">{{ $val }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-[#f9faf6] border-b border-gray-100">
                                                <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest">Permission Scope & Name</th>
                                                <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-right">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 bg-white">
                                            @foreach($allPermissions as $permission)
                                                <tr wire:key="role-{{ $selectedRoleId }}-perm-{{ $permission->id }}" 
                                                    class="group hover:bg-gray-50/50 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors
                                                                {{ in_array($permission->name, $currentRolePermissions) ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-50 text-gray-300' }}">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-[11px] font-bold {{ in_array($permission->name, $currentRolePermissions) ? 'text-gray-900' : 'text-gray-500' }} transition-colors leading-tight">{{ ucwords(str_replace('.', ' ', $permission->name)) }}</h4>
                                                                <p class="text-[10px] {{ in_array($permission->name, $currentRolePermissions) ? 'text-[#2ab4c0]' : 'text-gray-400' }} uppercase font-black tracking-widest mt-0.5 transition-colors">{{ $permission->name }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <label class="relative inline-flex items-center {{ $selectedRole->company_id === null ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                                                            <input type="checkbox" 
                                                                @if($selectedRole->company_id !== null)
                                                                    wire:click="togglePermission('{{ $permission->name }}')"
                                                                @endif
                                                                {{ in_array($permission->name, $currentRolePermissions) ? 'checked' : '' }}
                                                                {{ $selectedRole->company_id === null ? 'disabled' : '' }}
                                                                class="sr-only peer">
                                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2ab4c0] shadow-inner transition-colors"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination Controls --}}
                                @if ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0)
                                    <div class="mt-8 pt-6 border-t border-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="text-[11px] text-gray-500 font-medium">
                                                Showing <span class="font-bold text-gray-900">{{ $paginationMeta['from'] ?? 0 }}</span> to
                                                <span class="font-bold text-gray-900">{{ $paginationMeta['to'] ?? 0 }}</span> of
                                                <span class="font-bold text-gray-900">{{ $paginationMeta['total'] }}</span> permissions
                                            </div>
                                            @if ($paginationMeta['last_page'] > 1)
                                                <div class="flex items-center gap-1.5">
                                                    @if ($paginationMeta['current_page'] > 1)
                                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                                            Previous
                                                        </button>
                                                    @endif

                                                    @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                                        <button wire:click="goToPage({{ $page }})" 
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[11px] font-bold transition-all
                                                            {{ $page === $paginationMeta['current_page']
                                                                ? 'bg-[#2ab4c0] text-white shadow-md'
                                                                : 'border border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                                                            {{ $page }}
                                                        </button>
                                                    @endfor

                                                    @if ($paginationMeta['has_more'])
                                                        <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600 hover:bg-gray-50 text-[11px] font-bold transition-all">
                                                            Next
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="h-64 flex flex-col items-center justify-center text-center opacity-50">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 9.997l.013-1.047a7.5 7.5 0 00-11.512-6.373M9 13v1m0 0a3 3 0 100 6v-1m0 0a3 3 0 100-6"/></svg>
                                    <p class="text-sm font-bold text-gray-400">Select a Role to Manage Permissions</p>
                                </div>
                            @endif
                        </div>

                    </div>
                @else
                    {{-- Users Mode --}}
                    @if($activeUser)
                        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden h-full min-h-0 flex flex-col">
                            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-br from-white to-[#fafbfc]">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-lg bg-[#f2feff] border border-[#2ab4c0]/20 flex items-center justify-center text-[21px] font-black text-[#2ab4c0] shadow-sm">
                                        {{ strtoupper(substr($activeUser->first_name ?? $activeUser->email, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <h2 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $activeUser->display_name ?? $activeUser->first_name }}</h2>
                                            @if($activeUser->id === auth()->id())
                                                <div class="flex items-center gap-1.5 px-2 py-0.5 bg-amber-50 border border-amber-200 rounded-lg text-[9px] font-black text-amber-700 uppercase tracking-wider">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    Self-Assignment Locked
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $activeUser->email }}</p>
                                    </div>
                                </div>

                                @if($isSuperAdmin)
                                    <div class="flex items-center gap-3">
                                        <div class="px-4 py-2 bg-gray-100/80 border border-gray-100 rounded-lg text-[11px] font-black text-gray-500 uppercase tracking-widest text-center shadow-sm">
                                            {{ empty($contextCompanyId) || $contextCompanyId === 'global' ? 'Global Context' : (($companies->find($contextCompanyId)->name ?? 'Company') . ' Context') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-h-0 overflow-y-auto p-6 no-scrollbar">
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-[21px] font-black text-gray-900">User Roles</h3>
                                        <p class="text-[11px] text-gray-500 font-medium">Assign access roles to this user.</p>
                                    </div>
                                </div>

                                @if(count($contextRoles) > 0)
                                    <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm">
                                        <table class="w-full text-left border-collapse">
                                            <thead>
                                                <tr class="bg-[#f9faf6] border-b border-gray-100">
                                                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest">Role Name</th>
                                                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest text-right">Assignment</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50 bg-white">
                                                @foreach($contextRoles as $role)
                                                    <tr wire:key="user-{{ $activeUser->id }}-role-{{ $role->id }}" 
                                                        class="group hover:bg-gray-50/50 transition-colors">
                                                        <td class="px-6 py-4">
                                                            <div class="flex items-center gap-4">
                                                                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-teal-50 transition-colors
                                                                    {{ in_array($role->id, $currentUserRoleIds) ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : '' }}">
                                                                    <svg class="w-5 h-5 {{ in_array($role->id, $currentUserRoleIds) ? 'text-[#2ab4c0]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-[11px] font-bold {{ in_array($role->id, $currentUserRoleIds) ? 'text-gray-800' : 'text-gray-400' }} leading-tight">{{ $role->name }}</h4>
                                                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-0.5">
                                                                        {{ in_array($role->id, $currentUserRoleIds) ? 'Assigned to User' : 'Not Assigned' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <label class="relative inline-flex items-center {{ ($role->company_id === null || $activeUser->id === auth()->id()) ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }}">
                                                                <input type="checkbox" 
                                                                    @if($role->company_id !== null && $activeUser->id !== auth()->id())
                                                                        wire:click="toggleDoubleSync('{{ $role->name }}', {{ $role->id }})"
                                                                    @endif
                                                                    {{ in_array($role->id, $currentUserRoleIds) ? 'checked' : '' }}
                                                                    {{ ($role->company_id === null || $activeUser->id === auth()->id()) ? 'disabled' : '' }}
                                                                    class="sr-only peer">
                                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2ab4c0] shadow-inner transition-colors"></div>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="h-64 flex flex-col items-center justify-center text-center opacity-50">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <p class="text-sm font-bold text-gray-400">No roles found for this context.</p>
                                        <p class="text-xs text-gray-400 mt-1">Switch back to Roles mode to define them.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg border border-gray-100 shadow-sm flex items-center justify-center p-20 h-full">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-6 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">User Assignments</h3>
                                <p class="text-sm text-gray-500 mt-2">Select a user from the left to assign access roles.</p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
