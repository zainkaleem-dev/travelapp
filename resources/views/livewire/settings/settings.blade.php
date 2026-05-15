<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Settings</h1>
            <p class="text-[11px] font-bold text-gray-500 uppercase mt-1">Choose how you usually travel. You can change this anytime.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            @if ($saveMessage)
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] font-bold text-green-800 uppercase">
                    {{ $saveMessage }}
                </div>
            @endif

            <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase mb-4">Travel Preferences</h2>
                <div class="max-w-sm">
                    <label for="trip_type" class="field-label">
                        Trip Type
                    </label>
                    <div class="relative" x-data="{ open: false, selected: @js($trip_type ?? '') }"
                        @keydown.escape.window="open = false" @click.outside="open = false">
                        <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                            <span x-text="selected === '' ? 'Select trip type...' : selected"></span>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                            <button type="button" class="admin-menu-item"
                                :class="{ 'is-active': selected === '' }"
                                @click="selected = ''; open = false; $wire.set('trip_type', '')">Select trip type...</button>
                            @foreach($tripPurposeOptions as $purposeKey => $purposeLabel)
                                <button type="button" class="admin-menu-item"
                                    :class="{ 'is-active': selected === '{{ $purposeLabel }}' }"
                                    @click="selected = '{{ $purposeLabel }}'; open = false; $wire.set('trip_type', '{{ $purposeKey }}')">{{ $purposeLabel }}</button>
                            @endforeach
                        </div>
                    </div>
                    @error('trip_type')
                        <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <button type="button"
                    wire:click="saveSettings"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    Save Settings
                </button>
            </div>
        </div>
    </div>

    <p class="mb-10 mt-4 text-center text-gray-600" style="font-size:10px">
        &copy; 2024 FlightBook &middot;
        <a href="{{ route('privacy') }}" class="transition-colors hover:text-gray-600">Privacy Policy</a> &middot;
        <a href="{{ route('terms') }}" class="transition-colors hover:text-gray-600">Terms of Service</a>
    </p>
</div>
