<?php

namespace App\Livewire\Admin\Companies\Branches;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class SelectedBranchIndex extends Component
{
    public function render()
    {
        $requestedCompanyId = (int) request()->query('company', 0);
        if ($requestedCompanyId > 0) {
            session()->put('super_admin_company_id', $requestedCompanyId);
        }

        $companyId = (int) session('super_admin_company_id', 0);

        if ($companyId <= 0) {
            return view('livewire.admin.companies.branches.no-company');
        }

        $company = Company::query()->find($companyId);
        if (!$company) {
            session()->forget('super_admin_company_id');
            return view('livewire.admin.companies.branches.no-company');
        }

        return view('livewire.admin.companies.branches.selected-redirect', [
            'company' => $company,
        ]);
    }
}
