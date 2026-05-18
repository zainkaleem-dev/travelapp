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

    public ?string $phone = null;
    public ?string $dob = null;
    public ?string $gender = null;
    public ?string $nationality = null;

    public ?string $passport_number = null;
    public ?string $expiry_date = null;
    public ?string $issuing_country = null;

    public ?string $purpose_of_travel = null;
    public ?string $seat_preference = null;
    public ?string $meal_preference = null;
    public ?string $preferred_cabin = null;
    public ?string $preferred_airline = null;

    public $companies = [];
    public $branches = [];
    public string $routePrefix = 'admin';
    public string $tab = 'personal';
    public bool $isTmc = false;

    public $availableGrades = [];
    public array $selectedGrades = [];

    public array $familyMembers = [];

    // Family Member Temp Fields
    public string $f_first_name = '';
    public string $f_last_name = '';
    public string $f_email = '';
    public ?string $f_phone = null;
    public ?string $f_dob = null;
    public ?string $f_gender = null;
    public ?string $f_nationality = null;
    public ?string $f_passport_number = null;
    public ?string $f_expiry_date = null;
    public ?string $f_issuing_country = null;

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
        $this->fetchGrades();

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
            $company = Company::find($this->company_id);
            $this->isTmc = ($company?->company_type === 'TMC');
            $this->branches = Branch::where('company_id', $this->company_id)->orderBy('name')->get();
            $this->fetchGrades();
        } else {
            $this->isTmc = false;
            $this->branches = [];
            $this->availableGrades = [];
        }
    }

    public function fetchGrades()
    {
        if ($this->company_id) {
            $this->availableGrades = \App\Models\Grade::where('company_id', $this->company_id)
                ->where('status', 'Active')
                ->orderBy('name')
                ->get();
        } else {
            $this->availableGrades = [];
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
            'phone' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'issuing_country' => ['nullable', 'string', 'max:255'],
            'purpose_of_travel' => ['nullable', 'string', 'max:255'],
            'seat_preference' => ['nullable', 'string', 'max:255'],
            'meal_preference' => ['nullable', 'string', 'max:255'],
            'preferred_cabin' => ['nullable', 'string', 'max:255'],
            'preferred_airline' => ['nullable', 'string', 'max:255'],
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
        if ($count >= $limit && !auth()->user()->hasRole('Super Admin')) {
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
            
            // Check if this is the first user of the company
            $isFirstUser = User::where('company_id', $this->company_id)->count() <= 1;
            
            $defaultRoleName = 'User';
            if ($isFirstUser) {
                $targetCompany = Company::find($this->company_id);
                if ($targetCompany) {
                    if (is_null($targetCompany->parent_id)) {
                        $defaultRoleName = 'Organization Admin';
                    } else {
                        $defaultRoleName = 'Partner Admin';
                    }
                }
            }

            // Explicitly resolve the role belonging to THIS company to prevent Global role bleeds
            $role = \App\Models\Role::where('name', $defaultRoleName)
                ->where('company_id', $this->company_id)
                ->first();

            if ($role) {
                $user->assignRole($role);
            } else {
                // Fallback or error if company roles aren't seeded yet
                $user->assignRole($defaultRoleName);
            }
        }

        \App\Models\UserPersonalInfo::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'] ?? null,
            'dob' => !empty($validated['dob']) ? date('Y-m-d', strtotime($validated['dob'])) : null,
            'gender' => $validated['gender'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'passport_number' => $validated['passport_number'] ?? null,
            'expiry_date' => !empty($validated['expiry_date']) ? date('Y-m-d', strtotime($validated['expiry_date'])) : null,
            'issuing_country' => $validated['issuing_country'] ?? null,
            'purpose_of_travel' => $validated['purpose_of_travel'] ?? null,
            'seat_preference' => $validated['seat_preference'] ?? null,
            'meal_preference' => $validated['meal_preference'] ?? null,
            'preferred_cabin' => $validated['preferred_cabin'] ?? null,
            'preferred_airline' => $validated['preferred_airline'] ?? null,
        ]);

        foreach ($this->familyMembers as $fm) {
            \App\Models\UserFamilyInfo::create([
                'user_id' => $user->id,
                'first_name' => $fm['first_name'],
                'last_name' => $fm['last_name'],
                'email' => $fm['email'],
                'phone' => $fm['phone'],
                'dob' => $fm['dob'],
                'gender' => $fm['gender'],
                'nationality' => $fm['nationality'],
                'passport_number' => $fm['passport_number'],
                'expiry_date' => $fm['expiry_date'],
                'issuing_country' => $fm['issuing_country'],
            ]);
        }

        if (!empty($this->selectedGrades)) {
            $user->grades()->sync($this->selectedGrades);
        }

        session()->flash('status', 'User created successfully with family members.');
        return redirect()->route('users.index', ['companyId' => $this->company_id]);
    }

    public function addFamilyMember()
    {
        $this->validate([
            'f_first_name' => 'required|string|max:255',
            'f_last_name' => 'required|string|max:255',
            'f_email' => 'nullable|email|max:255',
            'f_phone' => 'nullable|string|max:255',
            'f_dob' => 'nullable|date',
            'f_gender' => 'nullable|string',
            'f_nationality' => 'nullable|string',
            'f_passport_number' => 'nullable|string',
            'f_expiry_date' => 'nullable|date',
            'f_issuing_country' => 'nullable|string',
        ]);

        $this->familyMembers[] = [
            'first_name' => $this->f_first_name,
            'last_name' => $this->f_last_name,
            'email' => $this->f_email,
            'phone' => $this->f_phone,
            'dob' => $this->f_dob,
            'gender' => $this->f_gender,
            'nationality' => $this->f_nationality,
            'passport_number' => $this->f_passport_number,
            'expiry_date' => $this->f_expiry_date,
            'issuing_country' => $this->f_issuing_country,
        ];

        // Reset fields
        $this->reset([
            'f_first_name', 'f_last_name', 'f_email', 'f_phone', 'f_dob', 
            'f_gender', 'f_nationality', 'f_passport_number', 'f_expiry_date', 'f_issuing_country'
        ]);
    }

    public function removeFamilyMember($index)
    {
        unset($this->familyMembers[$index]);
        $this->familyMembers = array_values($this->familyMembers);
    }

    public function render()
    {
        return view('livewire.user.create');
    }
}
