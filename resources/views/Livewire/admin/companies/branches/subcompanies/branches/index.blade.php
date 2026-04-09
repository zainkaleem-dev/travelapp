<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight truncate">Branches</h1>
                    <p class="text-sm text-gray-600 mt-1 truncate">{{ $company->name }} · {{ $subCompany->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.users', ['company' => $company->id]) }}"
                        class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-black text-gray-700 hover:bg-gray-50">
                        Users
                    </a>
                    <a href="{{ route('superadmin.subcompanies.index', ['company' => $company, 'branch' => $branch]) }}"
                        class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-black text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                    <button type="button" wire:click="openCreate"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                        Add Branch
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div>
                <table class="w-full table-fixed text-sm">
                    <thead>
                        <tr class="text-left text-xs font-black tracking-wide uppercase text-gray-600 border-b border-gray-200">
                            <th class="py-3 pr-4 w-[32%]">Branch</th>
                            <th class="py-3 pr-4 w-[10%]">Code</th>
                            <th class="py-3 pr-4 w-[18%]">Email</th>
                            <th class="py-3 pr-4 w-[16%]">Phone</th>
                            <th class="py-3 pr-4 w-[10%]">Status</th>
                            <th class="py-3 pr-0 w-[18%] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($branches as $b)
                            <tr class="{{ $b->is_active ? '' : 'opacity-60 grayscale' }}">
                                <td class="py-4 pr-4 align-top">
                                    <div class="font-black text-gray-900">{{ $b->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ optional($b->created_at)->format('Y-m-d') }}</div>
                                    <div class="text-xs text-gray-600 mt-1 break-words">
                                        {{ collect([$b->address, $b->city, $b->country])->filter()->implode(', ') ?: '—' }}
                                    </div>
                                </td>
                                <td class="py-4 pr-4 align-top font-semibold text-gray-900 break-words">{{ $b->code ?: '—' }}</td>
                                <td class="py-4 pr-4 align-top font-semibold text-gray-900 break-all">{{ $b->email ?: '—' }}</td>
                                <td class="py-4 pr-4 align-top font-semibold text-gray-900 break-words">{{ $b->phone ?: '—' }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md {{ $b->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2 py-0.5 text-[11px] font-black">
                                        {{ $b->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-4 pr-0 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $b->id }})"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-black text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="toggleActive({{ $b->id }})"
                                            class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-xs font-black {{ $b->is_active ? 'bg-gray-900 text-white hover:bg-black' : 'bg-[#2ab4c0] text-white hover:bg-[#229aa4]' }} min-w-[88px]">
                                            {{ $b->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-gray-500">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    @if ($createModalOpen)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" wire:click="closeCreate"></div>
            <div class="relative h-full w-full overflow-y-auto px-4 py-8">
                <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-black text-gray-900">Add Branch</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $subCompany->name }}</div>
                            </div>
                            <button type="button" wire:click="closeCreate"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="createBranch" class="p-6 space-y-6">
                        @include('Livewire.admin.companies.branches.subcompanies.branches.partials.form')
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeCreate"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if ($editModalOpen)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" wire:click="closeEdit"></div>
            <div class="relative h-full w-full overflow-y-auto px-4 py-8">
                <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-black text-gray-900">Edit Branch</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $subCompany->name }}</div>
                            </div>
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="updateBranch" class="p-6 space-y-6">
                        @include('Livewire.admin.companies.branches.subcompanies.branches.partials.form')
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeEdit"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
