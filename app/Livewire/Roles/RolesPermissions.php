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
    public $roles;
    public $permissions;
    public $selectedRoleId;
    public $newRoleName;
    public $newPermissionName;
    public $searchPermissions = '';

    public function mount()
    {
        $this->refreshData();
        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        }
    }

    public function refreshData()
    {
        $tenantContext = app(TenantContext::class);
        $companyId = $tenantContext->companyId();
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        $this->roles = Role::query()
            ->when(!$isSuperAdmin, function($query) use ($companyId) {
                // Regular admins only see roles for their own company
                $query->where('company_id', $companyId);
            })
            ->orderBy('name')
            ->get();

        $this->permissions = Permission::query()
            ->when($this->searchPermissions, function($query) {
                $query->where('name', 'like', '%' . $this->searchPermissions . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function selectRole($id)
    {
        $this->selectedRoleId = $id;
    }

    public function createRole()
    {
        $tenantContext = app(TenantContext::class);
        $companyId = $tenantContext->companyId();

        $this->validate([
            'newRoleName' => 'required|string',
        ]);

        // Check uniqueness within the same company context
        $exists = Role::where('name', $this->newRoleName)
            ->where('company_id', $companyId)
            ->exists();

        if ($exists) {
            $this->addError('newRoleName', 'This role already exists in this company.');
            return;
        }

        Role::create([
            'name' => $this->newRoleName,
            'company_id' => $companyId,
            'guard_name' => 'web'
        ]);
        $this->newRoleName = '';
        $this->refreshData();
        session()->flash('status', 'Role created successfully.');
    }

    public function createPermission()
    {
        $this->validate([
            'newPermissionName' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->newPermissionName]);
        $this->newPermissionName = '';
        $this->refreshData();
        session()->flash('status', 'Permission created successfully.');
    }

    public function togglePermission($permissionName)
    {
        if (!$this->selectedRoleId) return;

        $role = Role::findById($this->selectedRoleId);
        
        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->refreshData();
    }

    public function deleteRole($id)
    {
        $role = Role::findById($id);
        if ($role->name === 'super_admin') {
            session()->flash('error', 'The super_admin role cannot be deleted.');
            return;
        }

        $role->delete();
        $this->refreshData();
        if ($this->selectedRoleId == $id) {
            $this->selectedRoleId = $this->roles->count() > 0 ? $this->roles->first()->id : null;
        }
        session()->flash('status', 'Role deleted successfully.');
    }

    public function render()
    {
        $this->refreshData();
        $selectedRole = $this->selectedRoleId ? Role::findById($this->selectedRoleId) : null;
        $currentRolePermissions = $selectedRole ? $selectedRole->permissions->pluck('name')->toArray() : [];

        return view('livewire.roles.roles-permissions', [
            'currentRolePermissions' => $currentRolePermissions,
            'selectedRole' => $selectedRole
        ]);
    }
}
