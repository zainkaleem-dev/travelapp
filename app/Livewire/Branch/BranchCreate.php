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

    public function updatedName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:branches,code'],
            'slug' => ['required', 'string', 'max:255', 'unique:branches,slug'],
            'company_id' => ['required', 'exists:companies,id'],
            'is_main' => ['boolean'],
            'status' => ['required', 'in:active,inactive'],
            
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_secondary' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],

            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        Branch::query()->create($validated);

        session()->flash('status', 'Branch created successfully.');
        return redirect()->route('superadmin.branches');
    }

    public function render()
    {
        return view('livewire.branch.create', [
            'companies' => Company::query()->orderBy('name')->get(),
        ]);
    }
}
