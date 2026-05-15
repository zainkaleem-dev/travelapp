<div class="w-full">
    <div class="w-full py-6 px-3 sm:px-4 lg:px-6">
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <div class="mb-5">
                <h1 class="text-lg font-bold text-gray-900">Create Family Traveler</h1>
                <p class="text-xs text-gray-500 mt-1">Same fields as your personal profile. Data is stored on this account only.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="saveFamilyMember" class="space-y-6">
                @include('livewire.profile.partials.personal-info-fields')

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="{{ route('profile') }}"
                        class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-semibold">
                        Back to Profile
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[#2ab4c0] text-white hover:bg-[#239ea9] transition-colors text-sm font-semibold">
                        Save Family Traveler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
