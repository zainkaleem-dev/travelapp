{{-- Shared fields: Personal Info + Travel Documents + Preferences (wire:model on parent Livewire component) --}}
<section class="bg-white rounded-xl border border-gray-200/70 p-4 shadow-sm">
    <h2 class="text-sm font-bold text-gray-900 mb-4">Personal Info</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">First name</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="First name" wire:model.defer="first_name" />
        </div>
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Last name</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Last name" wire:model.defer="last_name" />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
            <input type="email"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Email" wire:model.defer="email" />
        </div>
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone</label>
            <input type="tel"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Phone" wire:model.defer="phone" />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">DOB</label>
            <input type="date"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="dob" />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Gender</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="gender">
                <option value="">Select</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
                <option>Prefer not to say</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nationality</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Nationality" wire:model.defer="nationality" />
        </div>
    </div>
</section>

<section class="bg-white rounded-xl border border-gray-200/70 p-4 shadow-sm">
    <h2 class="text-sm font-bold text-gray-900 mb-4">Travel Documents</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Passport number</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Passport number" wire:model.defer="passport_number" />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Expiry date</label>
            <input type="date"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="expiry_date" />
        </div>

        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Issuing country</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Issuing country" wire:model.defer="issuing_country" />
        </div>
    </div>
</section>

<section class="bg-white rounded-xl border border-gray-200/70 p-4 shadow-sm">
    <h2 class="text-sm font-bold text-gray-900 mb-4">Preferences</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Purpose of travel</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="purpose_of_travel">
                <option value="">Select</option>
                <option>Business</option>
                <option>Leisure</option>
                <option>Education</option>
                <option>Visiting family</option>
                <option>Other</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Seat preference</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="seat_preference">
                <option value="">Select</option>
                <option>Aisle</option>
                <option>Window</option>
                <option>Middle</option>
                <option>Any</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Meal preference</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="meal_preference">
                <option value="">Select</option>
                <option>Regular</option>
                <option>Vegetarian</option>
                <option>Vegan</option>
                <option>Halal</option>
                <option>Kosher</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Preferred cabin</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                wire:model.defer="preferred_cabin">
                <option value="">Select</option>
                <option>Economy</option>
                <option>Premium Economy</option>
                <option>Business</option>
                <option>First</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Preferred airline</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2ab4c0]/20 focus:border-[#2ab4c0]"
                placeholder="Airline name" wire:model.defer="preferred_airline" />
        </div>
    </div>
</section>
