<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Airports</h1>
                    </div>
                    <a href="{{ route('admin.airports.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                        Add Airport
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="p-6">
                @if ($crudMessage)
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ $crudMessage }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-[#2ab4c0]">
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group rounded-ss-2xl" wire:click="sort('name')">
                                    <div class="flex items-center gap-2">
                                        <span>Name</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'name' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'name' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'name' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('city_id')">
                                    <div class="flex items-center gap-2">
                                        <span>City</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'city_id' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'city_id' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'city_id' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('code')">
                                    <div class="flex items-center gap-2">
                                        <span>IATA</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'code' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'code' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'code' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide cursor-pointer group" wire:click="sort('created_at')">
                                    <div class="flex items-center gap-2">
                                        <span>Added on</span>
                                        <div class="flex flex-col transition-opacity {{ $sortBy === 'created_at' ? 'opacity-100' : 'opacity-40' }}">
                                            <svg class="w-3.5 h-3.5 {{ $sortBy === 'created_at' && $sortDirection === 'asc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-3.5 h-3.5 -mt-1 {{ $sortBy === 'created_at' && $sortDirection === 'desc' ? 'text-white' : 'text-white/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-2 text-end text-[11px] font-bold text-white uppercase tracking-wide rounded-se-2xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($airports as $airport)
                                <tr class="border-b border-gray-200 transition-colors hover:bg-blue-50">
                                    <td class="px-6 py-2 text-[11px] font-semibold text-gray-900">{{ $airport->name }}</td>
                                    <td class="px-6 py-2 text-[11px] text-gray-600">{{ $airport->city->name }}, {{ $airport->city->country->code }}</td>
                                    <td class="px-6 py-2 text-[11px] text-gray-600 font-mono">{{ $airport->code }}</td>
                                    <td class="px-6 py-2 text-[11px] text-gray-600 font-medium">{{ $airport->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-2 text-end">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.airports.view', $airport->id) }}"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.airports.edit', $airport->id) }}"
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                data-action="deleteAirport" data-args='[{{ $airport->id }}]'
                                                data-confirm-title="Are you sure?"
                                                data-confirm-text="This will permanently delete this airport."
                                                data-confirm-button-text="Yes, delete it"
                                                data-done-title="Deleted!"
                                                data-done-text="Airport has been deleted."
                                                class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-[11px] text-gray-500">No airports found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
