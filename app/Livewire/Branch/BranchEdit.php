<?php

namespace App\Livewire\Branch;

use App\Models\Company;
use App\Models\Branch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class BranchEdit extends Component
{
    public int $branchId;
    public Branch $branch;
    public string $routePrefix = 'admin';

    // Identity
    public string $name = '';
    public string $code = '';
    public string $slug = '';
    public ?int $company_id = null;
    public bool $is_main = false;
    public string $status = 'active';

    // Contact
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $phone_secondary = null;
    public ?string $fax = null;
    public ?string $whatsapp = null;

    // Address
    public ?string $address_line_1 = null;
    public ?string $address_line_2 = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postal_code = null;
    public ?string $country = null;

    // GPS & Others
    public ?string $latitude = null;
    public ?string $longitude = null;
    public ?string $notes = null;

    public function mount(int $id, \App\Support\TenantContext $tenantContext): void
    {
        if (request()->is('admin*')) {
            $this->routePrefix = 'admin';
        } elseif (request()->is('company*')) {
            $this->routePrefix = 'company';
        }

        $this->branchId = $id;
        $companyId = $tenantContext->companyId();

        $this->branch = Branch::query()
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->findOrFail($id);

        $this->name = $this->branch->name;
        $this->code = $this->branch->code;
        $this->slug = $this->branch->slug;
        $this->company_id = $this->branch->company_id;
        $this->is_main = (bool) $this->branch->is_main;
        $this->status = strtolower($this->branch->status ?: 'active');

        $this->email = $this->branch->email;
        $this->phone = $this->branch->phone;
        $this->phone_secondary = $this->branch->phone_secondary;
        $this->fax = $this->branch->fax;
        $this->whatsapp = $this->branch->whatsapp;

        $this->address_line_1 = $this->branch->address_line_1;
        $this->address_line_2 = $this->branch->address_line_2;
        $this->city = $this->branch->city;
        $this->state = $this->branch->state;
        $this->postal_code = $this->branch->postal_code;
        $this->country = $this->branch->country;

        $this->latitude = $this->branch->latitude;
        $this->longitude = $this->branch->longitude;
        $this->notes = $this->branch->notes;
    }

    public function updatedName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'code' => ['required', 'string', 'max:50', Rule::unique('branches', 'code')->ignore($this->branchId)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('branches', 'slug')->ignore($this->branchId), 'alpha_dash'],
            'company_id' => ['required', 'exists:companies,id'],
            'is_main' => ['boolean'],
            'status' => ['required', 'in:active,inactive'],

            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'phone_secondary' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],

            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:255'],

            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'We need a branch email address.',
            'phone.required' => 'A primary contact number is required.',
            'address_line_1.required' => 'Please provide the physical address.',
            'city.required' => 'City is required.',
            'state.required' => 'State/Province is required.',
            'country.required' => 'Country is required.',
            'latitude.required' => 'GPS Latitude is necessary for mapping.',
            'longitude.required' => 'GPS Longitude is necessary for mapping.',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $this->branch->update($validated);

        session()->flash('status', 'Branch updated successfully.');
        return redirect()->route($this->routePrefix . '.branches.index');
    }

    public function render(\App\Support\TenantContext $tenantContext)
    {
        $companyId = $tenantContext->companyId();

        return view('livewire.branch.edit', [
            'companies' => Company::query()
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('id', $companyId);
                })
                ->orderBy('name')
                ->get(),
        ]);
    }
}
