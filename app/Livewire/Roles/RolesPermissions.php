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

        return \App\Models\User::query()
            ->when($companyId, function ($q) use ($companyId) {
                // Filter users by the selected company context
                $q->where('company_id', $companyId);
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

        // Handle Team/Company Context
        $teamId = $this->getNormalizedCompanyId();
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

        // Use standard Eloquent to bypass Spatie's strict findById
        $role = \App\Models\Role::find($this->selectedRoleId);
        if (!$role || $role->company_id === null)
            return;

        // Set context to match the role's company
        setPermissionsTeamId($role->company_id);

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

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
            // Check if role is active before assigning
            $role = \App\Models\Role::where('name', $roleName)->where('company_id', $teamId)->first();
            if ($role && !$role->status) {
                session()->flash('error', "Cannot assign inactive role '{$roleName}'. Please activate it first.");
                return;
            }
            $user->assignRole($roleName);
            session()->flash('status', "Role '{$roleName}' assigned to user.");
        }
    }

    public function toggleDoubleSync($roleName, $roleId)
    {
        // 1. Toggle Global Status
        $role = \App\Models\Role::find($roleId);
        if (!$role || $role->company_id === null)
            return;

        $role->status = !$role->status;
        $role->save();

        // 2. Sync User Assignment to match the new status
        if ($this->selectedUserId) {
            $user = \App\Models\User::find($this->selectedUserId);
            // Context follows the user's company
            $teamId = $user->company_id;
            setPermissionsTeamId($teamId);

            if ($role->status) {
                $user->assignRole($roleName);
            } else {
                $user->removeRole($roleName);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $statusText = $role->status ? 'Active and Assigned' : 'Inactive and Removed';
        session()->flash('status', "Role '{$roleName}' is now {$statusText}.");
    }

    public function deleteRole($id)
    {
        $role = \App\Models\Role::find($id);
        if (!$role)
            return;

        if ($role->name === 'Super Admin') {
            session()->flash('error', 'Cannot delete Super Admin.');
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
