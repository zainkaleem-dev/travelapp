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
    public $contextCompanyId = null; // Filter roles by this company
    public $companies = [];          // List of companies for the selector

    // User Assignment Mode Properties
    public $viewMode = 'roles'; // 'roles' or 'users'
    public $searchUsers = '';
    public $selectedUserId = null;
    public $activeUser = null;

    public function mount(): void
    {
        $this->isSuperAdmin = auth()->user()->hasRole('Super Admin');

        if ($this->isSuperAdmin) {
            $this->companies = \App\Models\Company::orderBy('name')->get();
        }

        $this->setViewMode('roles');
    }

    /**
     * Helper to ensure contextCompanyId is either an integer or null.
     * Maps empty strings and "global" string to null.
     */
    private function getNormalizedCompanyId(): ?int
    {
        if (!$this->contextCompanyId || $this->contextCompanyId === 'global') {
            return null;
        }
        return (int) $this->contextCompanyId;
    }

    /**
     * Reset selection when context changes to prevent cross-context data leaks
     * or looking up IDs that don't exist in the new context.
     */
    public function updatedContextCompanyId(): void
    {
        $this->selectedRoleId = null;
        $this->selectedUserId = null;
        $this->activeUser = null;
        $this->search = '';
        $this->searchUsers = '';

        // Auto-select first item in new context if available
        $this->setViewMode($this->viewMode);
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
    }

    public function getSidebarRolesProperty()
    {
        $tenantContext = app(\App\Support\TenantContext::class);
        $companyId = $this->isSuperAdmin ? $this->getNormalizedCompanyId() : $tenantContext->companyId();

        // Ensure Spatie context is aligned with our filter
        setPermissionsTeamId($companyId);

        return \App\Models\Role::query()
            ->where('company_id', $companyId)
            ->when($companyId === null, function ($query) {
                // Strictly restrict the Global Context to only show the "Super Admin" role
                $query->where('name', 'Super Admin');
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
        $companyId = $this->isSuperAdmin ? $this->getNormalizedCompanyId() : $tenantContext->companyId();

        // Ensure Spatie context is aligned with our filter
        setPermissionsTeamId($companyId);

        return \App\Models\User::query()
            ->when($companyId, function ($q) use ($companyId) {
                // Filter users by the selected company context
                $q->where('company_id', $companyId);
            })
            ->when($companyId === null, function ($q) {
                // Strictly restrict the Global Context to only show Super Admins
                $q->role('Super Admin');
            })
            ->when($this->searchUsers, function ($query) {
                $query->where(function ($q) {
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
        if (!$this->selectedUserId)
            return;

        $user = \App\Models\User::find($this->selectedUserId);

        // Handle Team/Company Context based on the role/user native context
        $teamId = $user->company_id;
        setPermissionsTeamId($teamId);

        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
        } else {
            $user->assignRole($roleName);
        }
    }





    public function getAllPermissionsProperty()
    {
        // Resolve the selected role once to check its name for specific UI filtering
        $selectedRole = $this->selectedRoleId ? \App\Models\Role::find($this->selectedRoleId) : null;

        // Determine the current context company ID
        $contextCompanyId = $this->isSuperAdmin ? $this->getNormalizedCompanyId() : app(\App\Support\TenantContext::class)->companyId();

        return Permission::query()
            ->when($selectedRole && $selectedRole->name === 'Super Admin' && $selectedRole->company_id === null, function ($query) {
                // For the Super Admin role, only show the "Manage Global System" permission
                $query->where('name', 'Manage Global System');
            })
            ->when(($selectedRole && $selectedRole->company_id !== null) || $contextCompanyId !== null || !$this->isSuperAdmin, function ($query) {
                // NEVER show the "Manage Global System" permission in a company context/role
                // or to non-super admins. It is strictly for Global context roles.
                $query->where('name', '!=', 'Manage Global System');
            })
            ->when($this->searchPermissions, function ($query) {
                $query->where('name', 'like', '%' . $this->searchPermissions . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function editRole($id)
    {
        $role = \App\Models\Role::find($id);
        if (!$role)
            return;

        $protectedRoles = ['Super Admin', 'Company Admin', 'Organization Admin', 'Branch Admin', 'Agent', 'User'];
        if (in_array($role->name, $protectedRoles)) {
            session()->flash('error', "The '{$role->name}' role is a system-protected role and cannot be renamed.");
            return;
        }

        $this->editingRoleId = $id;
        $this->editingRoleName = $role->name;
    }

    public function cancelEdit()
    {
        $this->editingRoleId = null;
        $this->editingRoleName = '';
    }

    public function updateRole()
    {
        if (!$this->editingRoleId)
            return;

        $this->validate([
            'editingRoleName' => 'required|string|max:255',
        ]);

        $role = \App\Models\Role::find($this->editingRoleId);
        if (!$role)
            return;

        $protectedRoles = ['Super Admin', 'Company Admin', 'Organization Admin', 'Branch Admin', 'Agent', 'User'];
        if (in_array($role->name, $protectedRoles)) {
            session()->flash('error', "The '{$role->name}' role name is protected.");
            return;
        }

        $role->update(['name' => $this->editingRoleName]);
        $this->cancelEdit();
        session()->flash('status', 'Role updated successfully.');
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
            $companyId = $this->getNormalizedCompanyId();
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
        if (!$this->selectedRoleId)
            return;

        // Security check: Only Super Admins can touch the Global System Master Key
        if ($permissionName === 'Manage Global System' && !$this->isSuperAdmin) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        // Use standard Eloquent to bypass Spatie's strict findById
        $role = \App\Models\Role::find($this->selectedRoleId);
        if (!$role || $role->company_id === null && !$this->isSuperAdmin)
            return;

        // Set context to match the role's company
        setPermissionsTeamId($role->company_id);

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            // Explicitly attach with company_id pivot data to ensure isolation
            $permission = Permission::findByName($permissionName);
            $role->permissions()->attach($permission->id, ['company_id' => $role->company_id]);
        }

        // FORCE RELOAD: Ensure the next render gets the actual database state
        $role->unsetRelation('permissions');
        $role->load('permissions');

        // Force cache refresh for the current registrar
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function toggleGlobalStatus($roleId = null)
    {
        $id = $roleId ?: $this->selectedRoleId;
        if (!$id)
            return;

        $role = \App\Models\Role::find($id);
        if (!$role)
            return;

        // Global context roles (Super Admin, etc.) are currently read-only for safety
        if ($role->company_id === null) {
            session()->flash('error', 'Global context roles are read-only for safety.');
            return;
        }

        $role->status = !$role->status;
        $role->save();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        session()->flash('status', "Role '{$role->name}' status updated to " . ($role->status ? 'Active' : 'Inactive'));
    }

    public function toggleUserAssignment($roleName)
    {
        if (!$this->selectedUserId)
            return;

        $user = \App\Models\User::find($this->selectedUserId);
        // Context follows the user's company
        $teamId = $user->company_id;
        setPermissionsTeamId($teamId);

        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
            session()->flash('status', "Role '{$roleName}' removed from user.");
        } else {
            // Force assignment via Spatie's team-aware logic
            $user->assignRole($roleName);
        }

        // Clear permissions cache immediately
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function toggleDoubleSync($roleName, $roleId)
    {
        // If we are in 'users' mode, we only want to toggle the assignment, 
        // NOT the global role status.
        if ($this->viewMode === 'users') {
            $this->toggleUserAssignment($roleName);
            return;
        }

        // Toggle Global Status (Only for non-protected roles)
        $role = \App\Models\Role::find($roleId);
        if (!$role)
            return;

        // Skip global status toggle for Super Admin to ensure it stays active for the system
        if ($role->company_id !== null || $role->name !== 'Super Admin') {
            $role->status = !$role->status;
            $role->save();
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $statusText = $role->status ? 'Active' : 'Inactive';
        session()->flash('status', "Role '{$roleName}' status is now {$statusText}.");
    }

    public function deleteRole($id)
    {
        $role = \App\Models\Role::find($id);
        if (!$role)
            return;

        $protectedRoles = ['Super Admin', 'Company Admin', 'Organization Admin', 'Branch Admin', 'Agent', 'User'];
        if (in_array($role->name, $protectedRoles)) {
            session()->flash('error', "Cannot delete system-protected role '{$role->name}'.");
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
            // Context follows the selected user's own company
            $teamId = $this->activeUser->company_id;
            setPermissionsTeamId($teamId);

            $currentUserRoles = $this->activeUser->roles()->pluck('name')->toArray();

            $contextRoles = \App\Models\Role::query()
                ->where('company_id', $teamId)
                ->when($teamId === null, function ($query) {
                    // In User assignment mode, for global context (Super Admin)
                    $query->where('name', 'Super Admin');
                })
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
