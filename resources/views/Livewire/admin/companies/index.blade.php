<div>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Companies</h1>
                        <p class="text-sm text-gray-600 mt-1">All added companies</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block">
                            <div class="field-wrap !py-2 !px-3">
                                <input type="text" class="field-input" wire:model.live.debounce.300ms="search" placeholder="Search companies..." />
                            </div>
                        </div>
                        <a href="{{ route('superadmin.companies.create') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-black text-white hover:bg-[#229aa4]">
                            Add Company
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
                @endif

                <!-- Datatable -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Logo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Company</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors {{ $company->is_active ? '' : 'opacity-60 grayscale' }}">
                                <td class="px-6 py-4">
                                    <div class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if ($company->logo_path)
                                        <img src="{{ '/storage/' . ltrim($company->logo_path, '/') }}"
                                            alt="{{ $company->name }} logo"
                                            class="w-full h-full object-contain p-1">
                                        @else
                                        <span class="text-xs font-black text-gray-500">
                                            {{ strtoupper(mb_substr((string) $company->name, 0, 2)) }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                                    <div class="text-xs text-gray-500">{{ optional($company->created_at)->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2.5 py-0.5 text-xs font-bold text-[#1f8f98]">
                                        {{ $company->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $company->email ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $company->phone ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-md {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-xs font-semibold">
                                        {{ $company->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="openEdit({{ $company->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('superadmin.branches', ['company' => $company->id]) }}"
                                            class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold"
                                            title="Branches">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7.5h18M3 12h18M3 16.5h18" />
                                            </svg>
                                        </a>

                                        <button type="button" wire:click="toggleActive({{ $company->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-lg text-xs font-semibold {{ $company->is_active ? 'bg-gray-900 text-white hover:bg-black' : 'bg-[#2ab4c0] text-white hover:bg-[#229aa4]' }}"
                                            title="{{ $company->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if ($company->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            @endif
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    No companies found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                @if ($paginationMeta['last_page'] > 1 || $paginationMeta['total'] > 0)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold">{{ $paginationMeta['from'] ?? 0 }}</span> to
                            <span class="font-semibold">{{ $paginationMeta['to'] ?? 0 }}</span> of
                            <span class="font-semibold">{{ $paginationMeta['total'] }}</span> companies
                        </div>
                        @if ($paginationMeta['last_page'] > 1)
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if ($paginationMeta['current_page'] > 1)
                            <button wire:click="goToPage({{ $paginationMeta['current_page'] - 1 }})"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                                ← Previous
                            </button>
                            @else
                            <button disabled
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-semibold opacity-50">
                                ← Previous
                            </button>
                            @endif

                            {{-- Page Numbers --}}
                            @for ($page = max(1, $paginationMeta['current_page'] - 2); $page <= min($paginationMeta['last_page'], $paginationMeta['current_page'] + 2); $page++)
                                <button
                                wire:click="goToPage({{ $page }})"
                                class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium
                                        {{ $page === $paginationMeta['current_page'] 
                                            ? 'bg-[#2ab4c0] text-white' 
                                            : 'border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                {{ $page }}
                                </button>
                                @endfor

                                {{-- Next Button --}}
                                @if ($paginationMeta['has_more'])
                                <button wire:click="goToPage({{ $paginationMeta['current_page'] + 1 }})"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                                    Next →
                                </button>
                                @else
                                <button disabled
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-semibold opacity-50">
                                    Next →
                                </button>
                                @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if ($editModalOpen)
    <div class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40" wire:click="closeEdit"></div>

        <div class="relative h-full w-full overflow-y-auto px-4 py-8">
            <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-black text-gray-900">Edit Company</div>
                            <div class="text-xs text-gray-600 mt-0.5">Update company and admin details</div>
                        </div>
                        <button type="button" wire:click="closeEdit"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="updateCompany" class="p-6 space-y-6">
                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Company</h2>
                            <span class="text-xs font-semibold text-gray-500">Basic details</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <label class="field-label">Company Logo <span class="text-gray-400">(Optional)</span></label>
                                    @if ($company_logo || $existing_logo_path)
                                    <button type="button" wire:click="removeLogo"
                                        class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                                        Remove
                                    </button>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4">
                                    <div class="w-14 h-14 rounded-full border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if ($company_logo)
                                        <img src="{{ $company_logo->temporaryUrl() }}" alt="Company logo preview" class="w-full h-full object-cover">
                                        @elseif ($existing_logo_path)
                                        <img src="{{ '/storage/' . ltrim($existing_logo_path, '/') }}" alt="Company logo" class="w-full h-full object-contain p-1">
                                        @else
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M9 3v2m6-2v2M4 8h16v13H4z" />
                                        </svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold text-gray-900">Upload logo</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Click to choose a file</div>
                                        <input type="file"
                                            class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2ab4c0] file:text-white hover:file:bg-[#229aa4]"
                                            wire:model="company_logo"
                                            accept=".jpg,.jpeg,.png,.svg,image/jpeg,image/png,image/svg+xml" />
                                        @error('company_logo') <span class="field-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Name <span class="text-red-600">*</span></label>
                                    <input type="text" class="field-input" wire:model.defer="company_name" placeholder="Company name" />
                                </div>
                                @error('company_name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Type <span class="text-red-600">*</span></label>
                                    <select class="field-input" wire:model.defer="company_type">
                                        <option value="">Select type</option>
                                        <option value="TMC - Alma Travel">TMC - Alma Travel</option>
                                        <option value="Corporate - Nahdi">Corporate - Nahdi</option>
                                        <option value="Corporate - STC">Corporate - STC</option>
                                        <option value="TMC - Global Travel">TMC - Global Travel</option>
                                    </select>
                                </div>
                                @error('company_type') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Email <span class="text-gray-400">(Optional)</span></label>
                                    <input type="email" class="field-input" wire:model.defer="company_email" placeholder="Optional" />
                                </div>
                                @error('company_email') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Phone <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="phone" placeholder="Optional" />
                                </div>
                                @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <div class="field-wrap">
                                    <label class="field-label">Country <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="country" placeholder="Optional" />
                                </div>
                                @error('country') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Admin Account</h2>
                            <span class="text-xs font-semibold text-gray-500">Required</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Admin Email <span class="text-red-600">*</span></label>
                                    <input type="email" class="field-input" wire:model.defer="admin_email" placeholder="admin@company.com" />
                                </div>
                                @error('admin_email') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Admin Password <span class="text-red-600">*</span></label>
                                    <input type="password" class="field-input" wire:model.defer="admin_password" placeholder="New password" />
                                </div>
                                @error('admin_password') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <div class="field-wrap">
                                    <label class="field-label">Admin Name <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="admin_name" placeholder="Optional" />
                                </div>
                                @error('admin_name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-black tracking-wide text-gray-900 uppercase">Limits</h2>
                            <span class="text-xs font-semibold text-gray-500">Optional</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Subscription Plan <span class="text-gray-400">(Optional)</span></label>
                                    <input type="text" class="field-input" wire:model.defer="subscription_plan" placeholder="Optional" />
                                </div>
                                @error('subscription_plan') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="field-wrap">
                                    <label class="field-label">Company Limit <span class="text-gray-400">(Optional)</span></label>
                                    <input type="number" class="field-input" wire:model.defer="company_limit" placeholder="Optional" min="1" />
                                </div>
                                @error('company_limit') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeEdit"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-black text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-6 py-2.5 text-sm font-black text-white hover:bg-[#229aa4]">
                            Update Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
