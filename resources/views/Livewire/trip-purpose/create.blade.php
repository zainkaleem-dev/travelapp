<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Add Trip Purpose</h1>
                    <a href="{{ route('admin.trip-purpose') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Trip Purpose</label>
                            <input type="text"
                                wire:model.defer="purpose_label"
                                placeholder="Business trip"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                            @error('purpose_label')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Code</label>
                            <input type="text"
                                wire:model.defer="purpose_key"
                                placeholder="business_trip"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                            @error('purpose_key')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.trip-purpose') }}"
                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="button"
                            wire:click="save"
                            class="inline-flex items-center rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                            Save Trip
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
