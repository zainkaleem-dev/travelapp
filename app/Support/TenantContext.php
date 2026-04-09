<?php

namespace App\Support;

use App\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class TenantContext
{
    private ?int $companyId = null;

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId && $companyId > 0 ? $companyId : null;
    }

    public function companyId(): ?int
    {
        return $this->companyId;
    }

    public function inSuperAdminArea(Request $request): bool
    {
        return $request->is('super-admin*');
    }

    public function resolveCompanyId(Request $request, ?Authenticatable $user): ?int
    {
        if (!$user) {
            return null;
        }

        $isSuperAdmin = (bool) ($user->is_super_admin ?? false);

        $isLivewireRequest = $request->headers->has('X-Livewire')
            || $request->is('livewire/*')
            || $request->route()?->getName() === 'livewire.update';

        if ($this->inSuperAdminArea($request) || ($isSuperAdmin && $isLivewireRequest)) {
            if (!$isSuperAdmin) {
                return null;
            }

            return (int) $request->session()->get('super_admin_company_id', 0) ?: null;
        }

        if ($isSuperAdmin) {
            return null;
        }

        return (int) ($user->company_id ?? 0) ?: null;
    }

    public function currentCompany(Request $request): ?Company
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return null;
        }

        try {
            return Company::query()->find($companyId);
        } catch (\Throwable) {
            return null;
        }
    }
}
