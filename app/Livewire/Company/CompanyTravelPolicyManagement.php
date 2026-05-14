<?php

namespace App\Livewire\Company;

use App\Models\TravelPolicy;
use App\Models\Company;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyTravelPolicyManagement extends Component
{
    use WithPagination;

    #[Reactive]
    public $companyId = '';

    #[Reactive]
    public $policyType = '';

    #[Reactive]
    public $search = '';

    public $returnUrl = '';

    public function mount()
    {
        $this->returnUrl = request()->getRequestUri();
    }

    public function delete($id)
    {
        TravelPolicy::findOrFail($id)->delete();
        session()->flash('status', 'Travel policy deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $policy = TravelPolicy::findOrFail($id);
        $policy->update(['is_active' => !$policy->is_active]);
        session()->flash('status', 'Policy status updated successfully.');
    }

    public function render()
    {
        $query = TravelPolicy::query()->with(['company', 'grades']);

        if ($this->companyId) {
            $query->where('company_id', $this->companyId);
        }

        if ($this->policyType) {
            $query->where('policy_type', $this->policyType);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.company.company-travel-policy-management', [
            'policies' => $query->latest()->paginate(10),
            'companies' => Company::orderBy('name')->get(),
            'policyTypes' => ['flight', 'hotel', 'car', 'concierge', 'general'],
        ]);
    }
}
