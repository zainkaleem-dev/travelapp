<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4" x-data="{ tab: @entangle('tab') }">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Add New User</h1>
                    <p class="text-[11px] text-gray-500 mt-1">Create a login account for the selected company</p>
                </div>
                <a href="{{ route('users.index', ['companyId' => $company_id]) }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        @if(!$isTmc)
        {{-- Standard Sub-Navigation --}}
        <div class="px-6 pt-2 border-b border-gray-200 bg-white">
            <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-[11px] font-semibold w-full">
                <button type="button" @click="tab = 'personal'"
                    class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t-lg transition-colors whitespace-nowrap"
                    :class="tab === 'personal' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900'">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Personal Information
                </button>
                <button type="button" @click="tab = 'family'"
                    class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t-lg transition-colors whitespace-nowrap"
                    :class="tab === 'family' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900'">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4m10 0H7" />
                    </svg>
                    Family Members
                </button>
            </div>
        </div>
        @endif

        <div class="px-6 py-4">

            <div x-show="tab === 'personal'">
                <form wire:submit.prevent="save">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Identity & Organization
                    </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @if(count($companies) > 0)
                        <div>
                            <label class="field-label">Select Company <span class="text-red-500">*</span></label>
                            <div class="relative"
                                x-data="{ open: false, selected: @entangle('company_id').live, labels: @js($companies->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span
                                        x-text="!selected ? '-- Choose Company --' : (labels[selected] ?? '-- Choose Company --')"></span>
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
                                            @click="selected = '{{ $company->id }}'; open = false">{{ $company->name }}</button>
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
                            <div wire:key="create-branch-dropdown-{{ $company_id ?? 'none' }}-{{ count($branches) }}"
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
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Account & Security</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="input-field"
                                placeholder="john.doe@example.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div x-data="{ showPassword: false }">
                            <label class="field-label">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" wire:model="password" class="input-field pr-10"
                                    placeholder="••••••••">
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
                    <span wire:loading.remove>Create User</span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>
        </form>
    </div>

    @if(!$isTmc)
    <div x-show="tab === 'family'" x-cloak class="space-y-6">
        @if (!empty($familyMembers))
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider text-[11px]">Name</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider text-[11px]">Email</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700 uppercase tracking-wider text-[11px]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($familyMembers as $index => $fm)
                            <tr>
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $fm['first_name'] }} {{ $fm['last_name'] }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $fm['email'] ?: '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" wire:click="removeFamilyMember({{ $index }})" class="text-red-600 hover:text-red-800 font-bold text-[11px] uppercase tracking-wider">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Add New Family Member</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="field-label">First Name *</label>
                    <input type="text" wire:model="f_first_name" class="input-field" placeholder="First Name">
                    @error('f_first_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Last Name *</label>
                    <input type="text" wire:model="f_last_name" class="input-field" placeholder="Last Name">
                    @error('f_last_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Email</label>
                    <input type="email" wire:model="f_email" class="input-field" placeholder="Email">
                    @error('f_email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Phone</label>
                    <input type="tel" wire:model="f_phone" class="input-field" placeholder="Phone">
                    @error('f_phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Date of Birth</label>
                    <input type="date" wire:model="f_dob" class="input-field">
                    @error('f_dob') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Gender</label>
                    <select wire:model="f_gender" class="input-field">
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="field-label">Passport number</label>
                        <input type="text" wire:model="f_passport_number" class="input-field" placeholder="Passport number">
                    </div>
                    <div>
                        <label class="field-label">Expiry date</label>
                        <input type="date" wire:model="f_expiry_date" class="input-field">
                    </div>
                    <div>
                        <label class="field-label">Issuing country</label>
                        <input type="text" wire:model="f_issuing_country" class="input-field" placeholder="Issuing country">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" wire:click="addFamilyMember"
                    class="rounded-lg bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#2ab4c0]/20 transition hover:scale-[1.02] active:scale-[0.98]">
                    Add Family Member to List
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
</div>