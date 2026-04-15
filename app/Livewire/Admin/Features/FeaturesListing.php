<?php

namespace App\Livewire\Admin\Features;

use App\Models\Company;
use App\Services\PaginationService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Laravel\Pennant\Feature;
use Illuminate\Support\Collection;

#[Layout('layouts.flight')]
class FeaturesListing extends Component
{
    public string $search = '';
    public int $selectedCompanyId = 0;

    /** @var array<string, array{label: string, icon: string, description: string}> */
    public array $definedFeatures = [
        'flights-module' => [
            'label' => 'Flights',
            'icon' => 'plane',
            'description' => 'Flight search, booking, and management capabilities.'
        ],
        'hotels-module' => [
            'label' => 'Hotels',
            'icon' => 'building',
            'description' => 'Hotel reservations and accommodation services.'
        ],
        'cars-module' => [
            'label' => 'Cars',
            'icon' => 'car',
            'description' => 'Car rental management and transportation booking.'
        ],
        'concierge-module' => [
            'label' => 'Concierge',
            'icon' => 'bell',
            'description' => 'Premium concierge and VIP travel services.'
        ],
        'travel-hub-module' => [
            'label' => 'Travel Hub',
            'icon' => 'hub',
            'description' => 'Unified travel dashboard for document syncing.'
        ],
    ];

    public function mount($company = null): void
    {
        if ($company instanceof Company && $company->exists) {
            $this->selectedCompanyId = $company->id;
        } elseif (is_numeric($company)) {
            $this->selectedCompanyId = (int) $company;
        }
    }

    protected function selectFirstCompany(): void
    {
        $first = Company::orderBy('name')->first();
        if ($first) {
            $this->selectedCompanyId = $first->id;
        }
    }

    public function selectCompany(int $id): void
    {
        $this->selectedCompanyId = $id;
    }

    public function toggleFeature(int $companyId, string $featureKey): void
    {
        $company = Company::findOrFail($companyId);

        if (Feature::for($company)->active($featureKey)) {
            Feature::for($company)->deactivate($featureKey);
        } else {
            Feature::for($company)->activate($featureKey);
        }

        session()->flash('status', "Feature status updated for {$company->name}.");
    }

    public function getSelectedCompanyProperty()
    {
        return $this->selectedCompanyId ? Company::find($this->selectedCompanyId) : null;
    }

    public function render()
    {
        // Fetch companies for the sidebar (without standard pagination since it's a scrollable list)
        $sidebarCompanies = Company::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();

        // Calculate stats for all sidebar companies
        $companyStats = [];
        foreach ($sidebarCompanies as $company) {
            $activeCount = 0;
            foreach (array_keys($this->definedFeatures) as $f) {
                if (Feature::for($company)->active($f)) {
                    $activeCount++;
                }
            }
            $companyStats[$company->id] = [
                'active' => $activeCount,
                'total' => count($this->definedFeatures),
                'is_any_active' => $activeCount > 0
            ];
        }

        // Selected company details
        $activeCompany = $this->selectedCompany;
        $activeFeatures = [];
        $activePercentage = 0;
        $onCount = 0;
        $offCount = 0;

        if ($activeCompany) {
            foreach (array_keys($this->definedFeatures) as $f) {
                $status = Feature::for($activeCompany)->active($f);
                $activeFeatures[$f] = $status;
                if ($status)
                    $onCount++;
                else
                    $offCount++;
            }
            $activePercentage = round(($onCount / count($this->definedFeatures)) * 100);
        }

        return view('livewire.admin.features.features-listing', [
            'sidebarCompanies' => $sidebarCompanies,
            'companyStats' => $companyStats,
            'activeCompany' => $activeCompany,
            'activeFeatures' => $activeFeatures,
            'onCount' => $onCount,
            'offCount' => $offCount,
            'activePercentage' => $activePercentage,
        ]);
    }
}
