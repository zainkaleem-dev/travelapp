<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyShow extends Component
{
    public int $companyId;
    public Company $company;

    // Core Identity
    public string $company_name = '';
    public string $slug = '';
    public ?string $company_type = null;
    public ?string $legal_name = null;
    public ?string $registration_number = null;
    public ?string $existing_logo_path = null;
    public ?int $founded_year = null;
    public ?string $description = null;
    public ?int $parent_id = null;

    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;

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

        $this->company_name = $this->company->name;
        $this->slug = $this->company->slug;
        $this->company_type = $this->company->company_type;
        $this->legal_name = $this->company->legal_name;
        $this->registration_number = $this->company->registration_number;
        $this->existing_logo_path = $this->company->settings['logo_path'] ?? null;
        $this->founded_year = $this->company->founded_year;
        $this->description = $this->company->description;
        $this->status = $this->company->status;
        $this->notes = $this->company->notes;
        $this->parent_id = $this->company->parent_id;
    }

    public function render()
    {
        return view('livewire.company.show', [
            'parentCompanyName' => optional($this->company->parent)->name,
        ]);
    }
}

