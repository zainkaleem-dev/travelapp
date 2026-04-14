<?php
 
namespace App\Livewire\User;
 
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
 
    public function save(TenantContext $tenantContext)
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        
        if ($companyId <= 0) {
            $this->addError('email', 'Please select a company from the switcher first.');
            return;
        }
 
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
 
        $user = User::query()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
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
