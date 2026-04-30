<div class="w-full px-1 py-1 flex flex-col gap-6">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Add City</h1>
                <a href="{{ route('admin.countries-and-cities') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Country</label>
                            <select wire:model.defer="country_id"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-[11px] text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                                @endforeach
                            </select>
                            @error('country_id') <p class="mt-1 text-[11px] font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">City Name</label>
                            <input type="text" wire:model.defer="city_name" placeholder="New York"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-[11px] text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                            @error('city_name') <p class="mt-1 text-[11px] font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">City Code</label>
                            <input type="text" wire:model.defer="city_code" placeholder="NYC"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-[11px] text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                            @error('city_code') <p class="mt-1 text-[11px] font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.countries-and-cities') }}"
                            class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="button" wire:click="save"
                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-3 py-1.5 text-[11px] font-semibold text-white shadow-md shadow-[#2ab4c0]/20 transition hover:brightness-105">
                            Save City
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
