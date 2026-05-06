<div class="w-full">
    <div class="px-1 py-1 w-full">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm flex flex-col mb-4">
            <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Integrations &amp; API</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm flex flex-col">
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
                    <div class="rounded-lg border border-gray-200/80 bg-white overflow-hidden">
                        <div class="px-4 py-2 bg-[#2ab4c0]">
                            <p class="text-[11px] font-bold text-white uppercase tracking-wide">{{ $section['title'] }}</p>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($section['keys'] as $key)
                                @php($isSecret = str_contains($key, 'SECRET') || str_contains($key, 'PASSWORD') || str_contains($key, 'KEY'))
                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-gray-500">{{ $key }}</label>
                                    <input
                                        type="{{ $isSecret ? 'password' : 'text' }}"
                                        wire:model.defer="values.{{ $key }}"
                                        class="input-field"
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
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
