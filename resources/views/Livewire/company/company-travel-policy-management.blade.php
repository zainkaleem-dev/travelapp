<div>
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] font-bold text-green-800 uppercase shadow-sm animate-fade-in">
            {{ session('status') }}
        </div>
    @endif

    {{-- Table Container --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-[#2ab4c0] border-b-2 border-gray-200">
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-ss-lg">Policy Name</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Type</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Company</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Grades</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide">Status</th>
                    <th class="px-6 py-2.5 text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($policies as $policy)
                    <tr class="group hover:bg-gray-50/50 transition-colors" wire:key="policy-{{ $policy->id }}">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <a href="{{ route('admin.travel-policy.view', ['companyId' => $policy->company_id, 'id' => $policy->id, 'returnUrl' => $returnUrl]) }}" class="text-[11px] font-bold text-gray-900 uppercase hover:text-[#2ab4c0] transition-colors">
                                    {{ $policy->name }}
                                </a>
                                <span class="text-[10px] text-gray-500 truncate max-w-xs">{{ $policy->description }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#2ab4c0]/10 text-[#2ab4c0] uppercase tracking-wider">{{ $policy->policy_type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-medium text-gray-600">{{ $policy->company->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @forelse($policy->grades as $grade)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase border border-gray-200">
                                        {{ $grade->name }}
                                    </span>
                                @empty
                                    <span class="text-[10px] text-gray-400 italic uppercase">No grades assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" wire:click="toggleStatus({{ $policy->id }})"
                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400"
                                title="{{ $policy->is_active ? 'Deactivate' : 'Activate' }}">
                                @if($policy->is_active)
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14h4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.travel-policy.edit', ['companyId' => $policy->company_id, 'id' => $policy->id, 'returnUrl' => $returnUrl]) }}" 
                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400" 
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button" wire:click="delete({{ $policy->id }})" wire:confirm="Are you sure you want to delete this policy?" 
                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-[11px] font-semibold transition-colors hover:border-gray-400" 
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-[11px] text-gray-500 font-bold uppercase">No travel policies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($policies->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $policies->links() }}
            </div>
        @endif
    </div>
</div>
