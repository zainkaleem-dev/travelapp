<div class="w-full px-1 py-1">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <h1 class="text-[21px] font-black text-gray-900 tracking-tight">{{ $company->name }} Integrations & API</h1>
        </div>

        <!-- Navigation Tabs -->
        @include('partials.navigation-company', ['companyId' => $companyId, 'activeTab' => 'integrations'])

        <!-- Content Area -->
        <div class="p-6 space-y-8">
            <!-- Section 1: Amadeus API -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Amadeus API Settings</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Amadeus URL</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['amadeus_url'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Client ID</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $integrations['amadeus_client_id'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Client Secret</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ !empty($integrations['amadeus_client_secret']) ? '••••••••••••••••' : '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Grant Type</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['amadeus_grant_type'] ?? '--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Mail (SMTP) -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Mail (SMTP) Settings</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Mailer</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_mailer'] ?? '--' }}</p>
                    </div>
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Host</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $integrations['mail_host'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Port</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_port'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Encryption</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_encryption'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Username</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_username'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Password</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ !empty($integrations['mail_password']) ? '••••••••' : '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">From Address</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_from_address'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">From Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['mail_from_name'] ?? '--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: AWS / Storage -->
            <div class="space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">AWS / Storage Settings</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Filesystem Disk</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['filesystem_disk'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Default Region</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['aws_default_region'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Access Key ID</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ $integrations['aws_access_key_id'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Secret Access Key</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase font-mono">{{ !empty($integrations['aws_secret_access_key']) ? '••••••••••••••••' : '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bucket Name</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['aws_bucket'] ?? '--' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Path Style Endpoint</p>
                        <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $integrations['aws_use_path_style_endpoint'] ?? '--' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
