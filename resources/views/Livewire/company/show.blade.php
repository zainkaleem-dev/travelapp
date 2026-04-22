<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Organization Profile</h1>
                    <p class="text-sm text-gray-500 mt-1">Viewing {{ $company->name }} profile</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('companies.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'general'])

        <div class="p-6 space-y-8">
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Core Identity</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <div class="flex items-center gap-6 p-4 rounded-2xl border border-dashed border-gray-200 bg-white">
                            <div
                                class="w-16 h-16 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if ($existing_logo_path)
                                    <img src="{{ asset('storage/' . $existing_logo_path) }}" class="w-full h-full object-contain p-1">
                                @else
                                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-tight mb-1">Organization
                                    logo</label>
                                <p class="text-[11px] text-gray-500">Read-only profile view.</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Organization Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $company_name }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Slug / ID</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700 font-mono" value="{{ $slug }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Organization Type</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $company_type ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Parent Organization</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $parentCompanyName ?: 'None (Root Organization)' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Registration No.</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $registration_number ?: '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Founded Year</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $founded_year ?: '--' }}" readonly>
                    </div>

                    <div class="md:col-span-3">
                        <label class="field-label">Description</label>
                        <textarea rows="3" class="input-field pt-2 bg-gray-50 text-gray-700" readonly>{{ $description }}</textarea>
                    </div>

                </div>
            </div>

            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="field-label">Status</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ ucfirst($status) }}" readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Internal Notes</label>
                        <textarea rows="3" class="input-field pt-2 bg-gray-50 text-gray-700" readonly>{{ $notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

