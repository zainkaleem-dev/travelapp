<?php
 
namespace App\Livewire\User;
 
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
 
    public function mount(int $id, TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        
        $this->userId = $id;
        $this->user = User::query()
            ->where('company_id', $companyId)
            ->withoutRole('super_admin')
            ->findOrFail($id);
 
        $this->first_name = (string) ($this->user->first_name ?? '');
        $this->middle_name = (string) ($this->user->middle_name ?? '');
        $this->last_name = (string) ($this->user->last_name ?? '');
        $this->email = (string) $this->user->email;
    }
 
    public function save(TenantContext $tenantContext)
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);
 
        $this->user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
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
