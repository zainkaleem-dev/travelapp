<?php

namespace App\Livewire\SystemSettings;

use App\Models\TravelPolicy;
use App\Models\Company;
use App\Models\Grade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TravelPolicyEdit extends Component
{
    public $policyId;
    public $name;
    public $description;
    public $companyId;
    public $policyType;
    public $isActive;
    public $returnUrl;
    public $selectedGrades = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'companyId' => 'required|exists:companies,id',
        'policyType' => 'required|in:flight,car,hotel,concierge,general',
        'description' => 'nullable|string',
        'isActive' => 'boolean',
        'selectedGrades' => 'nullable|array',
        'selectedGrades.*' => 'exists:grades,id',
    ];

    public function mount($companyId, $id)
    {
        $policy = TravelPolicy::findOrFail($id);
        $this->policyId = $policy->id;
        $this->name = $policy->name;
        $this->description = $policy->description;
        $this->companyId = $policy->company_id;
        $this->policyType = $policy->policy_type;
        $this->isActive = $policy->is_active;
        $this->selectedGrades = $policy->grades->pluck('id')->toArray();
        $this->returnUrl = request()->query('returnUrl');
    }

    public function save()
    {
        $this->validate();

        $policy = TravelPolicy::findOrFail($this->policyId);
        $policy->update([
            'name' => $this->name,
            'description' => $this->description,
            'company_id' => $this->companyId,
            'policy_type' => $this->policyType,
            'is_active' => $this->isActive,
        ]);

        $policy->grades()->syncWithPivotValues($this->selectedGrades, ['company_id' => $this->companyId]);

        session()->flash('status', 'Travel policy updated successfully.');

        if ($this->returnUrl) {
            return redirect($this->returnUrl);
        }

        return redirect()->route('admin.system-settings', ['activeTab' => 'travel-policy']);
    }

    public function render()
    {
        return view('livewire.system-settings.travel-policy-edit', [
            'companies' => Company::orderBy('name')->get(),
            'grades' => $this->companyId ? Grade::where('company_id', $this->companyId)->orderBy('name')->get() : collect(),
            'policyTypes' => ['flight', 'car', 'hotel', 'concierge', 'general'],
        ]);
    }
}
