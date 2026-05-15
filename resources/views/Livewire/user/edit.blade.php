<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Edit User</h1>
                    <p class="text-[11px] text-gray-500 mt-1">Update account details for <span
                            class="font-bold text-gray-700">{{ $user->display_name }}</span></p>
                </div>
                <a href="{{ route('users.index', ['companyId' => $company_id]) }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <div class="px-6 py-4" x-data="{ 
            tab: @entangle('tab'),
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
        @keydown.escape.window="showDeleteModal && closeDeleteModal()">
            <style>
                .profile-tabs {
                    display: flex;
                    position: relative;
                    padding: 0.25rem;
                    border-radius: 50px;
                    background-color: #F5F3F1;
                    width: 100%;
                    max-width: 400px;
                    margin: 0 0 1.5rem 0;
                    box-sizing: border-box;
                }
                .profile-tabs * { z-index: 2; }
                .profile-tabs input[type=radio] { display: none; }
                .profile-tabs input[type=radio]:checked + label { color: #F5F3F1; }
                .profile-tabs .tab {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex: 1;
                    height: 40px;
                    font-size: 13px;
                    font-weight: 700;
                    border-radius: 50px;
                    cursor: pointer;
                    transition: color 0.15s ease-in;
                    color: #6b7280;
                }
                .profile-tabs .glider {
                    position: absolute;
                    height: 40px;
                    width: calc(50% - 0.25rem);
                    background-image: linear-gradient(to right, #2ab4c0, #239ea9);
                    z-index: 1;
                    border-radius: 80px;
                    transition: transform 0.25s ease-out;
                    box-shadow: 0 5px 13px rgba(42, 180, 192, 0.35);
                    top: 0.25rem;
                    left: 0.25rem;
                }
                .profile-tabs #ptabs-1:checked ~ .glider { transform: translateX(0); }
                .profile-tabs #ptabs-2:checked ~ .glider { transform: translateX(100%); }
            </style>

            @if(!$isTmc)
            <div class="profile-tabs">
                <input type="radio" id="ptabs-1" name="profile-tabs" value="personal" x-model="tab">
                <label for="ptabs-1" class="tab">Personal</label>

                <input type="radio" id="ptabs-2" name="profile-tabs" value="family" x-model="tab">
                <label for="ptabs-2" class="tab">Family</label>

                <div class="glider"></div>
            </div>
            @endif

            <div x-show="tab === 'personal'">
                <form wire:submit.prevent="save">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Identity & Organization
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @if(count($companies) > 0)
                        <div>
                            <label class="field-label">Select Company <span class="text-red-500">*</span></label>
                            <div class="relative"
                                x-data="{ 
                                    open: false, 
                                    selected: @entangle('company_id').live, 
                                    labels: @js($companies->pluck('name', 'id')->toArray()) 
                                }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span
                                        x-text="!selected ? '-- Choose Company --' : (labels[String(selected)] ?? '-- Choose Company --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }"
                                        @click="selected = ''; open = false">-- Choose Company --</button>
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': String(selected) === '{{ $company->id }}' }"
                                            @click="selected = {{ $company->id }}; open = false">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                        @endif

                        <div>
                            <label class="field-label">Select Branch <span class="text-red-500">*</span></label>
                            <div wire:key="edit-branch-dropdown-{{ $company_id ?? 'none' }}-{{ count($branches) }}"
                                class="relative"
                                x-data="{ open: false, selected: @entangle('branch_id').live, labels: @js(collect($branches)->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left"
                                    @click="if (!{{ empty($branches) ? 'true' : 'false' }}) open = !open"
                                    :class="{ 'opacity-60 cursor-not-allowed': {{ empty($branches) ? 'true' : 'false' }} }">
                                    <span
                                        x-text="!selected ? '-- Choose Branch --' : (labels[selected] ?? '-- Choose Branch --')"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }"
                                        @click="selected = ''; open = false">-- Choose Branch --</button>
                                    @foreach($branches as $branch)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': String(selected) === '{{ $branch->id }}' }"
                                            @click="selected = '{{ $branch->id }}'; open = false">{{ $branch->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @if(empty($branches) && $company_id)
                                <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">This company has no branches
                                    yet.</p>
                            @elseif(!$company_id && auth()->user()->hasRole('Super Admin'))
                                <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">Please select a company first.
                                </p>
                            @endif
                            @error('branch_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">First Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="first_name" class="input-field" placeholder="John">
                            @error('first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="input-field" placeholder="Quincy">
                            @error('middle_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="last_name" class="input-field" placeholder="Doe">
                            @error('last_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Account Security -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Account & Security</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="input-field"
                                placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>


                        <div class="md:col-span-2 pt-4 border-t border-gray-100 mt-4" x-data="{ showPassword: false }">
                            <label class="field-label">Change Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" wire:model="password" class="input-field pr-10"
                                    placeholder="Leave blank to keep current password">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-gray-400 hover:text-gray-600"
                                    :title="showPassword ? 'Hide password' : 'Show password'">
                                    <svg x-show="!showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368M9.88 9.88a3 3 0 104.24 4.24" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6.1 6.1A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.411M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1.5 text-[10px] text-red-500 font-medium">Only fill this if you want to reset
                                the user's password.</p>
                            @error('password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>

                @if(!$isTmc)
                <!-- Section 3: Additional Personal Info -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Additional Personal Info</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Phone</label>
                            <input type="tel" wire:model="phone" class="input-field" placeholder="Phone number">
                            @error('phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Date of Birth</label>
                            <input type="date" wire:model="dob" class="input-field">
                            @error('dob') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Gender</label>
                            <select wire:model="gender" class="input-field">
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                                <option>Prefer not to say</option>
                            </select>
                            @error('gender') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Nationality</label>
                            <input type="text" wire:model="nationality" class="input-field" placeholder="Nationality">
                            @error('nationality') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Travel Documents -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Travel Documents</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">Passport number</label>
                            <input type="text" wire:model="passport_number" class="input-field" placeholder="Passport number">
                            @error('passport_number') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Expiry date</label>
                            <input type="date" wire:model="expiry_date" class="input-field">
                            @error('expiry_date') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Issuing country</label>
                            <input type="text" wire:model="issuing_country" class="input-field" placeholder="Issuing country">
                            @error('issuing_country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Preferences -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Preferences</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Purpose of travel</label>
                            <select wire:model="purpose_of_travel" class="input-field">
                                <option value="">Select</option>
                                <option>Business</option>
                                <option>Leisure</option>
                                <option>Education</option>
                                <option>Visiting family</option>
                                <option>Other</option>
                            </select>
                            @error('purpose_of_travel') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Seat preference</label>
                            <select wire:model="seat_preference" class="input-field">
                                <option value="">Select</option>
                                <option>Aisle</option>
                                <option>Window</option>
                                <option>Middle</option>
                                <option>Any</option>
                            </select>
                            @error('seat_preference') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Meal preference</label>
                            <select wire:model="meal_preference" class="input-field">
                                <option value="">Select</option>
                                <option>Regular</option>
                                <option>Vegetarian</option>
                                <option>Vegan</option>
                                <option>Halal</option>
                                <option>Kosher</option>
                            </select>
                            @error('meal_preference') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Preferred cabin</label>
                            <select wire:model="preferred_cabin" class="input-field">
                                <option value="">Select</option>
                                <option>Economy</option>
                                <option>Premium Economy</option>
                                <option>Business</option>
                                <option>First</option>
                            </select>
                            @error('preferred_cabin') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Preferred airline</label>
                            <input type="text" wire:model="preferred_airline" class="input-field" placeholder="Airline name">
                            @error('preferred_airline') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    <span wire:loading.remove>Update User</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>

    @if(!$isTmc)
    <div x-show="tab === 'family'" x-cloak>
        <div class="relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm ring-1 ring-gray-900/[0.04] sm:p-6">
            <div class="pointer-events-none absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-[#2ab4c0] to-[#239ea9]" aria-hidden="true"></div>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between sm:gap-4 pl-2 sm:pl-3">
                <div>
                    <div class="text-base font-bold tracking-tight text-gray-900">Family traveler profiles</div>
                    <p class="mt-1 max-w-xl text-xs leading-relaxed text-gray-500">
                        Saved travelers for <span class="font-bold text-gray-700">{{ $user->display_name }}</span>.
                    </p>
                </div>

                <a href="{{ route('family.create', ['userId' => $userId]) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#2ab4c0]/25 transition hover:shadow-lg hover:brightness-105">
                    <svg class="h-4 w-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add traveler
                </a>
            </div>

            @if ($familyMembers->isEmpty())
                <div class="mt-6 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/50 py-12 text-center">
                    <p class="text-sm text-gray-600">No family travelers yet.</p>
                </div>
            @else
                <div class="mt-6 overflow-hidden rounded-xl border border-gray-200/90 bg-gray-50/40 shadow-inner">
                    <div class="overflow-x-auto overscroll-x-contain">
                        <table class="w-full min-w-[62rem] border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-[#2ab4c0]/25 bg-gradient-to-r from-[#2ab4c0]/14 via-white to-gray-50">
                                    <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">First name</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Last name</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Email</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Phone</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($familyMembers as $m)
                                    <tr class="group transition-colors duration-150 hover:bg-[#2ab4c0]/[0.07]">
                                        <td class="px-4 py-3.5 text-gray-800">{{ $m->first_name ?: '—' }}</td>
                                        <td class="px-4 py-3.5 text-gray-800">{{ $m->last_name ?: '—' }}</td>
                                        <td class="px-4 py-3.5 text-gray-700">{{ $m->email ?: '—' }}</td>
                                        <td class="px-4 py-3.5 text-gray-700">{{ $m->phone ?: '—' }}</td>
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('family.edit', $m->id) }}"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-[#2ab4c0]/50 hover:bg-[#2ab4c0]/5 hover:text-[#239ea9]">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    @click="openDeleteModal({{ $m->id }})"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 shadow-sm transition hover:border-red-300 hover:bg-red-100">
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
    @endif

    {{-- Delete Modal --}}
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
    >
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-[2px]" @click="closeDeleteModal()"></div>
        <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
            <div class="bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-5 py-4">
                <h3 class="text-base font-bold text-white">Remove family traveler?</h3>
            </div>
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600">Are you sure you want to delete this family traveler?</p>
            </div>
            <div class="flex justify-end gap-3 bg-gray-50 px-5 py-4">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                <button type="button" @click="$wire.deleteFamilyMember(pendingDeleteId); closeDeleteModal()" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white">Delete</button>
            </div>
        </div>
    </div>
</div>