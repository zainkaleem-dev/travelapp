<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company_name }} Profile</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'general'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            {{-- Core Identity --}}
            <div class="space-y-6">
                <div class="flex items-center gap-6 p-4 rounded-lg border border-dashed border-gray-200 bg-gray-50/30">
                    @php
                        $companyInitials = strtoupper(
                            collect(preg_split('/\s+/', trim((string) $company_name)))
                                ->filter()
                                ->take(2)
                                ->map(fn ($word) => mb_substr($word, 0, 1))
                                ->implode('')
                        );
                        $companyInitials = $companyInitials !== '' ? $companyInitials : strtoupper(mb_substr((string) $company_name, 0, 2));
                    @endphp
                    <div class="w-16 h-16 rounded-lg border border-gray-200 bg-white flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
                        @if ($existing_logo_path)
                            <img src="{{ asset('storage/' . $existing_logo_path) }}" class="w-full h-full object-contain p-1">
                        @else
                            <span class="text-lg font-black text-gray-500">
                                {{ $companyInitials ?? strtoupper(mb_substr((string) ($company_name ?? ''), 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-[11px] font-bold text-gray-900 uppercase tracking-wider mb-1">Organization Logo</label>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-tight">Read-only profile view</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Name' : 'Partner Name' }}</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $company_name }}</p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Slug / ID</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-700 font-mono uppercase">{{ $slug }}</p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Type' : 'Partner Type' }}</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $company_type ?: '--' }}</p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Parent Organization' : 'Parent Partner' }}</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $parentCompanyName ?: ((auth()->user()?->can('Manage Global System') ?? false) ? 'None (Root Organization)' : 'None (Root Partner)') }}</p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Registration No.</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $registration_number ?: '--' }}</p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Founded Year</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $founded_year ?: '--' }}</p>
                    </div>

                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Status</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $status }}</p>
                    </div>

                    <div class="md:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Organization Description' : 'Partner Description' }}</p>
                        <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">{{ $description ?: 'No description provided.' }}</p>
                    </div>

                    <div class="md:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Internal Notes</p>
                        <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">{{ $notes ?: 'No internal notes.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

