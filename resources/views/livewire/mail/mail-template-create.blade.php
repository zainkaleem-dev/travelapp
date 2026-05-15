<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.mail.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Templates
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Create Mail Template</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form wire:submit="save" class="p-6 space-y-6">
                <!-- Name (Slug) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Template Identifier (Name)</label>
                    <input type="text" wire:model="name" placeholder="e.g. welcome_email" class="mt-1 block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-400 mt-1">Use lowercase and underscores only.</p>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Subject</label>
                    <input type="text" wire:model="subject" class="mt-1 block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                    @error('subject') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Type & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Type</label>
                        <select wire:model="type" class="mt-1 block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="System">System</option>
                            <option value="Booking">Booking</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                        @error('type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model="status" class="mt-1 block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Content (HTML)</label>
                    <textarea wire:model="content" rows="10" class="mt-1 block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 font-mono text-sm @error('content') border-red-500 @enderror" placeholder="<h1>Hello!</h1>..."></textarea>
                    @error('content') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" wire:click="save" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
