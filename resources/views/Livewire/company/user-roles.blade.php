@php($isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System'))
<div class="w-full px-1 py-1 flex flex-col gap-3">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Users and Roles</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'users-roles'])
    </div>

    <div class="overflow-visible rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] text-green-800 uppercase font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase mb-4">Users</h2>

                <div class="overflow-visible">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide rounded-ss-lg">Name</th>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide">Role</th>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide">Notes</th>
                                <th class="px-4 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide rounded-se-lg">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-b border-gray-200 bg-white">
                                    <td class="px-4 py-2 text-[11px] font-semibold text-gray-900">{{ $user->display_name }}</td>
                                    <td class="px-4 py-2">
                                        <div class="relative" x-data="{ open: false, selected: @js($selectedRoles[$user->id] ?? '') }" @keydown.escape.window="open = false" @click.outside="open = false">
                                            <button type="button" class="input-field flex items-center justify-between text-left !pl-3" @click="open = !open">
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
                                            <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" wire:model.defer="userNotes.{{ $user->id }}"
                                            class="input-field !pl-3"
                                            placeholder="Add note for this user">
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="button" wire:click="saveUserSettings({{ $user->id }})"
                                            class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] uppercase">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-[11px] text-gray-500">No users found for this {{ $isSuperAdmin ? 'organization' : 'partner' }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

