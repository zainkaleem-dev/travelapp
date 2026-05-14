<?php

namespace App\Livewire\SystemSettings;

use App\Models\TravelPolicy;
use App\Models\Company;
use App\Models\Grade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TravelPolicyCreate extends Component
{
    public $name;
    public $description;
    public $companyId;
    public $policyType = 'general';
    public $isActive = true;
    public $returnUrl;
    public $selectedGrades = [];

    public function mount($companyId = null)
    {
        $this->companyId = $companyId ?: request()->query('companyId');
        $this->returnUrl = request()->query('returnUrl');
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'companyId' => 'required|exists:companies,id',
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

        if ($this->returnUrl) {
            return redirect($this->returnUrl);
        }

        return redirect()->route('admin.system-settings', ['activeTab' => 'travel-policy']);
    }

    public function render()
    {
        return view('livewire.system-settings.travel-policy-create', [
            'companies' => Company::orderBy('name')->get(),
            'grades' => $this->companyId ? Grade::where('company_id', $this->companyId)->orderBy('name')->get() : collect(),
            'policyTypes' => ['flight', 'car', 'hotel', 'concierge', 'general'],
        ]);
    }
}
