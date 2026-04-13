<?php
 
namespace App\Livewire\Company;
 
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
#[Layout('layouts.flight')]
class CompanyListing extends Component
{
    public ?int $companyId = null;
    public ?string $companyName = null;
    public ?string $companyType = null;
    public bool $isActive = true;
 
    public function mount(TenantContext $tenantContext): void
    {
        $company = $tenantContext->currentCompany(request());
 
        $this->companyId = $company?->id;
        $this->companyName = $company?->name;
        $this->companyType = $company?->type;
        $this->isActive = (bool) ($company?->is_active ?? true);
    }
 
    public function render()
    {
        return view('livewire.company.listing');
    }
}
