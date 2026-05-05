<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Integrations & API</h1>
        </div>

        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'integrations'])
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6 space-y-8">
            <!-- Section 1: Amadeus API -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Amadeus API Settings</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="field-label">Amadeus URL</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['amadeus_url'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Client ID</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['amadeus_client_id'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Client Secret</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ !empty($integrations['amadeus_client_secret']) ? '••••••••••••••••' : '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Grant Type</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['amadeus_grant_type'] ?? '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 2: Mail (SMTP) -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">Mail (SMTP) Settings</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="field-label">Mailer</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_mailer'] ?? '--' }}" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Host</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_host'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Port</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_port'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Encryption</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_encryption'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Username</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_username'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Password</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ !empty($integrations['mail_password']) ? '••••••••' : '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">From Address</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_from_address'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">From Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['mail_from_name'] ?? '--' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Section 3: AWS / Storage -->
            <div class="rounded-xl border border-gray-100 bg-gray-50/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black tracking-widest text-gray-400 uppercase">AWS / Storage Settings</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="field-label">Filesystem Disk</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['filesystem_disk'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Default Region</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['aws_default_region'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Access Key ID</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['aws_access_key_id'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Secret Access Key</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ !empty($integrations['aws_secret_access_key']) ? '••••••••••••••••' : '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Bucket Name</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['aws_bucket'] ?? '--' }}" readonly>
                    </div>

                    <div>
                        <label class="field-label">Path Style Endpoint</label>
                        <input type="text" class="input-field bg-gray-50 text-gray-700" value="{{ $integrations['aws_use_path_style_endpoint'] ?? '--' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
