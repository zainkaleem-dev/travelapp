<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyUserRoles extends Component
{
    public int $companyId;
    public Company $company;
    public array $selectedRoles = [];
    public array $userNotes = [];

    public function mount(int $id): void
    {
        $this->companyId = $id;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to view this organization (Access denied).');
        }

        setPermissionsTeamId($this->companyId);

        $users = User::query()
            ->where('company_id', $this->companyId)
            ->withoutRole('Super Admin')
            ->with('roles')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        foreach ($users as $user) {
            $this->selectedRoles[$user->id] = optional($user->roles->first())->name ?? '';
            $this->userNotes[$user->id] = (string) ($user->notes ?? '');
        }
    }

    public function saveUserSettings(int $userId): void
    {
        $user = User::query()
            ->where('company_id', $this->companyId)
            ->withoutRole('Super Admin')
            ->findOrFail($userId);

        $roleName = trim((string) ($this->selectedRoles[$userId] ?? ''));
        $note = trim((string) ($this->userNotes[$userId] ?? ''));

        if ($roleName === '') {
            $this->addError("selectedRoles.{$userId}", 'Please select a role.');
            return;
        }

        $roleExists = Role::query()
            ->where('name', $roleName)
            ->where(function ($query) {
                $query->whereNull('company_id')->orWhere('company_id', $this->companyId);
            })
            ->exists();

        if (!$roleExists) {
            $this->addError("selectedRoles.{$userId}", 'Selected role is not available.');
            return;
        }

        setPermissionsTeamId($this->companyId);
        $user->syncRoles([$roleName]);
        $user->update([
            'notes' => $note !== '' ? $note : null,
        ]);

        session()->flash('status', "Updated role and notes for {$user->display_name}.");
    }

    public function render()
    {
        setPermissionsTeamId($this->companyId);

        return view('livewire.company.user-roles', [
            'users' => User::query()
                ->where('company_id', $this->companyId)
                ->withoutRole('Super Admin')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(['id', 'first_name', 'middle_name', 'last_name', 'email', 'notes']),
            'roles' => Role::query()
                ->where(function ($query) {
                    $query->whereNull('company_id')->orWhere('company_id', $this->companyId);
                })
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }
}

