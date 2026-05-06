<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Add Airport</h1>
                    <a href="{{ route('admin.airports') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Airport Name</label>
                             <input type="text" wire:model.defer="name" placeholder="Heathrow Airport"
                                 class="input-field">
                             @error('name') <p class="mt-1 text-[11px] font-medium text-red-600 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">City</label>
                             <select wire:model.defer="city_id"
                                 class="input-field">
                                 <option value="">Select City</option>
                                 @foreach($cities as $city)
                                     <option value="{{ $city->id }}">{{ $city->name }} ({{ $city->country->code }})</option>
                                 @endforeach
                             </select>
                             @error('city_id') <p class="mt-1 text-[11px] font-medium text-red-600 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">IATA Code</label>
                                 <input type="text" wire:model.defer="code" placeholder="LHR"
                                     class="input-field">
                                 @error('code') <p class="mt-1 text-[11px] font-medium text-red-600 uppercase">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">ICAO Code</label>
                                 <input type="text" wire:model.defer="icao_code" placeholder="EGLL"
                                     class="input-field">
                                 @error('icao_code') <p class="mt-1 text-[11px] font-medium text-red-600 uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                     <div class="mt-4 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.airports') }}"
                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="button" wire:click="save"
                            class="inline-flex items-center rounded-lg bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-3 py-1.5 text-[11px] font-semibold text-white shadow-md shadow-[#2ab4c0]/20 transition hover:brightness-105">
                            Save Airport
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
