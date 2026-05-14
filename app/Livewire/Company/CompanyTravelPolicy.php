<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\TravelPolicy;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class CompanyTravelPolicy extends Component
{
    use WithPagination;

    public int $companyId;
    public Company $company;
    public $search = '';
    public $activeTab = 'flight';

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'flight'],
    ];

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
    }

    public function render()
    {
        return view('livewire.company.travel-policy');
    }
}
