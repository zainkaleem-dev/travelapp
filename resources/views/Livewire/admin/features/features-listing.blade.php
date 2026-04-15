<div>
    <div class="max-w-7xl mx-auto px-1 sm:px-4 lg:px-6">
        <div class="flex flex-col lg:flex-row gap-6 bg-transparent min-h-[calc(100vh-200px)]">
            
            {{-- Sidebar: Company Management --}}
            <div class="w-full lg:w-96 flex-shrink-0">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                    {{-- Sidebar Header --}}
                    <div class="p-6 bg-[#f9faf6] border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Feature management</h2>
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

                    {{-- Company List --}}
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
                                            @if($companyStats[$company->id]['is_any_active'])
                                                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-500 font-medium">
                                            {{ $companyStats[$company->id]['active'] }}/{{ $companyStats[$company->id]['total'] }} active
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="py-12 text-center text-gray-400">
                                <p class="text-sm">No companies found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Main Content: Feature Details --}}
            <div class="flex-1">
                @if($activeCompany)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-visible h-full flex flex-col">
                        {{-- Detailed Header --}}
                        <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-br from-white to-[#fafbfc]">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-3xl bg-[#f2feff] border border-[#2ab4c0]/20 flex items-center justify-center text-2xl font-black text-[#2ab4c0] shadow-sm">
                                    {{ substr($activeCompany->name, 0, 1) }}
                                </div>
                                <div>
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $activeCompany->name }}</h2>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $activeCompany->slug }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-3 min-w-[200px]">
                                <div class="flex gap-2">
                                    <span class="px-3 py-1 bg-green-50 text-green-700 text-[11px] font-black uppercase rounded-full border border-green-100">
                                        {{ $onCount }} on
                                    </span>
                                    <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[11px] font-black uppercase rounded-full border border-gray-100">
                                        {{ $offCount }} off
                                    </span>
                                </div>
                                <div class="w-full">
                                    <div class="flex justify-between text-[11px] font-bold text-gray-500 mb-1.5 px-0.5">
                                        <span>SYSTEM HEALTH</span>
                                        <span class="text-[#2ab4c0]">{{ $activePercentage }}% ENABLED</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] transition-all duration-1000" style="width: {{ $activePercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Features Body --}}
                        <div class="flex-1 p-8 bg-[#fdfdfc]/50">
                            <p class="text-sm font-medium text-gray-600 mb-8">
                                Managing infrastructure modules for <span class="text-gray-900 font-bold">{{ $activeCompany->name }}</span>. Changes take effect immediately across all client instances.
                            </p>

                            <div class="grid grid-cols-1 gap-4 max-w-4xl">
                                @foreach($definedFeatures as $key => $feature)
                                    <div class="group relative bg-white border border-gray-200 rounded-2xl p-5 hover:border-[#2ab4c0]/40 hover:shadow-lg hover:shadow-[#2ab4c0]/5 transition-all duration-300">
                                        <div class="flex items-center gap-5">
                                            {{-- Feature Icon --}}
                                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-colors
                                                {{ $activeFeatures[$key] ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100' }}">
                                                @if($feature['icon'] === 'plane')
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                                                @elseif($feature['icon'] === 'building')
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                @elseif($feature['icon'] === 'car')
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0M21 12H3m14 0c0-1.5-4-5-8-5s-8 3.5-8 5m14 0c0 1.5-4 5-8 5s-8-3.5-8-5"/></svg>
                                                @elseif($feature['icon'] === 'bell')
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                                @else
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <h4 class="text-base font-bold text-gray-900 leading-tight">{{ $feature['label'] }}</h4>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $feature['description'] }}</p>
                                                <div class="mt-1 flex items-center gap-1.5">
                                                    <span class="text-[10px] uppercase font-black tracking-widest {{ $activeFeatures[$key] ? 'text-green-600' : 'text-gray-400' }}">
                                                        {{ $activeFeatures[$key] ? 'Active' : 'Disabled' }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Big Toggle Switch --}}
                                            <div class="flex items-center pr-2">
                                                <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                                    <input type="checkbox" 
                                                           wire:click="toggleFeature({{ $activeCompany->id }}, '{{ $key }}')"
                                                           {{ $activeFeatures[$key] ? 'checked' : '' }}
                                                           class="sr-only peer">
                                                    <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#2ab4c0] shadow-inner transition-colors duration-300"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer Action Bar (Optional) --}}
                        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 rounded-b-3xl">
                             <div class="flex items-center justify-between">
                                 <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Session ID: {{ substr(session()->getId(), 0, 8) }}</p>
                                 <div class="flex items-center gap-2">
                                     <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                     <span class="text-[11px] text-gray-600 font-bold uppercase tracking-wider">Sync fully active</span>
                                 </div>
                             </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex items-center justify-center p-20 h-full">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Select a company</h3>
                            <p class="text-sm text-gray-500 mt-2">Pick a company from the left to manage its modules.</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>
