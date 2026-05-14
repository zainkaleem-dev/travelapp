<?php

namespace App\Livewire\SystemSettings;

use App\Models\TravelPolicy;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TravelPolicyView extends Component
{
    public $policy;

    public function mount($companyId, $id)
    {
        $this->policy = TravelPolicy::with('company')->findOrFail($id);
    }

    public function toggleStatus()
    {
        $this->policy->update(['is_active' => !$this->policy->is_active]);
        $this->policy->refresh();
        session()->flash('status', 'Policy status updated successfully.');
    }

    public function delete()
    {
        $this->policy->delete();
        session()->flash('status', 'Travel policy deleted successfully.');
        return redirect()->route('admin.system-settings', ['activeTab' => 'travel-policy']);
    }

    public function render()
    {
        return view('livewire.system-settings.travel-policy-view');
    }
}
