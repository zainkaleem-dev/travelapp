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
        // ── Travel Modules ───────────────────────────────────────────
        'flights-module' => [
            'label'       => 'Flights',
            'icon'        => 'plane',
            'description' => 'Flight search, booking, and management capabilities.',
            'type'        => 'toggle',
        ],
        'hotels-module' => [
            'label'       => 'Hotels',
            'icon'        => 'building',
            'description' => 'Hotel reservations and accommodation services.',
            'type'        => 'toggle',
        ],
        'cars-module' => [
            'label'       => 'Cars',
            'icon'        => 'car',
            'description' => 'Car rental management and transportation booking.',
            'type'        => 'toggle',
        ],
        'concierge-module' => [
            'label'       => 'Concierge',
            'icon'        => 'bell',
            'description' => 'Premium concierge and VIP travel services.',
            'type'        => 'toggle',
        ],
        'travel-hub-module' => [
            'label'       => 'Travel Hub',
            'icon'        => 'hub',
            'description' => 'Unified travel dashboard for document syncing.',
            'type'        => 'toggle',
        ],

        // ── Admin Modules ────────────────────────────────────────────
        'companies-module' => [
            'label'       => 'Companies',
            'icon'        => 'office-building',
            'description' => 'Company creation and management access.',
            'type'        => 'toggle',
        ],
        'branches-module' => [
            'label'       => 'Branches',
            'icon'        => 'branch',
            'description' => 'Branch management within each company.',
            'type'        => 'toggle',
        ],
        'users-module' => [
            'label'       => 'Users',
            'icon'        => 'users',
            'description' => 'User creation, assignment, and management.',
            'type'        => 'toggle',
        ],
        'roles-permissions-module' => [
            'label'       => 'Roles & Permissions',
            'icon'        => 'shield',
            'description' => 'Role-based access control and permission assignment.',
            'type'        => 'toggle',
        ],
        'feature-management-module' => [
            'label'       => 'Feature Management',
            'icon'        => 'cog',
            'description' => 'Control which features are enabled per company.',
            'type'        => 'toggle',
        ],

        // ── Quantity Limits ──────────────────────────────────────────
        'companies-quantity' => [
            'label'       => 'Max Companies',
            'icon'        => 'hash',
            'description' => 'Maximum number of companies allowed in the system.',
            'type'        => 'quantity',
        ],
        'branches-quantity' => [
            'label'       => 'Max Branches',
            'icon'        => 'hash',
            'description' => 'Maximum number of branches allowed per company.',
            'type'        => 'quantity',
        ],
        'users-quantity' => [
            'label'       => 'Max Users',
            'icon'        => 'hash',
            'description' => 'Maximum number of users allowed per company.',
            'type'        => 'quantity',
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

    public function updateQuantity(int $companyId, string $featureKey, int $value): void
    {
        $company = Company::findOrFail($companyId);
        Feature::for($company)->activate($featureKey, max(0, $value));
        session()->flash('status', "Quantity limit updated for {$company->name}.");
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

        // Calculate stats — only count toggle features for sidebar
        $toggleKeys = array_keys(array_filter($this->definedFeatures, fn ($f) => ($f['type'] ?? 'toggle') === 'toggle'));
        $companyStats = [];
        foreach ($sidebarCompanies as $company) {
            $activeCount = 0;
            foreach ($toggleKeys as $f) {
                if (Feature::for($company)->active($f)) {
                    $activeCount++;
                }
            }
            $companyStats[$company->id] = [
                'active'       => $activeCount,
                'total'        => count($toggleKeys),
                'is_any_active'=> $activeCount > 0,
            ];
        }

        // Selected company details
        $activeCompany   = $this->selectedCompany;
        $activeFeatures  = [];
        $activePercentage = 0;
        $onCount  = 0;
        $offCount = 0;

        if ($activeCompany) {
            foreach ($this->definedFeatures as $f => $def) {
                if (($def['type'] ?? 'toggle') === 'quantity') {
                    // Store the actual numeric value, not a boolean
                    $activeFeatures[$f] = Feature::for($activeCompany)->value($f);
                } else {
                    $status = Feature::for($activeCompany)->active($f);
                    $activeFeatures[$f] = $status;
                    if ($status) $onCount++;
                    else         $offCount++;
                }
            }
            $activePercentage = count($toggleKeys) > 0
                ? round(($onCount / count($toggleKeys)) * 100)
                : 0;
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
