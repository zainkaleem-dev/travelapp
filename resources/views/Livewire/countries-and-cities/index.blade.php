<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Countries & Cities</h1>
                    </div>
                    <div class="mt-1">
                        @if($activeTab === 'countries')
                            <a href="{{ route('admin.countries.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                Add Country
                            </a>
                        @else
                            <a href="{{ route('admin.cities.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                                Add City
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Standard Sub-Navigation --}}
            <div class="px-6 pt-4 border-b border-gray-200 bg-white">
                <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-xs font-semibold w-full">
                    <button wire:click="setTab('countries')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t transition-colors whitespace-nowrap {{ $activeTab === 'countries' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A11.954 11.954 0 0 1 12 16.5c-2.998 0-5.74-1.1-7.843-2.918m0 0A8.959 8.959 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                        </svg>
                        Countries
                    </button>
                    <button wire:click="setTab('cities')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 flex-shrink-0 rounded-t transition-colors whitespace-nowrap {{ $activeTab === 'cities' ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Cities
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                @if ($crudMessage)
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ $crudMessage }}
                    </div>
                @endif

                @if($activeTab === 'countries')
                    <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-[#2ab4c0]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Dial Code</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-white uppercase tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $country)
                                    <tr class="border-t border-gray-100">
                                        <td class="px-4 py-3 text-gray-900">{{ $country->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $country->code }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $country->dial_code }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.countries.view', $country->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.countries.edit', $country->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteCountry" data-args='[{{ $country->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will permanently delete this country."
                                                    data-confirm-button-text="Yes, delete it"
                                                    data-done-title="Deleted!"
                                                    data-done-text="Country has been deleted."
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
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No countries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-[#2ab4c0]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Country</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide">Code</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-white uppercase tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr class="border-t border-gray-100">
                                        <td class="px-4 py-3 text-gray-900">{{ $city->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $city->country->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $city->code }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.cities.view', $city->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c-5.25 0-9.75 3.72-11.25 9 1.5 5.28 6 9 11.25 9s9.75-3.72 11.25-9c-1.5-5.28-6-9-11.25-9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5a3 3 0 100-6 3 3 0 000 6z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                    class="group inline-flex items-center justify-center p-1 rounded-lg border border-gray-200 bg-transparent text-black text-xs font-semibold transition-colors hover:border-gray-400"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button" x-on:click="appSwalFromDataset($el, $wire)"
                                                    data-action="deleteCity" data-args='[{{ $city->id }}]'
                                                    data-confirm-title="Are you sure?"
                                                    data-confirm-text="This will permanently delete this city."
                                                    data-confirm-button-text="Yes, delete it"
                                                    data-done-title="Deleted!"
                                                    data-done-text="City has been deleted."
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
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No cities found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
