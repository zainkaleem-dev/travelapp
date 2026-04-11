<div class="w-full max-w-none mx-auto px-4 py-10">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight truncate">Companies</h1>
                    <p class="text-sm text-gray-600 mt-1 truncate">Your assigned company</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($companyId)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $companyName }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $companyType ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-md {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2 py-0.5 text-[11px] font-black">
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('company.branches.index') }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-xs font-black text-white hover:bg-[#229aa4]">
                                        Branches
                                    </a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No company assigned.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
