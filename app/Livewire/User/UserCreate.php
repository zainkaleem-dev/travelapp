<?php

namespace App\Livewire\User;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class UserCreate extends Component
{
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

    public function mount(TenantContext $tenantContext, ?int $companyId = null)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        if ($companyId) {
            $this->company_id = $companyId;
        }

        if ($isSuperAdmin) {
            $this->companies = Company::orderBy('name')->get();
        } else {
            $this->company_id = $this->company_id ?: $tenantContext->companyId();
            $this->companies = Company::where('id', $this->company_id)
                ->orWhere('parent_id', $this->company_id)
                ->orderBy('name')
                ->get();
        }
        
        $this->updatedCompanyId();

        if (request()->is('admin*') || request()->is('companies*')) {
            $this->routePrefix = 'admin';
        } elseif (request()->is('company*')) {
            $this->routePrefix = 'company';
        }
        $this->authorize('Create User');
    }

    public function updatedCompanyId()
    {
        $this->branch_id = null;
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'company_id' => [auth()->user()->hasRole('Super Admin') ? 'required' : 'nullable', 'exists:companies,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'first_name.required' => 'Please enter the user\'s first name.',
            'last_name.required' => 'Please enter the user\'s last name.',
            'email.required' => 'An email address is required for login.',
            'email.email' => 'Please provide a valid email format.',
            'email.unique' => 'This email is already registered in our system.',
            'password.required' => 'Set a strong password for the new account.',
            'password.min' => 'For security, passwords must be at least 8 characters long.',
            'company_id.required' => 'You must assign this user to a company.',
            'branch_id.required' => 'Please select a specific branch for this user.',
        ];
    }

    public function save(TenantContext $tenantContext)
    {
        // 1. Resolve Limit
        // We use the target company_id for scoping the feature check
        $scope = $this->company_id ? Company::find($this->company_id) : null;
        $limit = (int) \Laravel\Pennant\Feature::for($scope)->value('users-quantity');

        // 2. Count Existing for target company
        $count = User::where('company_id', $this->company_id)->count();

        // 3. Prevent save if limit is reached
        if ($count >= $limit) {
            session()->flash('error', "Limit reached. This company is only allowed to have {$limit} users.");
            return $this->redirect(route('users.index', ['companyId' => $this->company_id]));
        }

        $validated = $this->validate();
        $plainPassword = $validated['password'];

        $user = User::query()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $this->company_id,
            'branch_id' => $validated['branch_id'],
            'email_verified_at' => null,
            'has_set_password' => false,
        ]);

        Cache::put(
            "pending_user_password:{$user->id}",
            Crypt::encryptString($plainPassword),
            now()->addDays(7)
        );

        if ($this->company_id) {
            setPermissionsTeamId($this->company_id);
            // Explicitly resolve the role belonging to THIS company to prevent Global role bleeds
            $role = \App\Models\Role::where('name', 'User')
                ->where('company_id', $this->company_id)
                ->first();

            if ($role) {
                $user->assignRole($role);
            } else {
                // Fallback or error if company roles aren't seeded yet
                $user->assignRole('User');
            }
        }

        session()->flash('status', "User '{$user->display_name}' created successfully with default permissions.");
        return redirect()->route('users.index', ['companyId' => $this->company_id]);
    }

    public function render()
    {
        return view('livewire.user.create');
    }
}
