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

    public function mount(int $id, TenantContext $tenantContext, ?int $companyId = null): void
    {
        $this->company_id = $companyId ?: $tenantContext->companyId();

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

        $pi = \App\Models\UserPersonalInfo::where('user_id', $this->userId)->first();
        if ($pi) {
            $this->phone = $pi->phone;
            $this->dob = $pi->dob?->format('Y-m-d');
            $this->gender = $pi->gender;
            $this->nationality = $pi->nationality;

            $this->passport_number = $pi->passport_number;
            $this->expiry_date = $pi->expiry_date?->format('Y-m-d');
            $this->issuing_country = $pi->issuing_country;

            $this->purpose_of_travel = $pi->purpose_of_travel;
            $this->seat_preference = $pi->seat_preference;
            $this->meal_preference = $pi->meal_preference;
            $this->preferred_cabin = $pi->preferred_cabin;
            $this->preferred_airline = $pi->preferred_airline;
        }
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

        $pi = \App\Models\UserPersonalInfo::firstOrNew(['user_id' => $this->userId]);
        $pi->phone = $validated['phone'] ?? null;
        $pi->dob = !empty($validated['dob']) ? date('Y-m-d', strtotime($validated['dob'])) : null;
        $pi->gender = $validated['gender'] ?? null;
        $pi->nationality = $validated['nationality'] ?? null;
        $pi->passport_number = $validated['passport_number'] ?? null;
        $pi->expiry_date = !empty($validated['expiry_date']) ? date('Y-m-d', strtotime($validated['expiry_date'])) : null;
        $pi->issuing_country = $validated['issuing_country'] ?? null;
        $pi->purpose_of_travel = $validated['purpose_of_travel'] ?? null;
        $pi->seat_preference = $validated['seat_preference'] ?? null;
        $pi->meal_preference = $validated['meal_preference'] ?? null;
        $pi->preferred_cabin = $validated['preferred_cabin'] ?? null;
        $pi->preferred_airline = $validated['preferred_airline'] ?? null;
        $pi->save();

        session()->flash('status', 'User updated successfully.');
        return redirect()->route('users.index', ['companyId' => $this->company_id]);
    }

    public function deleteFamilyMember(int $id): void
    {
        $member = \App\Models\UserFamilyInfo::query()
            ->where('user_id', $this->userId)
            ->findOrFail($id);

        $member->delete();

        session()->flash('status', 'Family traveler removed.');
    }

    public function render()
    {
        $familyMembers = \App\Models\UserFamilyInfo::query()
            ->where('user_id', $this->userId)
            ->orderByDesc('updated_at')
            ->orderBy('id')
            ->get();

        return view('livewire.user.edit', [
            'familyMembers' => $familyMembers,
        ]);
    }
}
