<?php

namespace App\Livewire\Company;

use App\Models\Company;
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

        // Load existing integrations from settings JSON
        $settings = $this->company->settings ?? [];
        $integrations = $settings['integrations'] ?? [];

        if (!empty($integrations)) {
            $this->amadeus_url = $integrations['amadeus_url'] ?? null;
            $this->amadeus_client_id = $integrations['amadeus_client_id'] ?? null;
            $this->amadeus_client_secret = $integrations['amadeus_client_secret'] ?? null;
            $this->amadeus_grant_type = $integrations['amadeus_grant_type'] ?? null;
            $this->mail_mailer = $integrations['mail_mailer'] ?? null;
            $this->mail_host = $integrations['mail_host'] ?? null;
            $this->mail_port = $integrations['mail_port'] ?? null;
            $this->mail_username = $integrations['mail_username'] ?? null;
            $this->mail_password = $integrations['mail_password'] ?? null;
            $this->mail_encryption = $integrations['mail_encryption'] ?? null;
            $this->mail_from_address = $integrations['mail_from_address'] ?? null;
            $this->mail_from_name = $integrations['mail_from_name'] ?? null;
            $this->filesystem_disk = $integrations['filesystem_disk'] ?? null;
            $this->aws_access_key_id = $integrations['aws_access_key_id'] ?? null;
            $this->aws_secret_access_key = $integrations['aws_secret_access_key'] ?? null;
            $this->aws_default_region = $integrations['aws_default_region'] ?? null;
            $this->aws_bucket = $integrations['aws_bucket'] ?? null;
            $this->aws_use_path_style_endpoint = $integrations['aws_use_path_style_endpoint'] ?? null;
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

        $settings = $this->company->settings ?? [];
        $settings['integrations'] = $validated;
        
        $this->company->settings = $settings;
        $this->company->save();

        session()->flash('status', 'Integration & API settings saved successfully.');
        return $this->redirect(route('companies.index'));
    }

    public function render()
    {
        return view('livewire.company.company-create-integrations');
    }
}
