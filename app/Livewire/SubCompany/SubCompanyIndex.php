<?php
 
namespace App\Livewire\SubCompany;
 
use App\Models\SubCompany;
use App\Support\TenantContext;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
#[Layout('layouts.flight')]
class SubCompanyIndex extends Component
{
    public SubCompany $subCompany;
 
    public bool $editModalOpen = false;
 
    public string $name = '';
    public string $code = '';
    public string $country = '';
    public string $city = '';
    public ?string $address = null;
    public ?string $phone = null;
    public string $email = '';

    public function mount(TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $subCompanyId = (int) (auth()->user()?->sub_company_id ?? 0);
        abort_unless($subCompanyId > 0, 403);
 
        $this->subCompany = SubCompany::query()
            ->where('company_id', $companyId)
            ->findOrFail($subCompanyId);
 
        $this->fillFormFromSubCompany();
    }
 
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }
 
    public function openEdit(): void
    {
        $this->fillFormFromSubCompany();
        $this->resetErrorBag();
        $this->editModalOpen = true;
    }
 
    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->resetErrorBag();
    }
 
    public function updateSubCompany(): void
    {
        $validated = $this->validate();
 
        $this->subCompany->forceFill([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
        ])->save();
 
        $this->subCompany->refresh();
        session()->flash('status', 'Sub company updated successfully.');
        $this->closeEdit();
    }
 
    private function fillFormFromSubCompany(): void
    {
        $this->name = (string) $this->subCompany->name;
        $this->code = (string) $this->subCompany->code;
        $this->country = (string) $this->subCompany->country;
        $this->city = (string) $this->subCompany->city;
        $this->address = $this->subCompany->address;
        $this->phone = $this->subCompany->phone;
        $this->email = (string) $this->subCompany->email;
    }
 
    public function render()
    {
        return view('livewire.subcompany.index', [
            'subCompany' => $this->subCompany,
        ]);
    }
}
