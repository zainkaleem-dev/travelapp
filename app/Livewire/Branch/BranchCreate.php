<?php

namespace App\Livewire\Branch;

use App\Models\Company;
use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class BranchCreate extends Component
{
    // Identity
    public string $name = '';
    public string $code = '';
    public string $slug = '';
    public ?int $company_id = null;
    public string $routePrefix = 'admin';
    public bool $is_main = false;

    public string $status = 'active';

    public function mount(\App\Support\TenantContext $tenantContext)
    {


        $this->company_id = $tenantContext->companyId();

        // Enforcement: Only users with 'Create Branch' permission (or Super Admin bypass) can access this page
    }

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
            'code' => ['required', 'string', 'max:50', 'unique:branches,code'],
            'slug' => ['required', 'string', 'max:255', 'unique:branches,slug', 'alpha_dash'],
            'company_id' => ['required', 'exists:companies,id'],
            'is_main' => [
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value && $this->company_id) {
                        $exists = Branch::query()
                            ->where('company_id', $this->company_id)
                            ->where('is_main', true)
                            ->exists();

                        if ($exists) {
                            $fail('This company already has a main branch. Only one main branch is allowed.');
                        }
                    }
                }
            ],
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

        Branch::query()->create($validated);

        session()->flash('status', 'Branch created successfully.');
        return redirect()->route('branches.index');
    }

    public function render(\App\Support\TenantContext $tenantContext)
    {
        $companyId = $tenantContext->companyId();

        return view('livewire.branch.create', [
            'companies' => Company::query()
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('id', $companyId);
                })
                ->orderBy('name')
                ->get(),
        ]);
    }
}
