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
    public $selectedRoleId = null;
    public $newRoleName = '';
    public $newPermissionName = '';
    public $newRoleCompanyId = 'global';
    public $isSuperAdmin = false;

    // User Assignment Mode Properties
    public $viewMode = 'roles'; // 'roles' or 'users'
    public $searchUsers = '';
    public $selectedUserId = null;
    public $activeUser = null;
    public $userContextCompanyId = null; // Which company's roles we are assigning to the user

    public function mount(): void
    {
        $this->isSuperAdmin = auth()->user()->hasRole('super_admin');
        $this->setViewMode('roles');
    }

    public function setViewMode($mode): void
    {
        $this->viewMode = $mode;
        if ($mode === 'users') {
            $this->selectedRoleId = null;
            // Pre-select first user if available
            $users = $this->getSidebarUsersProperty();
            if ($users->count() > 0) {
                $this->selectUser($users->first()->id);
            }
        } else {
            $this->selectedUserId = null;
            $this->activeUser = null;
            // Pre-select first role
            $roles = $this->getSidebarRolesProperty();
            if ($roles->count() > 0) {
                $this->selectRole($roles->first()->id);
            }
        }
    }

    public function selectRole($id): void
    {
        $this->selectedRoleId = $id;
    }

    public function selectUser($id): void
    {
        $this->selectedUserId = $id;
        $this->activeUser = \App\Models\User::find($id);
        
        // Auto-select a context company if they are a company admin, or prompt global if super admin
        if ($this->isSuperAdmin) {
            $this->userContextCompanyId = 'global';
        } else {
            $tenantContext = app(\App\Support\TenantContext::class);
            $this->userContextCompanyId = $tenantContext->companyId();
        }
    }

    public function getSidebarRolesProperty()
    {
        $tenantContext = app(\App\Support\TenantContext::class);
        $companyId = $this->isSuperAdmin ? null : $tenantContext->companyId();

        return \App\Models\Role::query()
            ->when(!$this->isSuperAdmin && $companyId, function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->with('company')
            ->orderBy('company_id')
            ->orderBy('name')
            ->get();
    }

    public function getSidebarUsersProperty()
    {
        $tenantContext = app(\App\Support\TenantContext::class);
        $companyId = $this->isSuperAdmin ? null : $tenantContext->companyId();

        return \App\Models\User::query()
            ->when(!$this->isSuperAdmin && $companyId, function($q) use ($companyId) {
                // Not ideal, but realistically users in a tenant app are scoped if they lack super admin.
                // Assuming standard scopes apply or auth user company access.
                // For simplicity, we just list all non-super-admin users if we don't have a direct company relation on User yet.
            })
            ->when($this->searchUsers, function ($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->searchUsers . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchUsers . '%')
                      ->orWhere('email', 'like', '%' . $this->searchUsers . '%');
                });
            })
            ->orderBy('first_name')
            ->get();
    }

    public function toggleUserRole($roleName)
    {
        if (!$this->selectedUserId) return;

        $user = \App\Models\User::find($this->selectedUserId);
        
        // Handle Team/Company Context
        $teamId = $this->userContextCompanyId === 'global' ? null : (int) $this->userContextCompanyId;
        setPermissionsTeamId($teamId);

        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
        } else {
            $user->assignRole($roleName);
        }
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
        $this->validate([
            'newRoleName' => 'required|string',
        ]);

        $companyId = null;
        if (!$this->isSuperAdmin) {
            $companyId = app(\App\Support\TenantContext::class)->companyId();
        } else {
            $companyId = $this->newRoleCompanyId === 'global' ? null : $this->newRoleCompanyId;
        }

        $exists = \App\Models\Role::where('name', $this->newRoleName)
            ->where('company_id', $companyId)
            ->exists();

        if ($exists) {
            $this->addError('newRoleName', 'This role already exists.');
            return;
        }

        // Switch to the target context BEFORE creating the role
        // This ensures Spatie's internal validation respects the chosen company
        setPermissionsTeamId($companyId);

        $role = \App\Models\Role::create([
            'name' => $this->newRoleName,
            'company_id' => $companyId,
            'guard_name' => 'web'
        ]);

        $this->newRoleName = '';
        $this->selectRole($role->id);
        session()->flash('status', 'Role created successfully.');
    }

    public function togglePermission($permissionName)
    {
        if (!$this->selectedRoleId) return;

        // Use standard Eloquent to bypass Spatie's strict findById
        $role = \App\Models\Role::find($this->selectedRoleId);
        if (!$role) return;

        // Set context to match the role's company
        setPermissionsTeamId($role->company_id);
        
        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function deleteRole($id)
    {
        $role = \App\Models\Role::find($id);
        if (!$role) return;

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
        // Resolve selected role safely
        $selectedRole = $this->selectedRoleId ? \App\Models\Role::find($this->selectedRoleId) : null;
        
        $currentRolePermissions = [];
        if ($selectedRole) {
            // Set context to the role's company to fetch its permissions correctly
            setPermissionsTeamId($selectedRole->company_id);
            $currentRolePermissions = $selectedRole->permissions->pluck('name')->toArray();
        }

        // For user assignment mode
        $currentUserRoles = [];
        $contextRoles = [];
        
        if ($this->viewMode === 'users' && $this->activeUser) {
            $teamId = $this->userContextCompanyId === 'global' ? null : $this->userContextCompanyId;
            setPermissionsTeamId($teamId);
            
            $currentUserRoles = $this->activeUser->roles()->pluck('name')->toArray();
            
            $contextRoles = \App\Models\Role::query()
                ->where('company_id', $teamId)
                ->orderBy('name')
                ->get();
        }

        $sidebarRoles = $this->getSidebarRolesProperty();

        return view('livewire.roles.roles-permissions', [
            'sidebarRoles' => $sidebarRoles,
            'allPermissions' => $this->allPermissions,
            'currentRolePermissions' => $currentRolePermissions,
            'selectedRole' => $selectedRole,
            'sidebarUsers' => $this->sidebarUsers,
            'currentUserRoles' => $currentUserRoles,
            'contextRoles' => $contextRoles,
        ]);
    }
}
