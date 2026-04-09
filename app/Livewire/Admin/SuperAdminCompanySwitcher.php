<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use Livewire\Component;

class SuperAdminCompanySwitcher extends Component
{
    public int $companyId = 0;

    public function mount(): void
    {
        $this->companyId = (int) session('super_admin_company_id', 0);
    }

    public function switchCompany($value): void
    {
        $companyId = (int) $value;
        $this->companyId = $companyId;

        $currentCompanyId = (int) session('super_admin_company_id', 0);
        if ($companyId === $currentCompanyId) {
            return;
        }

        if ($companyId > 0) {
            session()->put('super_admin_company_id', $companyId);
        } else {
            session()->forget('super_admin_company_id');
        }

        $this->redirect(route('superadmin.branches'));
    }

    public function render()
    {
        return view('livewire.admin.super-admin-company-switcher', [
            'companies' => Company::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
