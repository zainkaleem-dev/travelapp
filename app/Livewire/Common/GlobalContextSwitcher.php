<?php

namespace App\Livewire\Common;

use App\Models\Company;
use App\Support\TenantContext;
use Livewire\Component;

class GlobalContextSwitcher extends Component
{
    public $activeCompanyId;
    public $manageableCompanies = [];
    public $isSuperAdmin = false;

    public function mount()
    {
        $user = auth()->user();
        if (!$user) return;

        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);

        // Determine if Global Super Admin
        $this->isSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->where('roles.name', 'Super Admin')
            ->whereNull('model_has_roles.company_id')
            ->exists();

        // Get everything they can manage
        $manageableIds = $tenantContext->getManageableHierarchy($user);
        
        $this->manageableCompanies = Company::whereIn('id', $manageableIds)
            ->orderBy('name')
            ->get();

        $this->activeCompanyId = session('active_company_id');
    }

    public function switchContext($companyId)
    {
        $user = auth()->user();
        if (!$user) return;

        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);

        // Normalize: empty string or 'global' means null context
        $targetId = ($companyId === '' || $companyId === 'global') ? null : (int) $companyId;

        // Security check
        $manageableIds = $tenantContext->getManageableHierarchy($user);
        
        if ($targetId !== null && !in_array($targetId, $manageableIds)) {
            session()->flash('error', 'Unauthorized context switch.');
            return;
        }

        if ($targetId === null && !$this->isSuperAdmin) {
            session()->flash('error', 'Only Super Admins can access the Global context.');
            return;
        }

        // Apply to session
        if ($targetId === null) {
            session()->forget('active_company_id');
        } else {
            session(['active_company_id' => $targetId]);
        }

        // Redirect to refresh all components with new context
        return redirect(request()->header('Referer') ?: route('dashboard'));
    }

    public function render()
    {
        // Only show if the user has more than one entity to manage OR is a Super Admin
        if (!$this->isSuperAdmin && count($this->manageableCompanies) <= 1) {
            return <<<'blade'
                <div></div>
            blade;
        }

        return view('livewire.common.global-context-switcher');
    }
}
