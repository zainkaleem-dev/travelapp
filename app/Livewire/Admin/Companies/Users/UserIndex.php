<?php

namespace App\Livewire\Admin\Companies\Users;

use App\Models\User;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class UserIndex extends Component
{
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;

    public bool $createModalOpen = false;
    public bool $editModalOpen = false;
    public ?int $editingUserId = null;

    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';

    public function mount(): void
    {
        $this->currentPage = (int) request()->query('page', 1);
    }

    #[\Livewire\Attributes\On('paginationGoTo')]
    public function goToPage($page): void
    {
        $this->currentPage = (int) $page;
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function openCreate(): void
    {
        $this->reset(['first_name', 'middle_name', 'last_name', 'email', 'password']);
        $this->resetErrorBag();
        $this->createModalOpen = true;
    }

    public function closeCreate(): void
    {
        $this->createModalOpen = false;
        $this->resetErrorBag();
    }

    public function openEdit(int $userId, TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);

        $user = User::query()
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('is_super_admin')->orWhere('is_super_admin', false);
            })
            ->findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->first_name = (string) ($user->first_name ?? '');
        $this->middle_name = (string) ($user->middle_name ?? '');
        $this->last_name = (string) ($user->last_name ?? '');
        $this->email = (string) $user->email;
        $this->password = '';

        $this->resetErrorBag();
        $this->editModalOpen = true;
    }

    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->editingUserId = null;
        $this->password = '';
        $this->resetErrorBag();
    }

    public function create(TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        if ($companyId <= 0) {
            $this->addError('email', 'Please select a company first.');
            return;
        }

        $validated = $this->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::query()->create([
            'company_id' => $companyId,
            'first_name' => $validated['first_name'] ?: null,
            'middle_name' => $validated['middle_name'] ?: null,
            'last_name' => $validated['last_name'] ?: null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        session()->flash('status', 'User created successfully.');
        $this->closeCreate();
    }

    public function update(TenantContext $tenantContext): void
    {
        if (!$this->editingUserId) {
            return;
        }

        $companyId = (int) ($tenantContext->companyId() ?? 0);

        $user = User::query()
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('is_super_admin')->orWhere('is_super_admin', false);
            })
            ->findOrFail($this->editingUserId);

        $validated = $this->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->forceFill([
            'first_name' => $validated['first_name'] ?: null,
            'middle_name' => $validated['middle_name'] ?: null,
            'last_name' => $validated['last_name'] ?: null,
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->forceFill(['password' => Hash::make($validated['password'])]);
        }

        $user->save();

        session()->flash('status', 'User updated successfully.');
        $this->closeEdit();
    }

    public function delete(int $userId, TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);

        $user = User::query()
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('is_super_admin')->orWhere('is_super_admin', false);
            })
            ->findOrFail($userId);

        $user->delete();
        session()->flash('status', 'User deleted successfully.');
    }

    public function render(PaginationService $paginationService, TenantContext $tenantContext)
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        $search = trim($this->search);

        $query = User::query()
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('is_super_admin')->orWhere('is_super_admin', false);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('id');

        $users = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('Livewire.admin.companies.users.index', [
            'users' => $users,
            'paginationMeta' => $paginationService->getPaginationMeta($users),
        ]);
    }
}

