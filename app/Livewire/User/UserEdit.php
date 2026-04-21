<?php

namespace App\Livewire\User;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class UserEdit extends Component
{
    public int $userId;
    public User $user;

    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';

    public ?int $company_id = null;
    public ?int $branch_id = null;

    public $companies = [];
    public $branches = [];
    public string $routePrefix = 'admin';

    public function mount(int $id, TenantContext $tenantContext): void
    {
        $companyId = $tenantContext->companyId();

        $this->userId = $id;

        // 1. Resolve hierarchy for current admin
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        // 2. Resolve the user WITHOUT restrictive global scopes for existence check
        // while maintaining security via explicit hierarchy validation.
        $this->user = User::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        // 3. Security Check: Prevent editing Super Admins unless you are one
        if ($this->user->hasRole('Super Admin') && !$isSuperAdmin) {
            abort(403, 'You are not authorized to edit a Super Admin account.');
        }

        // 4. Security Check: Ensure the target user is within the admin's management hierarchy
        if (!$isSuperAdmin) {
            if (!in_array($this->user->company_id, $manageableHierarchy)) {
                abort(403, 'You do not have permission to edit this user (Cross-tenant access denied).');
            }
        }

        $this->first_name = (string) ($this->user->first_name ?? '');
        $this->middle_name = (string) ($this->user->middle_name ?? '');
        $this->last_name = (string) ($this->user->last_name ?? '');
        $this->email = (string) $this->user->email;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');

        $this->company_id = $this->user->company_id;

        if ($isSuperAdmin) {
            $this->companies = Company::orderBy('name')->get();
        } else {
            $myCompanyId = $tenantContext->companyId();
            $this->companies = Company::where('id', $myCompanyId)
                ->orWhere('parent_id', $myCompanyId)
                ->orderBy('name')
                ->get();
        }

        if (request()->is('admin*')) {
            $this->routePrefix = 'admin';
        } elseif (request()->is('company*')) {
            $this->routePrefix = 'company';
        }

        $this->branch_id = $this->user->branch_id;
        $this->updatedCompanyId();
    }

    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->branches = Branch::where('company_id', $this->company_id)->orderBy('name')->get();
        } else {
            $this->branches = [];
        }
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'company_id' => [auth()->user()->hasRole('Super Admin') ? 'required' : 'nullable', 'exists:companies,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'first_name.required' => 'First name is mandatory.',
            'last_name.required' => 'Last name is mandatory.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email.',
            'email.unique' => 'This email is already in use by another account.',
            'password.min' => 'If changing password, it must be at least 8 characters.',
            'company_id.required' => 'Please select a company.',
            'branch_id.required' => 'Please select a branch.',
        ];
    }

    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate();

        $this->user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'company_id' => $this->company_id,
            'branch_id' => $validated['branch_id'],
        ]);

        if (!empty($validated['password'])) {
            $this->user->update(['password' => Hash::make($validated['password'])]);
        }

        session()->flash('status', 'User updated successfully.');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user.edit');
    }
}
