<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add Branch</h1>
                    <p class="text-xs text-gray-500 mt-1">Create a new branch for a company</p>
                </div>
                <a href="{{ route('branches.index') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="px-6 py-4">
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Identity -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Branch Identity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.live.debounce.500ms="name" class="input-field"
                                placeholder="Main Office">
                            @error('name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Slug / URL <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="slug" class="input-field font-mono"
                                placeholder="city-office">
                            @error('slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Branch Code <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="code" class="input-field" placeholder="DXB-001">
                            @error('code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Parent Company <span class="text-red-500">*</span></label>
                            <div class="relative"
                                x-data="{ open: false, selected: @js((string) ($company_id ?? '')), labels: @js($companies->pluck('name', 'id')) }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span
                                        x-text="selected === '' ? 'Select Company...' : (labels[selected] ?? 'Select Company...')"></span>
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
                                        @click="selected = ''; open = false; $wire.set('company_id', '')">Select
                                        Company...</button>
                                    @foreach($companies as $company)
                                        <button type="button" class="admin-menu-item"
                                            :class="{ 'is-active': selected === '{{ $company->id }}' }"
                                            @click="selected = '{{ $company->id }}'; open = false; $wire.set('company_id', '{{ $company->id }}')">{{ $company->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Status <span class="text-red-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: @js($status ?? 'active') }"
                                @keydown.escape.window="open = false" @click.outside="open = false">
                                <button type="button" class="input-field flex items-center justify-between text-left" @click="open = !open">
                                    <span x-text="selected.charAt(0).toUpperCase() + selected.slice(1)"></span>
                                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'active' }"
                                        @click="selected = 'active'; open = false; $wire.set('status', 'active')">Active</button>
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': selected === 'inactive' }"
                                        @click="selected = 'inactive'; open = false; $wire.set('status', 'inactive')">Inactive</button>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_main"
                                    class="w-4 h-4 rounded border-gray-300 text-[#2ab4c0] focus:ring-[#2ab4c0]">
                                <span class="text-sm font-semibold text-gray-700">Set as Main Branch for this
                                    Company</span>
                            </label>
                            @error('is_main') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Information -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Contact Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="input-field" placeholder="branch@company.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="phone" class="input-field" placeholder="+1234567890">
                            @error('phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Secondary Phone</label>
                            <input type="text" wire:model="phone_secondary" class="input-field"
                                placeholder="+1234567890">
                            @error('phone_secondary') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Fax Number</label>
                            <input type="text" wire:model="fax" class="input-field" placeholder="+1234567890">
                            @error('fax') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">WhatsApp</label>
                            <input type="text" wire:model="whatsapp" class="input-field" placeholder="+1234567890">
                            @error('whatsapp') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Physical Address -->
                <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Location & Address</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Address Line 1 <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="address_line_1" class="input-field"
                                placeholder="Street address, P.O. box">
                            @error('address_line_1') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Address Line 2</label>
                            <input type="text" wire:model="address_line_2" class="input-field"
                                placeholder="Apartment, suite, unit, building, floor">
                            @error('address_line_2') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">City <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="city" class="input-field" placeholder="City">
                            @error('city') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">State / Province <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="state" class="input-field" placeholder="City">
                            @error('state') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="input-field" placeholder="12345">
                            @error('postal_code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Country <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="country" class="input-field"
                                placeholder="Country">
                            @error('country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label mb-2">Pin Location on Map (Leaflet)</label>
                            <div id="branch-map" class="w-full h-[300px] rounded-xl border border-gray-200 shadow-inner"
                                wire:ignore></div>
                            <p class="text-[10px] text-red-500 mt-2 italic">Drag the marker or click on the map to
                                update coordinates automatically.</p>
                        </div>

                        <div>
                            <label class="field-label">Latitude <span class="text-red-500">*</span></label>
                            <input type="text" id="branch-lat" wire:model="latitude" class="input-field font-mono"
                                placeholder="25.2048">
                            @error('latitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Longitude <span class="text-red-500">*</span></label>
                            <input type="text" id="branch-lng" wire:model="longitude" class="input-field font-mono"
                                placeholder="55.2708">
                            @error('longitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Internal Notes</label>
                            <textarea wire:model="notes" rows="3" class="input-field pt-2"
                                placeholder="Private internal notes about this branch..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    <span wire:loading.remove>Save Branch</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .leaflet-container {
            z-index: 10;
            border-radius: 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function () {
            let map, marker;
            const defaultLat = 25.2048;
            const defaultLng = 55.2708;
            const initialWireLat = @js($latitude);
            const initialWireLng = @js($longitude);

            function parseCoordinate(value, fallback, min, max) {
                const normalized = String(value ?? '').replace(',', '.').trim();
                const parsed = Number.parseFloat(normalized);
                if (!Number.isFinite(parsed)) return fallback;
                if (parsed < min || parsed > max) return fallback;
                return parsed;
            }

            function getInitialCoords(latInput, lngInput) {
                const latSource = latInput?.value !== '' ? latInput?.value : initialWireLat;
                const lngSource = lngInput?.value !== '' ? lngInput?.value : initialWireLng;

                return {
                    lat: parseCoordinate(latSource, defaultLat, -90, 90),
                    lng: parseCoordinate(lngSource, defaultLng, -180, 180),
                };
            }

            function initLeafletMap() {
                const mapEl = document.getElementById('branch-map');
                if (!mapEl) return;

                // Recreate map on each navigation/render so marker always matches current lat/lng.
                if (window.__branchLeafletMap && typeof window.__branchLeafletMap.remove === 'function') {
                    window.__branchLeafletMap.remove();
                    window.__branchLeafletMap = null;
                    window.__branchLeafletMarker = null;
                }

                const latInput = document.getElementById('branch-lat');
                const lngInput = document.getElementById('branch-lng');
                if (!latInput || !lngInput) return;

                const { lat: initialLat, lng: initialLng } = getInitialCoords(latInput, lngInput);

                map = L.map('branch-map').setView([initialLat, initialLng], 13);
                window.__branchLeafletMap = map;

                // Fix for gray box / tile loading issues
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                marker = L.marker([initialLat, initialLng], {
                    draggable: true
                }).addTo(map);
                window.__branchLeafletMarker = marker;

                marker.on('dragend', function (event) {
                    const pos = event.target.getLatLng();
                    updateLatLng(pos.lat, pos.lng);
                });

                map.on('click', function (e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    marker.setLatLng([lat, lng]);
                    updateLatLng(lat, lng);
                });

                // Handle manual input changes
                [latInput, lngInput].forEach(el => {
                    el.addEventListener('change', function () {
                        const lat = parseCoordinate(latInput.value, defaultLat, -90, 90);
                        const lng = parseCoordinate(lngInput.value, defaultLng, -180, 180);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.panTo([lat, lng]);
                        }
                    });
                });

                function updateLatLng(lat, lng) {
                    const latFixed = lat.toFixed(6);
                    const lngFixed = lng.toFixed(6);
                    latInput.value = latFixed;
                    lngInput.value = lngFixed;
                    @this.set('latitude', latFixed);
                    @this.set('longitude', lngFixed);
                }
            }

            // Run on load and on Livewire navigation
            initLeafletMap();
            document.addEventListener('livewire:navigated', initLeafletMap);
        })();
    </script>
@endpush