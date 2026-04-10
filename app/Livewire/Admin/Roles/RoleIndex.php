<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.flight')]
class RoleIndex extends Component
{
    public string $name = '';
    public bool $editModalOpen = false;
    public ?int $editingRoleId = null;
    public string $editName = '';

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where(fn ($query) => $query->where('guard_name', 'web')),
            ],
        ];
    }

    public function saveRole(): void
    {
        $validated = $this->validate();

        Role::query()->create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $this->reset('name');
        session()->flash('status', 'Role created successfully.');
        $this->dispatch('role-created');
    }

    public function openEdit(int $roleId): void
    {
        $role = Role::query()->findOrFail($roleId);

        $this->editingRoleId = (int) $role->id;
        $this->editName = (string) $role->name;
        $this->resetErrorBag();
        $this->editModalOpen = true;
    }

    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->editingRoleId = null;
        $this->editName = '';
        $this->resetErrorBag();
    }

    public function updateRole(): void
    {
        if (!$this->editingRoleId) {
            return;
        }

        $validated = $this->validate([
            'editName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($this->editingRoleId)
                    ->where(fn ($query) => $query->where('guard_name', 'web')),
            ],
        ]);

        $role = Role::query()->findOrFail($this->editingRoleId);
        $role->update([
            'name' => $validated['editName'],
        ]);

        session()->flash('status', 'Role updated successfully.');
        $this->closeEdit();
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::query()->findOrFail($roleId);
        $role->delete();

        session()->flash('status', 'Role deleted successfully.');
    }

    public function render()
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->latest('id')
            ->get();

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
        ]);
    }
}
