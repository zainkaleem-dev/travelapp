<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
    <button type="button" @click="open = !open" 
        class="flex items-center gap-2 rounded-full border border-[#2ab4c0]/20 bg-[#f2feff]/50 px-4 py-1.5 hover:bg-[#f2feff] hover:border-[#2ab4c0]/40 transition-all focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/20 group">
        
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-[#2ab4c0] shadow-[0_0_8px_rgba(42,180,192,0.6)] animate-pulse"></div>
            <span class="text-xs font-black text-gray-800 uppercase tracking-wider">
                @if(empty($activeCompanyId))
                    Global System
                @else
                    {{ $manageableCompanies->find($activeCompanyId)->name ?? 'Unknown context' }}
                @endif
            </span>
        </div>

        <svg class="w-4 h-4 text-[#2ab4c0] transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-cloak x-show="open" 
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
        x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
        x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
        class="absolute left-0 mt-3 w-72 bg-white border border-gray-100 rounded-2xl shadow-2xl z-[9999] overflow-hidden">
        
        <div class="p-4 bg-gray-50 border-b border-gray-100">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Context</h3>
        </div>

        <div class="max-h-80 overflow-y-auto py-2 no-scrollbar">
            @if($isSuperAdmin)
                <button wire:click="switchContext('global')" 
                    class="w-full px-4 py-3 text-left flex items-center justify-between group transition-colors hover:bg-gray-50 {{ empty($activeCompanyId) ? 'bg-[#f2feff]/50' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-xs group-hover:bg-white transition-colors">G</div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 leading-tight">Global System</p>
                            <p class="text-[10px] text-gray-500 font-medium">Master Key Context</p>
                        </div>
                    </div>
                    @if(empty($activeCompanyId))
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0]"></div>
                    @endif
                </button>
            @endif

            @foreach($manageableCompanies as $company)
                <button wire:click="switchContext('{{ $company->id }}')" 
                    class="w-full px-4 py-3 text-left flex items-center justify-between group transition-colors hover:bg-gray-50 {{ $activeCompanyId == $company->id ? 'bg-[#f2feff]/50' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#2ab4c0]/5 flex items-center justify-center text-[#2ab4c0] font-black text-xs group-hover:bg-white transition-colors">
                            {{ strtoupper(substr($company->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 leading-tight truncate">{{ $company->name }}</p>
                            <p class="text-[10px] text-gray-500 font-medium tracking-tight truncate">Company Context</p>
                        </div>
                    </div>
                    @if($activeCompanyId == $company->id)
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2ab4c0]"></div>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="p-3 bg-gray-50 border-t border-gray-100">
            <p class="text-[9px] text-gray-400 font-bold text-center uppercase tracking-widest italic">Switch focus across the hierarchy</p>
        </div>
    </div>
</div>
