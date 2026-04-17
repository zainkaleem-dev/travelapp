<div class="w-full">
    <div class="w-full py-6 px-3 sm:px-4 lg:px-6">
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#2ab4c0] to-[#239ea9] rounded-xl px-5 py-4 mb-5">
                <h1 class="text-lg font-bold text-white">TMC Settings</h1>
                <p class="text-xs text-white/90 mt-1">Manage your TMC configurations and agency preferences.</p>
            </div>

            <div class="rounded-xl border border-gray-200/80 bg-gray-50/50 p-12 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#2ab4c0]/10 text-[#2ab4c0] mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-gray-900">Coming Soon</h2>
                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">TMC agency management features are currently being finalized and will be available shortly.</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-gray-600 mt-4 mb-10" style="font-size:10px">
        &copy; 2024 FlightBook &middot;
        <a href="{{ route('privacy') }}" class="hover:text-gray-600 transition-colors">Privacy Policy</a> &middot;
        <a href="{{ route('terms') }}" class="hover:text-gray-600 transition-colors">Terms of Service</a>
    </p>
</div>
