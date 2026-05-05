<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyIntegration;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyCreateIntegrations extends Component
{
    public int $companyId;
    public Company $company;

    // Amadeus API
    public ?string $amadeus_url = null;
    public ?string $amadeus_client_id = null;
    public ?string $amadeus_client_secret = null;
    public ?string $amadeus_grant_type = null;

    // Mail (SMTP)
    public ?string $mail_mailer = null;
    public ?string $mail_host = null;
    public ?string $mail_port = null;
    public ?string $mail_username = null;
    public ?string $mail_password = null;
    public ?string $mail_encryption = null;
    public ?string $mail_from_address = null;
    public ?string $mail_from_name = null;

    // AWS / Storage
    public ?string $filesystem_disk = null;
    public ?string $aws_access_key_id = null;
    public ?string $aws_secret_access_key = null;
    public ?string $aws_default_region = null;
    public ?string $aws_bucket = null;
    public ?string $aws_use_path_style_endpoint = null;

    public function mount(int $id): void
    {
        $this->companyId = $id;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to view this organization (Access denied).');
        }

        // Load existing integrations if any
        $integration = $this->company->integration;
        if ($integration) {
            $this->amadeus_url = $integration->amadeus_url;
            $this->amadeus_client_id = $integration->amadeus_client_id;
            $this->amadeus_client_secret = $integration->amadeus_client_secret;
            $this->amadeus_grant_type = $integration->amadeus_grant_type;
            $this->mail_mailer = $integration->mail_mailer;
            $this->mail_host = $integration->mail_host;
            $this->mail_port = $integration->mail_port;
            $this->mail_username = $integration->mail_username;
            $this->mail_password = $integration->mail_password;
            $this->mail_encryption = $integration->mail_encryption;
            $this->mail_from_address = $integration->mail_from_address;
            $this->mail_from_name = $integration->mail_from_name;
            $this->filesystem_disk = $integration->filesystem_disk;
            $this->aws_access_key_id = $integration->aws_access_key_id;
            $this->aws_secret_access_key = $integration->aws_secret_access_key;
            $this->aws_default_region = $integration->aws_default_region;
            $this->aws_bucket = $integration->aws_bucket;
            $this->aws_use_path_style_endpoint = $integration->aws_use_path_style_endpoint;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'amadeus_url' => 'nullable|string',
            'amadeus_client_id' => 'nullable|string',
            'amadeus_client_secret' => 'nullable|string',
            'amadeus_grant_type' => 'nullable|string',
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|string',
            'mail_from_name' => 'nullable|string',
            'filesystem_disk' => 'nullable|string',
            'aws_access_key_id' => 'nullable|string',
            'aws_secret_access_key' => 'nullable|string',
            'aws_default_region' => 'nullable|string',
            'aws_bucket' => 'nullable|string',
            'aws_use_path_style_endpoint' => 'nullable|string',
        ]);

        $integration = CompanyIntegration::firstOrNew(['company_id' => $this->company->id]);
        $integration->fill($validated);
        $integration->save();

        session()->flash('status', 'Integration & API settings saved successfully.');
        return $this->redirect(route('companies.index'));
    }

    public function render()
    {
        return view('livewire.company.company-create-integrations');
    }
}
