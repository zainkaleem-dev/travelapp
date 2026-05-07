<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Users and Roles</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'users-roles'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] text-green-800 uppercase font-bold tracking-wider">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-6">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Team & Access Management</p>

                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <table class="w-full text-sm border-separate border-spacing-0">
                        <thead class="bg-[#2ab4c0]">
                            <tr>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wider">Role</th>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wider">Notes</th>
                                <th class="px-4 py-2 text-right text-[11px] font-bold text-white uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $user->display_name }}</td>
                                    <td class="px-4 py-3">
                                        <div class="relative" x-data="{ open: false, selected: @js($selectedRoles[$user->id] ?? '') }" @keydown.escape.window="open = false" @click.outside="open = false">
                                            <button type="button" class="input-field flex items-center justify-between text-left !pl-3 !text-[11px] font-bold uppercase tracking-tight" @click="open = !open">
                                                <span x-text="selected === '' ? 'Select role...' : selected"></span>
                                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel !top-full !bottom-auto mt-1 z-[80]">
                                                <button type="button" class="admin-menu-item"
                                                    :class="{ 'is-active': selected === '' }"
                                                    @click="selected = ''; open = false; $wire.set('selectedRoles.{{ $user->id }}', '')">
                                                    Select role...
                                                </button>
                                                @foreach($roles as $role)
                                                    <button type="button" class="admin-menu-item"
                                                        :class="{ 'is-active': selected === '{{ $role->name }}' }"
                                                        @click="selected = '{{ $role->name }}'; open = false; $wire.set('selectedRoles.{{ $user->id }}', '{{ $role->name }}')">
                                                        {{ $role->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('selectedRoles.' . $user->id)
                                            <p class="mt-1 text-[10px] font-bold text-red-500 uppercase tracking-tight">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" wire:model.defer="userNotes.{{ $user->id }}"
                                            class="input-field !pl-3 !text-[11px] font-medium tracking-tight"
                                            placeholder="Add note for this user">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" wire:click="saveUserSettings({{ $user->id }})"
                                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-1.5 text-[11px] font-bold text-white hover:bg-[#229aa4] uppercase tracking-wider transition-colors shadow-sm">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-[11px] text-gray-500 font-bold uppercase tracking-widest">No users found for this organization</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

