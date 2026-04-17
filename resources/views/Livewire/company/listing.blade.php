<div x-data="{ filtersOpen: false }">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Company Profile</h1>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Management Domain</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Datatable -->
                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide rounded-ss-2xl">
                                    Company</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide">
                                    Type</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-white uppercase tracking-wide">
                                    Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wide rounded-se-2xl">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($companyId)
                                <tr class="border-b border-gray-100 hover:bg-[#f2feff]/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-[#2ab4c0]/10 flex items-center justify-center text-sm font-black text-[#2ab4c0]">
                                                {{ substr($companyName, 0, 1) }}
                                            </div>
                                            <div class="text-sm font-bold text-gray-900">{{ $companyName }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                        {{ $companyType ?: 'Standard' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $isActive ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('company.branches.index') }}"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-50 border border-gray-200 px-4 py-2 text-[11px] font-black uppercase tracking-wider text-gray-600 hover:bg-white hover:border-[#2ab4c0] hover:text-[#2ab4c0] transition-all shadow-sm">
                                                Manage Branches
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No company profile found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>