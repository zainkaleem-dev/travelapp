<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyBranches extends Component
{
    public int $companyId;
    public Company $company;

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
            ->with(['branches' => fn($query) => $query->orderByDesc('is_main')->orderBy('name')])
            ->findOrFail($id);

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to view this organization (Access denied).');
        }
    }

    public function render()
    {
        return view('livewire.company.branches', [
            'branches' => $this->company->branches,
        ]);
    }
}

