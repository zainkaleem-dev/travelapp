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
 
    public function mount(int $id, TenantContext $tenantContext): void
    {
        $this->userId = $id;
        $this->user = User::query()
            ->withoutRole('super_admin')
            ->findOrFail($id);
 
        $this->first_name = (string) ($this->user->first_name ?? '');
        $this->middle_name = (string) ($this->user->middle_name ?? '');
        $this->last_name = (string) ($this->user->last_name ?? '');
        $this->email = (string) $this->user->email;
        
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('super_admin');
 
        if ($isSuperAdmin) {
            $this->companies = Company::orderBy('name')->get();
            $this->company_id = $this->user->company_id;
        } else {
            $this->company_id = $tenantContext->companyId();
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
 
    public function save(TenantContext $tenantContext)
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'company_id' => [auth()->user()->hasRole('super_admin') ? 'required' : 'nullable', 'exists:companies,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

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
        return redirect()->route('superadmin.users');
    }
 
    public function render()
    {
        return view('livewire.user.edit');
    }
}
