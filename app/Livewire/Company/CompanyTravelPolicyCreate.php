<?php

namespace App\Livewire\Company;

use App\Models\TravelPolicy;
use App\Models\Grade;
use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyTravelPolicyCreate extends Component
{
    public $name;
    public $description;
    public $companyId;
    public $policyType = 'general';
    public $isActive = true;
    public $selectedGrades = [];
    public $company;

    public function mount($id)
    {
        $this->companyId = $id;
        $this->company = Company::findOrFail($id);
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

        $policy = TravelPolicy::create([
            'name' => $this->name,
            'description' => $this->description,
            'company_id' => $this->companyId,
            'policy_type' => $this->policyType,
            'is_active' => $this->isActive,
        ]);

        if (!empty($this->selectedGrades)) {
            $policy->grades()->syncWithPivotValues($this->selectedGrades, ['company_id' => $this->companyId]);
        }

        session()->flash('status', 'Travel policy created successfully.');
        
        return redirect()->route('companies.travel-policy', ['id' => $this->companyId]);
    }

    public function render()
    {
        return view('livewire.company.company-travel-policy-create', [
            'grades' => Grade::where('company_id', $this->companyId)->orderBy('name')->get(),
            'policyTypes' => ['flight', 'car', 'hotel', 'concierge', 'general'],
        ]);
    }
}
