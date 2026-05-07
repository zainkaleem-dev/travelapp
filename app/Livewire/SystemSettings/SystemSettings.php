<?php

namespace App\Livewire\SystemSettings;

use App\Models\SystemEndpoint;
use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class SystemSettings extends Component
{
    use WithPagination;

    public $companyId = '';
    public $policyType = '';
    public $search = '';
    public $activeTab = 'endpoints';

    protected $queryString = [
        'companyId' => ['except' => ''],
        'policyType' => ['except' => ''],
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'endpoints'],
    ];

    public function updatingPolicyType()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCompanyId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SystemEndpoint::query()
            ->with('company')
            ->join('companies', 'system_endpoints.company_id', '=', 'companies.id')
            ->select('system_endpoints.*');

        if ($this->companyId) {
            $query->where('system_endpoints.company_id', $this->companyId);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('system_endpoints.endpoint_name', 'like', '%' . $this->search . '%')
                  ->orWhere('system_endpoints.endpoint_link', 'like', '%' . $this->search . '%')
                  ->orWhere('system_endpoints.description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.system-settings.system-settings', [
            'endpoints' => $query->paginate(10),
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    public function toggleVerified($id)
    {
        $endpoint = SystemEndpoint::findOrFail($id);
        $endpoint->update(['is_verified' => !$endpoint->is_verified]);
        session()->flash('status', 'Endpoint verification status updated.');
    }
}
