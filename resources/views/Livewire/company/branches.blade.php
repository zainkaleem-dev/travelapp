<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Branches</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'branches'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            <div class="space-y-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Organization Branches (Read Only)</p>

                @forelse($branches as $branch)
                    <div class="relative p-6 rounded-lg border border-gray-200 bg-gray-50/30 shadow-sm mb-6 last:mb-0">
                        <div class="mb-6 flex items-center justify-between gap-3">
                            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest border-l-4 border-[#2ab4c0] pl-3">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Branch Name</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->name }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Branch Code</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-700 font-mono uppercase">{{ $branch->code }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Slug</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-700 font-mono uppercase">{{ $branch->slug }}</p>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Email Address</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->email ?: '--' }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Primary Phone</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->phone ?: '--' }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Secondary Phone</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->phone_secondary ?: '--' }}</p>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Fax</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->fax ?: '--' }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">WhatsApp</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->whatsapp ?: '--' }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Postal Code</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $branch->postal_code ?: '--' }}</p>
                            </div>

                            <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Full Address</p>
                                <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">
                                    {{ $branch->address_line_1 }}@if($branch->address_line_2), {{ $branch->address_line_2 }}@endif
                                    @if($branch->city), {{ $branch->city }}@endif @if($branch->state), {{ $branch->state }}@endif @if($branch->country), {{ $branch->country }}@endif
                                    @if(!$branch->address_line_1 && !$branch->address_line_2 && !$branch->city)--@endif
                                </p>
                            </div>

                            <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-gray-200 bg-white px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Branch Notes</p>
                                <p class="mt-2 text-[11px] font-medium text-gray-700 leading-relaxed">{{ $branch->notes ?: 'No internal notes.' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-6 text-center text-[11px] text-gray-500 font-semibold uppercase tracking-wider shadow-sm">
                        No branches found for this {{ auth()->user()->can('Manage Global System') ? 'organization' : 'partner' }}.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

