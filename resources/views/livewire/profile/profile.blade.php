<div class="w-full">
    <div class="w-full py-6 px-3 sm:px-4 lg:px-6">
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
            {{-- Header --}}
            <div class="bg-[#2ab4c0] rounded-xl px-5 py-4 mb-5">
                <h1 class="text-lg font-bold text-white">Profile</h1>
                <p class="text-xs text-white/90 mt-1">Manage your personal information and preferences.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-[#2ab4c0]/30 bg-[#2ab4c0]/10 px-4 py-3 text-sm text-gray-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($saveSuccess)
                <div class="mb-4 rounded-lg border border-[#2ab4c0]/30 bg-[#2ab4c0]/10 px-4 py-3 text-sm text-gray-800">
                    {{ $saveSuccess }}
                </div>
            @endif

            @php
                $profileTab = request()->query('tab') === 'family' ? 'family' : 'personal';
            @endphp

            <div
                x-data="{
                    tab: @js($profileTab),
                    showDeleteModal: false,
                    pendingDeleteId: null,
                    openDeleteModal(id) {
                        this.pendingDeleteId = id;
                        this.showDeleteModal = true;
                        document.body.classList.add('overflow-hidden');
                    },
                    closeDeleteModal() {
                        this.showDeleteModal = false;
                        this.pendingDeleteId = null;
                        document.body.classList.remove('overflow-hidden');
                    }
                }"
                @keydown.escape.window="showDeleteModal && closeDeleteModal()"
                @profile-saved.window="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="w-full"
            >
                {{-- Tabs --}}
                <style>
                    /* Scope CSS to profile tabs only */
                    .profile-tabs {
                        display: flex;
                        position: relative;
                        padding: 0.25rem;
                        border-radius: 50px;
                        background-color: #F5F3F1;
                        width: 100%;
                        max-width: none;
                        margin: 0 0 1.25rem 0;
                        box-sizing: border-box;
                    }

                    .profile-tabs * {
                        z-index: 2;
                    }

                    .profile-tabs input[type=radio] {
                        display: none;
                    }

                    .profile-tabs input[type=radio]:checked+label {
                        color: #F5F3F1;
                    }

                    .profile-tabs .tab {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        flex: 1 1 0;
                        min-width: 0;
                        height: 44px;
                        width: auto;
                        font-size: 13px;
                        font-weight: 700;
                        border-radius: 50px;
                        cursor: pointer;
                        transition: color 0.15s ease-in;
                        color: #6b7280;
                        user-select: none;
                    }

                    .profile-tabs .glider {
                        position: absolute;
                        display: flex;
                        height: 44px;
                        width: 50%;
                        max-width: calc(100% - 0.5rem);
                        box-sizing: border-box;
                        background-image: linear-gradient(to right, #2ab4c0, #239ea9);
                        z-index: 1;
                        border-radius: 80px;
                        transition: transform 0.25s ease-out;
                        box-shadow: 0 5px 13px rgba(42, 180, 192, 0.35);
                        top: 0.25rem;
                        left: 0.25rem;
                    }

                    /* Move glider by its own width = one tab slot */
                    .profile-tabs #ptabs-1:checked~.glider {
                        transform: translateX(0);
                    }

                    .profile-tabs #ptabs-2:checked~.glider {
                        transform: translateX(100%);
                    }
                </style>

                <div class="profile-tabs">
                    <input type="radio" id="ptabs-1" name="profile-tabs" @checked($profileTab === 'personal') @click="tab = 'personal'">
                    <label for="ptabs-1" class="tab" @click="tab = 'personal'">Personal Info</label>

                    <input type="radio" id="ptabs-2" name="profile-tabs" @checked($profileTab === 'family') @click="tab = 'family'">
                    <label for="ptabs-2" class="tab" @click="tab = 'family'">Family</label>

                    <div class="glider"></div>
                </div>

                {{-- Personal Info --}}
                <div x-show="tab === 'personal'" x-cloak>
                    <div class="space-y-6">
                        @include('livewire.profile.partials.personal-info-fields')

                        {{-- Saved Travelers --}}
                        <section class="bg-white rounded-xl border border-gray-200/70 p-4 shadow-sm">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Saved Travelers</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-800 mb-1">Single traveler</div>
                                    <div class="text-xs text-gray-500">Save one traveler profile for faster booking.</div>
                                    <button type="button"
                                        class="mt-3 w-full px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                                        Add / Manage Single Traveler
                                    </button>
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-gray-800 mb-1">Family traveler profiles</div>
                                    <div class="text-xs text-gray-500">Use the Family tab to add travelers.</div>
                                    <button type="button"
                                        class="mt-3 w-full px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors"
                                        @click="tab = 'family'; document.getElementById('ptabs-2').click();">
                                        Open Family tab
                                    </button>
                                </div>
                            </div>
                        </section>

                        {{-- For app specifically --}}
                        <section class="bg-white rounded-xl border border-gray-200/70 p-4 shadow-sm">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">For your app specifically</h2>
                            <div class="text-xs text-gray-600 leading-relaxed">
                                Save and reuse these preferences during flight search, seat selection, and meal recommendations.
                            </div>
                        </section>

                        {{-- Save Personal Info --}}
                        <div class="flex items-center gap-3 pt-1">
                            <button type="button"
                                wire:click="savePersonalInfo"
                                class="ml-auto px-5 py-2 rounded-lg bg-[#2ab4c0] text-white hover:bg-[#239ea9] transition-colors text-sm font-semibold">
                                Save Personal Info
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Family tab --}}
                <div x-show="tab === 'family'" x-cloak>
                    <div class="relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm ring-1 ring-gray-900/[0.04] sm:p-6">
                        <div class="pointer-events-none absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-[#2ab4c0] to-[#239ea9]" aria-hidden="true"></div>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between sm:gap-4 pl-2 sm:pl-3">
                            <div>
                                <div class="text-base font-bold tracking-tight text-gray-900">Family traveler profiles</div>
                                <p class="mt-1 max-w-xl text-xs leading-relaxed text-gray-500">
                                    Saved travelers on your account. On small screens, scroll sideways to see all columns.
                                </p>
                            </div>

                            <a href="{{ route('family.create') }}"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#2ab4c0]/25 transition hover:shadow-lg hover:brightness-105">
                                <svg class="h-4 w-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add traveler
                            </a>
                        </div>

                        @if ($familyMembers->isEmpty())
                            <div class="mt-6 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/50 py-12 text-center">
                                <p class="text-sm text-gray-600">No family travelers yet.</p>
                                <p class="mt-1 text-xs text-gray-500">Use <span class="font-semibold text-[#239ea9]">Add traveler</span> to create one.</p>
                            </div>
                        @else
                            <div class="mt-6 overflow-hidden rounded-xl border border-gray-200/90 bg-gray-50/40 shadow-inner">
                                <div class="family-table-scroll overflow-x-auto overscroll-x-contain [-webkit-overflow-scrolling:touch]">
                                    <table class="w-full min-w-[62rem] border-collapse text-sm">
                                        <thead>
                                            <tr class="border-b border-[#2ab4c0]/25 bg-gradient-to-r from-[#2ab4c0]/14 via-white to-gray-50">
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">First name</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Last name</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Email</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Phone</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">DOB</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Gender</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Nationality</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Passport #</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Expiry</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Issuing country</th>
                                                <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-gray-600">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @foreach ($familyMembers as $m)
                                                @php
                                                    $expiryPast = $m->expiry_date && $m->expiry_date->toDateString() < now()->toDateString();
                                                @endphp
                                                <tr class="group transition-colors duration-150 hover:bg-[#2ab4c0]/[0.07]">
                                                    <td class="max-w-[11rem] px-4 py-3.5 align-middle text-gray-800">
                                                        <span class="line-clamp-2 font-medium" title="{{ $m->first_name }}">{{ $m->first_name ?: '—' }}</span>
                                                    </td>
                                                    <td class="max-w-[11rem] px-4 py-3.5 align-middle text-gray-800">
                                                        <span class="line-clamp-2" title="{{ $m->last_name }}">{{ $m->last_name ?: '—' }}</span>
                                                    </td>
                                                    <td class="max-w-[14rem] px-4 py-3.5 align-middle">
                                                        <span class="block truncate text-gray-700" title="{{ $m->email }}">{{ $m->email ?: '—' }}</span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-4 py-3.5 align-middle text-gray-700">{{ $m->phone ?: '—' }}</td>
                                                    <td class="whitespace-nowrap px-4 py-3.5 align-middle tabular-nums text-gray-700">{{ $m->dob?->format('M j, Y') ?? '—' }}</td>
                                                    <td class="max-w-[8rem] px-4 py-3.5 align-middle text-gray-700">
                                                        <span class="line-clamp-2" title="{{ $m->gender }}">{{ $m->gender ?: '—' }}</span>
                                                    </td>
                                                    <td class="max-w-[9rem] px-4 py-3.5 align-middle text-gray-700">
                                                        <span class="line-clamp-2" title="{{ $m->nationality }}">{{ $m->nationality ?: '—' }}</span>
                                                    </td>
                                                    <td class="max-w-[10rem] px-4 py-3.5 align-middle font-mono text-[13px] text-gray-800">
                                                        <span class="block truncate" title="{{ $m->passport_number }}">{{ $m->passport_number ?: '—' }}</span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-4 py-3.5 align-middle tabular-nums">
                                                        @if ($m->expiry_date)
                                                            <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-[13px] font-medium {{ $expiryPast ? 'bg-amber-50 text-amber-800 ring-1 ring-amber-200/80' : 'bg-emerald-50/80 text-emerald-800 ring-1 ring-emerald-200/60' }}" title="{{ $expiryPast ? 'Passport expiry date has passed' : 'Valid expiry' }}">
                                                                @if ($expiryPast)
                                                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                                @endif
                                                                {{ $m->expiry_date->format('M j, Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="max-w-[12rem] px-4 py-3.5 align-middle text-gray-700">
                                                        <span class="line-clamp-2" title="{{ $m->issuing_country }}">{{ $m->issuing_country ?: '—' }}</span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-4 py-3.5 align-middle text-right">
                                                        <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                                            <a href="{{ route('family.edit', $m->id) }}"
                                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-[#2ab4c0]/50 hover:bg-[#2ab4c0]/5 hover:text-[#239ea9]">
                                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                                Edit
                                                            </a>
                                                            <button type="button"
                                                                @click="openDeleteModal({{ $m->id }})"
                                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 shadow-sm transition hover:border-red-300 hover:bg-red-100">
                                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Custom delete confirmation (theme: teal accent, no browser alert) --}}
                <div
                    x-show="showDeleteModal"
                    x-cloak
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[200] flex items-center justify-center p-4"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="profile-delete-modal-title"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/50 backdrop-blur-[2px]"
                        @click="closeDeleteModal()"
                    ></div>

                    <div
                        x-show="showDeleteModal"
                        x-transition:enter="ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl"
                        @click.stop
                    >
                        <div class="bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-5 py-4">
                            <h3 id="profile-delete-modal-title" class="text-base font-bold text-white">
                                Remove family traveler?
                            </h3>
                            <p class="mt-1 text-xs text-white/90">
                                This will permanently remove this profile from your saved travelers.
                            </p>
                        </div>
                        <div class="px-5 py-4 space-y-3">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Are you sure you want to delete this family traveler? You can add them again later from <span class="font-semibold text-gray-800">Create</span>.
                            </p>
                            <div class="rounded-xl border border-[#2ab4c0]/20 bg-[#2ab4c0]/5 px-3 py-2 text-xs text-gray-700">
                                <span class="font-semibold text-[#239ea9]">Note:</span> this action cannot be undone.
                            </div>
                        </div>
                        <div class="flex flex-col-reverse gap-2 border-t border-gray-100 bg-gray-50/80 px-5 py-4 sm:flex-row sm:justify-end sm:gap-3">
                            <button type="button"
                                @click="closeDeleteModal()"
                                class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="button"
                                @click="if (pendingDeleteId) { $wire.deleteFamilyMember(pendingDeleteId) }; closeDeleteModal()"
                                class="w-full sm:w-auto rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/40">
                                    Yes, delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-gray-600 mt-4 mb-10" style="font-size:10px">
        &copy; 2024 FlightBook &middot;
        <a href="{{ route('privacy') }}" class="hover:text-gray-600 transition-colors">Privacy Policy</a> &middot;
        <a href="{{ route('terms') }}" class="hover:text-gray-600 transition-colors">Terms of Service</a>
    </p>
</div>

