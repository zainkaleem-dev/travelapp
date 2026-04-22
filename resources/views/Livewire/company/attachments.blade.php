<div class="max-w-6xl px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Organization Attachments</h1>
                    <p class="text-sm text-gray-500 mt-1">Viewing uploaded files for {{ $company->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('companies.show', $companyId) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back to Profile
                    </a>
                </div>
            </div>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'attachments'])

        <div class="p-6">
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
                                    <button type="button" wire:click="renameAndDownloadAttachment({{ $uploadedAttachment->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                        Rename & Download
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

