<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">View Airport</h1>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-1 tracking-wider">Airport Information</p>
                    </div>
                    <a href="{{ route('admin.airports') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 hover:bg-gray-50 uppercase tracking-wider transition-colors shadow-sm">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $airport->name }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">City</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $airport->city->name }}, {{ $airport->city->country->code }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">IATA Code</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 font-mono uppercase">{{ $airport->code }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">ICAO Code</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 font-mono uppercase">{{ $airport->icao_code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
