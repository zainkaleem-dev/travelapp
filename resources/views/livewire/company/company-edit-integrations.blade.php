<div class="w-full px-1 py-1 flex flex-col gap-3">
    <!-- Header & Navigation Container -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ (auth()->user()?->can('Manage Global System') ?? false) ? 'Edit Organization' : 'Edit Partner' }}</h1>
                </div>
                <a href="{{ route('companies.index') }}"
                    class="hidden sm:inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Back
                </a>
            </div>
        </div>

        @include('partials.navigation-company-edit', ['companyId' => $companyId, 'activeTab' => 'integrations'])
    </div>

    <!-- Main Content Container -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        @if (session('status'))
            <div class="px-6 pt-6">
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[11px] text-green-800 uppercase font-semibold">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="px-6 pt-6">
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-[11px] text-red-800">
                    <p class="font-bold uppercase text-[11px] mb-2">Please fix the following errors:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="p-6">
            <div class="space-y-8">
                <!-- Section 1: Amadeus API -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Amadeus API Settings</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="field-label">Amadeus URL</label>
                            <input type="text" wire:model="amadeus_url" class="input-field" placeholder="https://test.api.amadeus.com">
                            @error('amadeus_url') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Client ID</label>
                            <input type="text" wire:model="amadeus_client_id" class="input-field" placeholder="API Key">
                            @error('amadeus_client_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Client Secret</label>
                            <input type="password" wire:model="amadeus_client_secret" class="input-field" placeholder="••••••••">
                            @error('amadeus_client_secret') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Grant Type</label>
                            <input type="text" wire:model="amadeus_grant_type" class="input-field" placeholder="client_credentials">
                            @error('amadeus_grant_type') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Mail (SMTP) -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Mail (SMTP) Settings</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="field-label">Mailer</label>
                            <input type="text" wire:model="mail_mailer" class="input-field" placeholder="smtp">
                            @error('mail_mailer') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Host</label>
                            <input type="text" wire:model="mail_host" class="input-field" placeholder="smtp.mailtrap.io">
                            @error('mail_host') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Port</label>
                            <input type="text" wire:model="mail_port" class="input-field" placeholder="2525">
                            @error('mail_port') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Encryption</label>
                            <input type="text" wire:model="mail_encryption" class="input-field" placeholder="tls">
                            @error('mail_encryption') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Username</label>
                            <input type="text" wire:model="mail_username" class="input-field" placeholder="Username">
                            @error('mail_username') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Password</label>
                            <input type="password" wire:model="mail_password" class="input-field" placeholder="••••••••">
                            @error('mail_password') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">From Address</label>
                            <input type="email" wire:model="mail_from_address" class="input-field" placeholder="hello@example.com">
                            @error('mail_from_address') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">From Name</label>
                            <input type="text" wire:model="mail_from_name" class="input-field" placeholder="Example App">
                            @error('mail_from_name') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: AWS / Storage -->
                <div class="rounded-lg border border-gray-100 bg-gray-50/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">AWS / Storage Settings</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Filesystem Disk</label>
                            <input type="text" wire:model="filesystem_disk" class="input-field" placeholder="s3">
                            @error('filesystem_disk') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Default Region</label>
                            <input type="text" wire:model="aws_default_region" class="input-field" placeholder="us-east-1">
                            @error('aws_default_region') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Access Key ID</label>
                            <input type="text" wire:model="aws_access_key_id" class="input-field" placeholder="AKIA...">
                            @error('aws_access_key_id') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Secret Access Key</label>
                            <input type="password" wire:model="aws_secret_access_key" class="input-field" placeholder="••••••••">
                            @error('aws_secret_access_key') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Bucket Name</label>
                            <input type="text" wire:model="aws_bucket" class="input-field" placeholder="my-bucket">
                            @error('aws_bucket') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="field-label">Use Path Style Endpoint</label>
                            <select wire:model="aws_use_path_style_endpoint" class="input-field">
                                <option value="">Select...</option>
                                <option value="false">No (Default)</option>
                                <option value="true">Yes</option>
                            </select>
                            @error('aws_use_path_style_endpoint') <p class="mt-1 text-[11px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-semibold text-white hover:bg-[#229aa4] transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
