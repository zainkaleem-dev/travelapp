@php($isSuperAdmin = auth()->check() && auth()->user()->can('Manage Global System'))
<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Attachments</h1>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($uploadedAttachments as $uploadedAttachment)
                        <div class="group relative flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:border-[#2ab4c0] transition-all shadow-sm hover:shadow-md">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#f2feff]">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#2ab4c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            
                            <div class="flex-1 min-w-0" x-data="{ editing: false, newName: @entangle('downloadNames.'.$uploadedAttachment->id) }">
                                <div class="flex items-center gap-2" x-show="!editing">
                                    <p class="text-[11px] font-bold text-gray-900 truncate tracking-tight">{{ $uploadedAttachment->original_name }}</p>
                                    <button type="button" @click="editing = true" class="text-gray-400 hover:text-[#2ab4c0]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center gap-2" x-show="editing" @click.outside="editing = false">
                                    <input type="text" x-model="newName" class="text-[11px] font-bold text-gray-900 border-b border-[#2ab4c0] bg-transparent focus:outline-none py-0 px-0" 
                                        @keydown.enter.prevent="$wire.renameAttachment({{ $uploadedAttachment->id }}); editing = false">
                                    <button type="button" @click="$wire.renameAttachment({{ $uploadedAttachment->id }}); editing = false" class="text-[#2ab4c0]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </div>
                                <p class="text-[11px] text-gray-500 uppercase tracking-tight">
                                    {{ number_format(($uploadedAttachment->size ?? 0) / 1024, 1) }} KB • {{ strtoupper(pathinfo($uploadedAttachment->original_name, PATHINFO_EXTENSION)) }}
                                </p>
                                @error('downloadNames.' . $uploadedAttachment->id)
                                    <p class="mt-1 text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" wire:click="downloadAttachment({{ $uploadedAttachment->id }})" class="p-2 text-gray-400 hover:text-[#2ab4c0] hover:bg-gray-50 rounded-lg transition-all" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/30">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-[11px] text-gray-500 font-medium">No attachments uploaded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

