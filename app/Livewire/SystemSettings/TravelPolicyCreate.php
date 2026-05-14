<?php

namespace App\Livewire\SystemSettings;

use App\Models\TravelPolicy;
use App\Models\Company;
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

    public function mount()
    {
        $this->companyId = $this->companyId ?: request()->query('companyId');
        $this->returnUrl = request()->query('returnUrl');
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'companyId' => 'required|exists:companies,id',
        'policyType' => 'required|in:flight,car,hotel,concierge,general',
        'description' => 'nullable|string',
        'isActive' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        TravelPolicy::create([
            'name' => $this->name,
            'description' => $this->description,
            'company_id' => $this->companyId,
            'policy_type' => $this->policyType,
            'is_active' => $this->isActive,
        ]);

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
            'policyTypes' => ['flight', 'car', 'hotel', 'concierge', 'general'],
        ]);
    }
}
