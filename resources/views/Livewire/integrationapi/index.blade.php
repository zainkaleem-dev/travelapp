<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm flex flex-col">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Integrations &amp; API</h1>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6 flex-1">
                @if ($statusMessage)
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ $statusMessage }}
                    </div>
                @endif

                @if ($errorMessage)
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ $errorMessage }}
                    </div>
                @endif

                @foreach($sections as $section)
                    <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden">
                        <div class="px-4 py-3 bg-[#2ab4c0]">
                            <p class="text-xs font-bold text-white uppercase tracking-wide">{{ $section['title'] }}</p>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($section['keys'] as $key)
                                @php($isSecret = str_contains($key, 'SECRET') || str_contains($key, 'PASSWORD') || str_contains($key, 'KEY'))
                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">{{ $key }}</label>
                                    <input
                                        type="{{ $isSecret ? 'password' : 'text' }}"
                                        wire:model.defer="values.{{ $key }}"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 shadow-sm focus:border-[#2ab4c0] focus:outline-none focus:ring-2 focus:ring-[#2ab4c0]/25"
                                        placeholder="(empty)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 flex justify-end">
                <button type="button"
                    wire:click="save"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm shadow-lg">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
