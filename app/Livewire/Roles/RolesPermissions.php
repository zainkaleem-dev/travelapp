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
    public $isCompanyRoute = false; // Whether we are on the companies.roles-permissions route

    // User Assignment Mode Properties
    public $viewMode = 'roles'; // 'roles' or 'users'
    public $searchUsers = '';
    public $selectedUserId = null;
    public $activeUser = null;
    public $editingRoleId = null;
    public $editingRoleName = '';

    private function getRoleRank($roleName): int
    {
        return match ($roleName) {
            'Super Admin' => 100,
            'Organization Admin' => 80,
            'Company Admin' => 60,
            'Branch Admin' => 40,
            'Agent' => 20,
            'User' => 0,
            default => 10, // Custom roles start with low rank
        };
    }

    private function getAuthUserMaxRank(): int
    {
        if ($this->isSuperAdmin)
            return 100;

        $maxRank = 0;
        foreach (auth()->user()->getRoleNames() as $roleName) {
            $rank = $this->getRoleRank($roleName);
            if ($rank > $maxRank)
                $maxRank = $rank;
        }
        return $maxRank;
    }

    public function mount(?int $id = null): void
    {
        $user = auth()->user();
        if (!$user) return;

        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);

        // 1. Determine local Super Admin status
        $this->isSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->where('roles.name', 'Super Admin')
            ->whereNull('model_has_roles.company_id')
            ->exists();

        // 2. Fetch manageable companies for the context switcher
        $manageableIds = $tenantContext->getManageableHierarchy($user);
        
        // Exclude own company for non-Super Admins
        $this->companies = \App\Models\Company::whereIn('id', $manageableIds)
            ->when(!$this->isSuperAdmin, function ($q) use ($user) {
                $q->where('id', '!=', $user->company_id);
            })
            ->orderBy('name')
            ->get();

        // 3. Set initial context from route or session/default
        if ($id !== null) {
            $this->contextCompanyId = $id;
            $this->isCompanyRoute = true;
        } else {
            $this->contextCompanyId = session('active_company_id');
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
        $companyId = $this->getNormalizedCompanyId();

        // If not super admin and no context selected, default to their own company
        // However, the UI now filters out their own company from context selection
        if (!$this->isSuperAdmin && $companyId === null) {
            $companyId = $tenantContext->companyId();
        }

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

    public function getActiveCompanyProperty()
    {
        $id = $this->getNormalizedCompanyId();
        return $id ? \App\Models\Company::find($id) : null;
    }

    public function getSidebarUsersProperty()
    {
        $tenantContext = app(\App\Support\TenantContext::class);
        $companyId = $this->getNormalizedCompanyId();

        if (!$this->isSuperAdmin && $companyId === null) {
            $companyId = $tenantContext->companyId();
        }

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

        // Requirement 1: Cannot change OWN role
        if ($this->selectedUserId == auth()->id()) {
            session()->flash('error', 'You cannot modify your own roles for security reasons.');
            return;
        }

        // Requirement 2: Cannot manage roles higher than your own
        $authMaxRank = $this->getAuthUserMaxRank();
        $targetRoleRank = $this->getRoleRank($roleName);

        if ($targetRoleRank > $authMaxRank) {
            session()->flash('error', 'You do not have permission to manage higher-ranking roles.');
            return;
        }

        $user = \App\Models\User::find($this->selectedUserId);

        // Handle Team/Company Context based on the role/user native context
        $teamId = $user->company_id;
        setPermissionsTeamId($teamId);

        // Explicitly resolve the role record within the user's company context
        $role = \App\Models\Role::where('name', $roleName)
            ->where('company_id', $teamId)
            ->first();

        if (!$role) {
            session()->flash('error', "Role '{$roleName}' not found for this company context.");
            return;
        }

        if ($user->hasRole($role)) {
            $user->removeRole($role);
        } else {
            $user->assignRole($role);
        }
    }





    public function getAllPermissionsProperty()
    {
        // Resolve the selected role once to check its name for specific UI filtering
        $selectedRole = $this->selectedRoleId ? \App\Models\Role::find($this->selectedRoleId) : null;

        // Determine the current context company ID
        $contextCompanyId = $this->isSuperAdmin ? $this->getNormalizedCompanyId() : app(\App\Support\TenantContext::class)->companyId();

        return Permission::query()
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
            $role->givePermissionTo($permissionName);
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

    public function toggleUserAssignment($roleId)
    {
        if (!$this->selectedUserId)
            return;

        // Requirement 1: Cannot change OWN role
        if ($this->selectedUserId == auth()->id()) {
            session()->flash('error', 'You cannot modify your own roles for security reasons.');
            return;
        }

        $user = \App\Models\User::find($this->selectedUserId);
        if (!$user)
            return;

        $teamId = $user->company_id;
        setPermissionsTeamId($teamId);

        // Find the specific role record
        $role = \App\Models\Role::find($roleId);
        if (!$role)
            return;

        // Requirement 2: Cannot manage roles higher than your own
        $authMaxRank = $this->getAuthUserMaxRank();
        $targetRoleRank = $this->getRoleRank($role->name);

        if ($targetRoleRank > $authMaxRank) {
            session()->flash('error', 'You do not have permission to manage higher-ranking roles.');
            return;
        }

        // Use Spatie's native methods which respect setPermissionsTeamId
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            session()->flash('status', "Role '{$role->name}' removed from user.");
        } else {
            $user->assignRole($role);
            session()->flash('status', "Role '{$role->name}' assigned to user.");
        }

        // Clear permissions cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function toggleDoubleSync($roleName, $roleId)
    {
        // If we are in 'users' mode, we toggle the specific role ID assignment
        if ($this->viewMode === 'users') {
            $this->toggleUserAssignment($roleId);
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
        $currentUserRoleIds = [];
        $contextRoles = [];

        if ($this->viewMode === 'users' && $this->activeUser) {
            // Context follows the selected user's own company
            $teamId = $this->activeUser->company_id;
            setPermissionsTeamId($teamId);

            // Read ACTUAL assignments by ID from pivot - bypasses name-match bleeding and ORM filters
            $currentUserRoleIds = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_id', $this->activeUser->id)
                ->where('model_type', \App\Models\User::class)
                ->where('company_id', $teamId)
                ->pluck('role_id')
                ->toArray();

            $authMaxRank = $this->getAuthUserMaxRank();
            $contextRoles = \App\Models\Role::query()
                ->where('company_id', $teamId)
                ->when($teamId === null, function ($query) {
                    // In User assignment mode, for global context (Super Admin)
                    $query->where('name', 'Super Admin');
                })
                ->orderBy('name')
                ->get()
                ->filter(function ($role) use ($authMaxRank, $currentUserRoleIds) {
                    // Requirement: Only show roles at or below the authorized user's rank
                    $isSubordinateRole = $this->getRoleRank($role->name) <= $authMaxRank;

                    // New Requirement: If viewing SELF, only show roles they currently HAVE
                    if ($this->activeUser->id === auth()->id()) {
                        return $isSubordinateRole && in_array($role->id, $currentUserRoleIds);
                    }

                    return $isSubordinateRole;
                });
        }

        $sidebarRoles = $this->getSidebarRolesProperty();

        return view('livewire.roles.roles-permissions', [
            'sidebarRoles' => $sidebarRoles,
            'allPermissions' => $this->allPermissions,
            'currentRolePermissions' => $currentRolePermissions,
            'selectedRole' => $selectedRole,
            'sidebarUsers' => $this->sidebarUsers,
            'currentUserRoleIds' => $currentUserRoleIds,
            'contextRoles' => $contextRoles,
        ]);
    }
}
