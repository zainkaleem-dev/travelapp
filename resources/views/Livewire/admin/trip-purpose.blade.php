<div class="w-full">
    <div class="w-full py-6 px-3 sm:px-4 lg:px-6">
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
                <div class="mb-6 rounded-xl bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-5 py-4">
                    <h1 class="text-lg font-bold text-white">Trip Purpose</h1>
                    <p class="mt-1 text-xs text-white/90">Choose how you usually travel. You can change this anytime.</p>
                </div>

                @if ($saveMessage)
                    <div class="mb-4 rounded-lg border border-[#2ab4c0]/30 bg-[#2ab4c0]/10 px-4 py-3 text-sm text-gray-800">
                        {{ $saveMessage }}
                    </div>
                @endif

                <div class="rounded-xl border border-gray-200/80 bg-gray-50/50 p-4 sm:p-5">
                    <label for="trip_type" class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        Trip type
                    </label>
                    <select id="trip_type"
                        wire:model.defer="trip_type"
                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-3 text-sm text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25">
                        <option value="">Select…</option>
                        <option value="business_trip">Business trip</option>
                        <option value="personal_trip">Personal trip</option>
                        <option value="annual_trip">Annual trip</option>
                        <option value="guest">Guest</option>
                    </select>
                    @error('trip_type')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-5 flex justify-end">
                        <button type="button"
                            wire:click="saveSettings"
                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#2ab4c0]/20 transition hover:brightness-105">
                            Save settings
                        </button>
                    </div>
                </div>
        </div>
    </div>
</div>

