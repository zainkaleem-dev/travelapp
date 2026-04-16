<?php
 
namespace App\Livewire\User;
 
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Support\TenantContext;
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
 
    public function mount(TenantContext $tenantContext)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        if ($isSuperAdmin) {
            $this->companies = Company::orderBy('name')->get();
        } else {
            $this->company_id = $tenantContext->companyId();
            $this->updatedCompanyId();
        }
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
 
    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'company_id' => [auth()->user()->hasRole('Super Admin') ? 'required' : 'nullable', 'exists:companies,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        $user = User::query()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $this->company_id,
            'branch_id' => $validated['branch_id'],
            'email_verified_at' => now(),
        ]);

        session()->flash('status', 'User created successfully.');
        return redirect()->route('superadmin.users');
    }
 
    public function render()
    {
        return view('livewire.user.create');
    }
}
