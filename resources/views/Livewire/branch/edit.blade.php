<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit Branch</h1>
                    <p class="text-xs text-gray-500 mt-1">Update branch information for <span
                            class="font-bold text-gray-700">{{ $branch->company->name ?? 'Company' }}</span></p>
                </div>
                <a href="{{ route('superadmin.branches') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back to List
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
                            <input type="text" wire:model.live.debounce.500ms="name" class="field-input"
                                placeholder="e.g. Dubai Main Office">
                            @error('name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Slug / URL <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="slug" class="field-input bg-gray-50 font-mono text-xs"
                                placeholder="dubai-office">
                            @error('slug') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Branch Code <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="code" class="field-input" placeholder="e.g. DXB-001">
                            @error('code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Parent Company <span class="text-red-500">*</span></label>
                            <select wire:model="company_id" class="field-input">
                                <option value="">Select Company...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Status <span class="text-red-500">*</span></label>
                            <select wire:model="status" class="field-input">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_main"
                                    class="w-4 h-4 rounded border-gray-300 text-[#2ab4c0] focus:ring-[#2ab4c0]">
                                <span class="text-sm font-semibold text-gray-700">Set as Main Branch for this
                                    Company</span>
                            </label>
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
                            <input type="email" wire:model="email" class="field-input" placeholder="branch@company.com">
                            @error('email') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="phone" class="field-input" placeholder="+1234567890">
                            @error('phone') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Secondary Phone</label>
                            <input type="text" wire:model="phone_secondary" class="field-input"
                                placeholder="+1234567890">
                            @error('phone_secondary') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Fax Number</label>
                            <input type="text" wire:model="fax" class="field-input" placeholder="+1234567890">
                            @error('fax') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>

                        <div>
                            <label class="field-label">WhatsApp</label>
                            <input type="text" wire:model="whatsapp" class="field-input" placeholder="+1234567890">
                            @error('whatsapp') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
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
                            <input type="text" wire:model="address_line_1" class="field-input"
                                placeholder="Street address, P.O. box">
                            @error('address_line_1') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Address Line 2</label>
                            <input type="text" wire:model="address_line_2" class="field-input"
                                placeholder="Apartment, suite, unit, building, floor">
                            @error('address_line_2') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">City <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="city" class="field-input" placeholder="e.g. Dubai">
                            @error('city') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">State / Province <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="state" class="field-input" placeholder="e.g. Dubai">
                            @error('state') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="field-input" placeholder="e.g. 12345">
                            @error('postal_code') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Country <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="country" class="field-input"
                                placeholder="e.g. United Arab Emirates">
                            @error('country') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label mb-2">Pin Location on Map (Leaflet)</label>
                            <div id="branch-map" class="w-full h-[300px] rounded-xl border border-gray-200 shadow-inner" wire:ignore></div>
                            <p class="text-[10px] text-gray-500 mt-2 italic">Drag the marker or click on the map to update coordinates automatically.</p>
                        </div>

                        <div>
                            <label class="field-label">Latitude <span class="text-red-500">*</span></label>
                            <input type="text" id="branch-lat" wire:model="latitude" class="field-input font-mono text-xs"
                                placeholder="e.g. 25.2048">
                            @error('latitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Longitude <span class="text-red-500">*</span></label>
                            <input type="text" id="branch-lng" wire:model="longitude" class="field-input font-mono text-xs"
                                placeholder="e.g. 55.2708">
                            @error('longitude') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">
                            {{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="field-label">Internal Notes</label>
                            <textarea wire:model="notes" rows="3" class="field-input pt-2"
                                placeholder="Private internal notes about this branch..."></textarea>
                            @error('notes') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}
                            </p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-6 py-3 text-xs font-black text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-[0.999rem] bg-[#2ab4c0] px-3 py-2 text-[13px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    <span wire:loading.remove>Update Branch</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-container {
        z-index: 10;
        border-radius: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    (function() {
        let map, marker;
        const defaultLat = 25.2048;
        const defaultLng = 55.2708;

        function initLeafletMap() {
            const mapEl = document.getElementById('branch-map');
            if (!mapEl || mapEl._leaflet_id) return;

            const latInput = document.getElementById('branch-lat');
            const lngInput = document.getElementById('branch-lng');

            const initialLat = parseFloat(latInput.value) || defaultLat;
            const initialLng = parseFloat(lngInput.value) || defaultLng;

            map = L.map('branch-map').setView([initialLat, initialLng], 13);
            
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

            marker.on('dragend', function(event) {
                const pos = event.target.getLatLng();
                updateLatLng(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                marker.setLatLng([lat, lng]);
                updateLatLng(lat, lng);
            });

            // Handle manual input changes
            [latInput, lngInput].forEach(el => {
                el.addEventListener('change', function() {
                    const lat = parseFloat(latInput.value) || 0;
                    const lng = parseFloat(lngInput.value) || 0;
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