@php($isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System'))
<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $isSuperAdmin ? 'Organization Attachments' : 'Partner Attachments' }}</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'attachments'])
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase mb-4">Uploaded Files</h2>

                <div class="rounded-xl border border-gray-200 bg-white">
                    @forelse($uploadedAttachments as $uploadedAttachment)
                        <div class="px-4 py-3 border-b last:border-b-0 border-gray-100 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $uploadedAttachment->original_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ number_format(($uploadedAttachment->size ?? 0) / 1024, 1) }} KB
                                </p>
                            </div>
                            <div class="w-full max-w-md">
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model.defer="downloadNames.{{ $uploadedAttachment->id }}"
                                        class="input-field !pl-3 text-xs"
                                        placeholder="Enter file name to download">
                                    <button type="button" wire:click="renameAttachment({{ $uploadedAttachment->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                        Rename
                                    </button>
                                    <button type="button" wire:click="downloadAttachment({{ $uploadedAttachment->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                        Download
                                    </button>
                                </div>
                                @error('downloadNames.' . $uploadedAttachment->id)
                                    <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-3 text-sm text-gray-500">No attachments uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

