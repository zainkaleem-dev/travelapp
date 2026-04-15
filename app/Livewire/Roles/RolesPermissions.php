<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use App\Models\Role;
use App\Support\TenantContext;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class RolesPermissions extends Component
{
    public $search = '';
    public $searchPermissions = '';
    public $selectedCompanyId = null;
    public $selectedRoleId = null;
    public $newRoleName = '';
    public $newPermissionName = '';
    public $activeCompany = null;
    public $isSuperAdmin = false;

    public function mount($company = null): void
    {
        $this->isSuperAdmin = auth()->user()->hasRole('super_admin');

        if (!$this->isSuperAdmin) {
            $tenantContext = app(\App\Support\TenantContext::class);
            $this->selectCompany($tenantContext->companyId());
        } elseif ($company) {
            if ($company instanceof \App\Models\Company) {
                $this->selectCompany($company->id);
            } elseif (is_numeric($company)) {
                $this->selectCompany((int) $company);
            }
        }
    }

    public function selectCompany($id): void
    {
        if (!$id) return;
        
        $this->selectedCompanyId = $id;
        $this->activeCompany = \App\Models\Company::find($id);
        $this->selectedRoleId = null; // Reset role when changing company
        $this->refreshData();
        
        // Auto-select first role if available
        $roles = $this->getRolesProperty();
        if ($roles->count() > 0) {
            $this->selectRole($roles->first()->id);
        }
    }

    public function selectRole($id): void
    {
        $this->selectedRoleId = $id;
    }

    public function getSidebarCompaniesProperty()
    {
        return \App\Models\Company::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function getCompanyStatsProperty()
    {
        $stats = [];
        $companies = $this->sidebarCompanies;
        foreach ($companies as $company) {
            $roleCount = \App\Models\Role::where('company_id', $company->id)->count();
            $stats[$company->id] = [
                'roles' => $roleCount,
            ];
        }
        return $stats;
    }

    public function getRolesProperty()
    {
        if (!$this->selectedCompanyId) return collect();

        return \App\Models\Role::where('company_id', $this->selectedCompanyId)
            ->orderBy('name')
            ->get();
    }

    public function getAllPermissionsProperty()
    {
        return Permission::query()
            ->when($this->searchPermissions, function ($query) {
                $query->where('name', 'like', '%' . $this->searchPermissions . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function refreshData()
    {
        // This is now handled by computed properties (get...Property)
    }

    public function createRole()
    {
        if (!$this->selectedCompanyId) return;

        $this->validate([
            'newRoleName' => 'required|string',
        ]);

        $exists = \App\Models\Role::where('name', $this->newRoleName)
            ->where('company_id', $this->selectedCompanyId)
            ->exists();

        if ($exists) {
            $this->addError('newRoleName', 'This role already exists.');
            return;
        }

        $role = \App\Models\Role::create([
            'name' => $this->newRoleName,
            'company_id' => $this->selectedCompanyId,
            'guard_name' => 'web'
        ]);

        $this->newRoleName = '';
        $this->selectRole($role->id);
        session()->flash('status', 'Role created successfully.');
    }

    public function togglePermission($permissionName)
    {
        if (!$this->selectedRoleId) return;

        $role = \App\Models\Role::findById($this->selectedRoleId);
        
        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function deleteRole($id)
    {
        $role = \App\Models\Role::findById($id);
        if ($role->name === 'super_admin') {
            session()->flash('error', 'Cannot delete super_admin.');
            return;
        }

        $role->delete();
        if ($this->selectedRoleId == $id) {
            $this->selectedRoleId = null;
        }
        session()->flash('status', 'Role deleted successfully.');
    }

    public function render()
    {
        $selectedRole = $this->selectedRoleId ? \App\Models\Role::findById($this->selectedRoleId) : null;
        $currentRolePermissions = $selectedRole ? $selectedRole->permissions->pluck('name')->toArray() : [];

        return view('livewire.roles.roles-permissions', [
            'sidebarCompanies' => $this->sidebarCompanies,
            'companyStats' => $this->companyStats,
            'roles' => $this->roles,
            'allPermissions' => $this->allPermissions,
            'currentRolePermissions' => $currentRolePermissions,
            'selectedRole' => $selectedRole
        ]);
    }
}
