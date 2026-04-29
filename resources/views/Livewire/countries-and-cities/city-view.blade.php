<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">View City</h1>
                    <a href="{{ route('admin.countries-and-cities') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Name</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $city->name }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Country</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $city->country->name }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Code</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $city->code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
