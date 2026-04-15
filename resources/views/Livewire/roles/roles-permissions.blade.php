<div>
    <div class="max-w-7xl mx-auto px-1 sm:px-4 lg:px-6">
        <div class="flex flex-col lg:flex-row gap-6 bg-transparent min-h-[calc(100vh-200px)]">
            
            {{-- Sidebar: Company Selection (Super Admin Only) --}}
            @if($isSuperAdmin)
            <div class="w-full lg:w-96 flex-shrink-0">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 bg-[#f9faf6] border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-2 rounded-full bg-[#2ab4c0] shadow-[0_0_8px_rgba(42,180,192,0.6)]"></div>
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Access Control</h2>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-focus-within:text-[#2ab4c0] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0] transition-all"
                                placeholder="Search companies...">
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar" style="max-height: 600px;">
                        @forelse($sidebarCompanies as $company)
                            <button wire:click="selectCompany({{ $company->id }})"
                                class="w-full text-left p-4 rounded-2xl border transition-all duration-200 group
                                    {{ $selectedCompanyId === $company->id 
                                        ? 'bg-white border-[#2ab4c0] shadow-md ring-1 ring-[#2ab4c0]/10' 
                                        : 'bg-transparent border-transparent hover:bg-gray-50 hover:border-gray-200' }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-black transition-transform group-hover:scale-110
                                        {{ $selectedCompanyId === $company->id ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-100 text-gray-400' }}">
                                        {{ substr($company->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $company->name }}</h3>
                                            @if($selectedCompanyId === $company->id)
                                                <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0]"></div>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-500 font-medium lowercase tracking-tighter">
                                            {{ $companyStats[$company->id]['roles'] }} roles defined
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="py-12 text-center text-gray-400">
                                <p class="text-sm font-medium">No companies found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            {{-- Main Content: Roles & Permissions --}}
            <div class="flex-1">
                @if($activeCompany)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-visible h-full flex flex-col">
                        {{-- Header --}}
                        <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-br from-white to-[#fafbfc]">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-3xl bg-[#f2feff] border border-[#2ab4c0]/20 flex items-center justify-center text-2xl font-black text-[#2ab4c0] shadow-sm">
                                    {{ substr($activeCompany->name, 0, 1) }}
                                </div>
                                <div>
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $activeCompany->name }}</h2>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Role Architecture</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="relative group">
                                    <input type="text" wire:model.defer="newRoleName"
                                        class="pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/20 transition-all w-48"
                                        placeholder="New role name...">
                                    <button wire:click="createRole" class="absolute right-2 top-2 p-1 text-[#2ab4c0] hover:bg-[#2ab4c0] hover:text-white rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Roles Selector --}}
                        <div class="px-8 py-4 bg-[#fdfdfc] border-b border-gray-100 flex items-center gap-3 overflow-x-auto no-scrollbar">
                            @foreach($roles as $role)
                                <button wire:click="selectRole({{ $role->id }})"
                                    class="px-5 py-2.5 rounded-xl border text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2
                                    {{ $selectedRoleId === $role->id 
                                        ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white shadow-lg shadow-[#2ab4c0]/20' 
                                        : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200' }}">
                                    <span>{{ $role->name }}</span>
                                    @if($selectedRoleId === $role->id && $role->name !== 'super_admin')
                                        <div wire:click.stop="deleteRole({{ $role->id }})" class="hover:text-red-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        {{-- Permissions Matrix --}}
                        <div class="flex-1 p-8 overflow-y-auto no-scrollbar" style="max-height: 500px;">
                            @if($selectedRole)
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-xl font-black text-gray-900">Manage Permissions</h3>
                                        <p class="text-xs text-gray-500 font-medium">Assigning capabilities to <span class="bg-[#2ab4c0]/10 text-[#2ab4c0] px-1.5 py-0.5 rounded-md font-bold">{{ $selectedRole->name }}</span></p>
                                    </div>
                                    <div class="relative w-64">
                                        <input type="text" wire:model.live.debounce.300ms="searchPermissions"
                                            class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/10 transition-all font-medium"
                                            placeholder="Filter permissions...">
                                        <svg class="absolute left-3 top-2.5 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($allPermissions as $permission)
                                        <div wire:key="perm-{{ $permission->id }}" class="group bg-white border border-gray-100 rounded-2xl p-4 flex items-center justify-between hover:border-[#2ab4c0]/30 hover:shadow-sm transition-all duration-300">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors
                                                    {{ in_array($permission->name, $currentRolePermissions) ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-50 text-gray-300' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ ucwords(str_replace('.', ' ', $permission->name)) }}</h4>
                                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-0.5">{{ $permission->name }}</p>
                                                </div>
                                            </div>

                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" 
                                                    wire:click="togglePermission('{{ $permission->name }}')"
                                                    {{ in_array($permission->name, $currentRolePermissions) ? 'checked' : '' }}
                                                    class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2ab4c0] shadow-inner transition-colors"></div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="h-64 flex flex-col items-center justify-center text-center opacity-50">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 9.997l.013-1.047a7.5 7.5 0 00-11.512-6.373M9 13v1m0 0a3 3 0 100 6v-1m0 0a3 3 0 100-6"/></svg>
                                    <p class="text-sm font-bold text-gray-400">Select a Role to Manage Permissions</p>
                                </div>
                            @endif
                        </div>

                        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 rounded-b-3xl flex items-center justify-between">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Policy Engine v2.0</p>
                            <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest">
                                <span class="text-green-600">Secure</span>
                                <span class="text-blue-600">Audited</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex items-center justify-center p-20 h-full">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Permissions Dashboard</h3>
                            <p class="text-sm text-gray-500 mt-2">Select a company from the left to configure access roles.</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
