<?php

namespace App\Livewire\Company;

use App\Models\TravelPolicy;
use App\Models\Grade;
use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyTravelPolicyEdit extends Component
{
    public $policyId;
    public $name;
    public $description;
    public $companyId;
    public $policyType;
    public $isActive;
    public $selectedGrades = [];
    public $company;

    public function mount($id, $policy)
    {
        $this->companyId = $id;
        $this->company = Company::findOrFail($id);
        
        $policyModel = TravelPolicy::where('company_id', $id)->findOrFail($policy);
        $this->policyId = $policyModel->id;
        $this->name = $policyModel->name;
        $this->description = $policyModel->description;
        $this->policyType = $policyModel->policy_type;
        $this->isActive = $policyModel->is_active;
        $this->selectedGrades = $policyModel->grades->pluck('id')->toArray();
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'policyType' => 'required|in:flight,car,hotel,concierge,general',
        'description' => 'nullable|string',
        'isActive' => 'boolean',
        'selectedGrades' => 'nullable|array',
        'selectedGrades.*' => 'exists:grades,id',
    ];

    public function save()
    {
        $this->validate();

        $policy = TravelPolicy::where('company_id', $this->companyId)->findOrFail($this->policyId);
        
        $policy->update([
            'name' => $this->name,
            'description' => $this->description,
            'policy_type' => $this->policyType,
            'is_active' => $this->isActive,
        ]);

        $policy->grades()->syncWithPivotValues($this->selectedGrades, ['company_id' => $this->companyId]);

        session()->flash('status', 'Travel policy updated successfully.');
        
        return redirect()->route('companies.travel-policy', ['id' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.company.company-travel-policy-edit', [
            'grades' => Grade::where('company_id', $this->companyId)->orderBy('name')->get(),
            'policyTypes' => ['flight', 'car', 'hotel', 'concierge', 'general'],
        ]);
    }
}
