<div class="p-6 sm:p-8 bg-[#f8fafc] min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Feature Management</h1>
                <p class="mt-1 text-sm text-gray-500">Enable or disable modules for <span class="font-semibold text-[#2ab4c0]">{{ $company->name }}</span></p>
            </div>
            <a href="{{ route('superadmin.companies.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-sm">
               <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
               Back to Companies
            </a>
        </div>

        {{-- Success Message --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Features Grid --}}
        <div class="grid grid-cols-1 gap-6">
            @foreach($definedFeatures as $key => $feature)
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:shadow-lg hover:border-[#2ab4c0]/30 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-[#2ab4c0]/10 text-[#2ab4c0] rounded-xl flex items-center justify-center transition-colors group-hover:bg-[#2ab4c0] group-hover:text-white">
                                @if($feature['icon'] === 'plane')
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                                @elseif($feature['icon'] === 'building')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                @elseif($feature['icon'] === 'car')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h9M9 6.75h6m-7.5 0V5.25A2.25 2.25 0 0111.25 3h.75a2.25 2.25 0 012.25 2.25v1.5H18a2.25 2.25 0 012.25 2.25v7.5A2.25 2.25 0 0118 16.5h-.75M9 6.75H6.75A2.25 2.25 0 004.5 9v7.5A2.25 2.25 0 006.75 18.75H9"/></svg>
                                @elseif($feature['icon'] === 'bell')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <span class="text-base font-semibold text-gray-900 leading-none">{{ $feature['label'] }}</span>
                                <span class="mt-1 text-sm text-gray-500 max-w-md leading-snug">{{ $feature['description'] }}</span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            {{-- Toggle Switch --}}
                            <label class="relative inline-flex items-center cursor-pointer group-hover:scale-105 transition-transform">
                                <input type="checkbox" 
                                       wire:model="featureStates.{{ $key }}" 
                                       wire:change="toggleFeature('{{ $key }}')"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2ab4c0]"></div>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center bg-white border border-dashed border-gray-300 rounded-2xl p-8">
            <svg class="mx-auto h-10 w-10 text-gray-400 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 tracking-tight">System Info</h3>
            <p class="mt-1 text-xs text-gray-500">Changes take effect immediately for all users in the company.</p>
        </div>
    </div>
</div>
