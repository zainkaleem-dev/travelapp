<?php

namespace App\Livewire\Company;

use App\Models\BillingDetail;
use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyEditBillingDetails extends Component
{
    public int $companyId;
    public Company $company;

    // Entity Information
    public ?string $entity_name = null;
    public ?string $display_name = null;
    public ?string $registration_number = null;
    public ?string $tax_number = null;

    // Currency
    public ?string $currency = null;
    public ?string $currency_code = null;

    // Location
    public ?string $country = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postal_code = null;
    public ?string $address_line_1 = null;
    public ?string $address_line_2 = null;

    // Contact Person
    public ?string $first_name = null;
    public ?string $middle_name = null;
    public ?string $last_name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $fax = null;

    // Banking
    public ?string $bank_name = null;
    public ?string $bank_account_number = null;
    public ?string $bank_iban = null;
    public ?string $bank_swift = null;

    // Notes
    public ?string $notes = null;

    public function mount(int $id): void
    {
        $this->companyId = $id;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to edit this organization (Access denied).');
        }

        // Load existing billing details if any
        $billing = $this->company->billingDetail;
        if ($billing) {
            $this->entity_name = $billing->entity_name;
            $this->display_name = $billing->display_name;
            $this->registration_number = $billing->registration_number;
            $this->tax_number = $billing->tax_number;
            $this->currency = $billing->currency;
            $this->currency_code = $billing->currency_code;
            $this->country = $billing->country;
            $this->city = $billing->city;
            $this->state = $billing->state;
            $this->postal_code = $billing->postal_code;
            $this->address_line_1 = $billing->address_line_1;
            $this->address_line_2 = $billing->address_line_2;
            $this->first_name = $billing->first_name;
            $this->middle_name = $billing->middle_name;
            $this->last_name = $billing->last_name;
            $this->email = $billing->email;
            $this->phone = $billing->phone;
            $this->fax = $billing->fax;
            $this->bank_name = $billing->bank_name;
            $this->bank_account_number = $billing->bank_account_number;
            $this->bank_iban = $billing->bank_iban;
            $this->bank_swift = $billing->bank_swift;
            $this->notes = $billing->notes;
        }

        // Pre-fill registration number from company info if billing doesn't have one yet
        if (empty($this->registration_number)) {
            $this->registration_number = $this->company->registration_number;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'entity_name'         => 'nullable|string',
            'display_name'        => 'nullable|string',
            'registration_number' => 'nullable|string',
            'tax_number'          => 'nullable|string',
            'currency'            => 'nullable|string',
            'currency_code'       => 'nullable|string',
            'country'             => 'nullable|string',
            'city'                => 'nullable|string',
            'state'               => 'nullable|string',
            'postal_code'         => 'nullable|string',
            'address_line_1'      => 'nullable|string',
            'address_line_2'      => 'nullable|string',
            'first_name'          => 'nullable|string',
            'middle_name'         => 'nullable|string',
            'last_name'           => 'nullable|string',
            'email'               => 'nullable|email',
            'phone'               => 'nullable|string',
            'fax'                 => 'nullable|string',
            'bank_name'           => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_iban'           => 'nullable|string',
            'bank_swift'          => 'nullable|string',
            'notes'               => 'nullable|string',
        ]);

        $billing = BillingDetail::firstOrNew(['company_id' => $this->company->id]);
        $billing->fill($validated);
        $billing->save();

        session()->flash('status', 'Billing details updated successfully.');
        return $this->redirect(route('companies.index'));
    }

    public function render()
    {
        return view('livewire.company.edit-billing-details');
    }
}
