@php($isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System'))
<div class="w-full px-1 py-1 flex flex-col gap-3">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Branches</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'branches'])
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase mb-4">Branches (Read Only)</h2>

                @forelse($branches as $branch)
                    <div class="relative p-6 rounded-lg border border-gray-100 bg-white shadow-sm mb-4 last:mb-0">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">
                                {{ $branch->name ?: 'Branch' }}
                            </h3>
                            <div class="flex items-center gap-2">
                                @if($branch->is_main)
                                    <span class="inline-flex items-center rounded-md bg-[#2ab4c0]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#1f8f98] uppercase tracking-tight">
                                        Main Branch
                                    </span>
                                @endif
                                <span class="inline-flex items-center rounded-md {{ $branch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }} px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-tight">
                                    {{ ucfirst($branch->status ?? 'inactive') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="field-label">Branch Name</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->name }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Branch Code</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700 font-mono" value="{{ $branch->code }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Slug</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700 font-mono" value="{{ $branch->slug }}" readonly>
                            </div>

                            <div>
                                <label class="field-label">Email</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->email }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Primary Phone</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->phone }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Secondary Phone</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->phone_secondary }}" readonly>
                            </div>

                            <div>
                                <label class="field-label">Fax</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->fax }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">WhatsApp</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->whatsapp }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Postal Code</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->postal_code }}" readonly>
                            </div>

                            <div class="md:col-span-2">
                                <label class="field-label">Address Line 1</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->address_line_1 }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Address Line 2</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->address_line_2 }}" readonly>
                            </div>

                            <div>
                                <label class="field-label">City</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->city }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">State / Province</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->state }}" readonly>
                            </div>
                            <div>
                                <label class="field-label">Country</label>
                                <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $branch->country }}" readonly>
                            </div>

                            <div class="md:col-span-3">
                                <label class="field-label">Notes</label>
                                <textarea rows="2" class="input-field pt-2 bg-gray-50 text-gray-700" readonly>{{ $branch->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 text-[11px] text-gray-500 font-semibold uppercase">
                        No branches found for this {{ $isSuperAdmin ? 'organization' : 'partner' }}.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

