<?php

namespace App\Support;

use App\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class TenantContext
{
    private ?int $companyId = null;
    private ?bool $isSuperAdmin = null;

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId && $companyId > 0 ? $companyId : null;
    }

    public function companyId(): ?int
    {
        return $this->companyId;
    }

    public function inAdminArea(Request $request): bool
    {
        return $request->is('admin*');
    }

    public function resolveCompanyId(Request $request, ?Authenticatable $user): ?int
    {
        if (!$user) {
            return null;
        }

        // 1. Determine if the user is a Global Super Admin
        // Use a direct DB check to avoid recursion with model methods
        $this->isSuperAdmin = \Illuminate\Support\Facades\Cache::remember(
            "user_{$user->id}_is_global_super_admin_context",
            now()->addMinutes(10),
            fn() => \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'Super Admin')
                ->whereNull('model_has_roles.company_id')
                ->exists()
        );

        // 2. Check for an active context switch in the session
        $sessionContextId = session('active_company_id');

        if ($sessionContextId) {
            // Validate that the user HAS ACCESS to this company
            if ($this->isSuperAdmin) {
                return (int) $sessionContextId;
            }

            // For non-super admins, check their manageable hierarchy
            $manageableIds = $this->getManageableHierarchy($user);
            if (in_array((int) $sessionContextId, $manageableIds)) {
                return (int) $sessionContextId;
            }

            // If session ID is invalid/malicious, clear it
            session()->forget('active_company_id');
        }

        // 3. Fallback logic: Super Admins default to Global (null)
        if ($this->isSuperAdmin) {
            return null;
        }

        // 4. Regular users default to their own company
        return (int) ($user->company_id ?? 0) ?: null;
    }

    /**
     * Get all company IDs the user is authorized to manage.
     * 
     * @return array<int>
     */
    public function getManageableHierarchy(?Authenticatable $user): array
    {
        if (!$user) return [];

        // Ensure isSuperAdmin is determined
        if ($this->isSuperAdmin === null) {
            $this->isSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'Super Admin')
                ->whereNull('model_has_roles.company_id')
                ->exists();
        }

        if ($this->isSuperAdmin) {
            return \App\Models\Company::pluck('id')->toArray();
        }

        $companyId = (int) ($user->company_id ?? 0);
        if (!$companyId) return [];

        $company = \App\Models\Company::find($companyId);
        if (!$company) return [];

        // Return the company itself and all its descendants recursively
        return $company->getAllDescendantIds();
    }

    public function currentCompany(Request $request): ?Company
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return null;
        }

        return \App\Models\Company::find($companyId);
    }
}
