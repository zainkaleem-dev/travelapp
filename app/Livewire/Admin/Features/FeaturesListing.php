<?php

namespace App\Livewire\Admin\Features;

use App\Models\Company;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Laravel\Pennant\Feature;
use Illuminate\Support\Collection;

#[Layout('layouts.flight')]
class FeaturesListing extends Component
{
    public string $search = '';
    public int $selectedCompanyId = 0;
    public bool $isCompanyRoute = false;
    public bool $isCompanyContext = false;

    /** @var array<string, array{label: string, icon: string, description: string}> */
    public array $definedFeatures = [
        // ── Travel Modules ───────────────────────────────────────────
        'flights-module' => [
            'label' => 'Flights',
            'icon' => 'plane',
            'description' => 'Flight search, booking, and management capabilities.',
            'type' => 'toggle',
        ],
        'hotels-module' => [
            'label' => 'Hotels',
            'icon' => 'building',
            'description' => 'Hotel reservations and accommodation services.',
            'type' => 'toggle',
        ],
        'cars-module' => [
            'label' => 'Cars',
            'icon' => 'car',
            'description' => 'Car rental management and transportation booking.',
            'type' => 'toggle',
        ],
        'concierge-module' => [
            'label' => 'Concierge',
            'icon' => 'bell',
            'description' => 'Premium concierge and VIP travel services.',
            'type' => 'toggle',
        ],
        'travel-hub-module' => [
            'label' => 'Travel Hub',
            'icon' => 'hub',
            'description' => 'Unified travel dashboard for document syncing.',
            'type' => 'toggle',
        ],

        // ── Admin Modules ────────────────────────────────────────────
        'companies-module' => [
            'label' => 'Organizations',
            'icon' => 'office-building',
            'description' => 'Organization creation and management access.',
            'type' => 'toggle',
        ],
        'branches-module' => [
            'label' => 'Branches',
            'icon' => 'branch',
            'description' => 'Branch management within each company.',
            'type' => 'toggle',
        ],
        'users-module' => [
            'label' => 'Users',
            'icon' => 'users',
            'description' => 'User creation, assignment, and management.',
            'type' => 'toggle',
        ],
        'roles-permissions-module' => [
            'label' => 'Roles & Permissions',
            'icon' => 'shield',
            'description' => 'Role-based access control and permission assignment.',
            'type' => 'toggle',
        ],
        'feature-management-module' => [
            'label' => 'Feature Management',
            'icon' => 'cog',
            'description' => 'Control which features are enabled per organization.',
            'type' => 'toggle',
        ],

        // ── Quantity Limits ──────────────────────────────────────────
        'companies-quantity' => [
            'label' => 'Max Organizations',
            'icon' => 'hash',
            'description' => 'Maximum number of organizations allowed in the system.',
            'type' => 'quantity',
        ],
        'branches-quantity' => [
            'label' => 'Max Branches',
            'icon' => 'hash',
            'description' => 'Maximum number of branches allowed per company.',
            'type' => 'quantity',
        ],
        'users-quantity' => [
            'label' => 'Max Users',
            'icon' => 'hash',
            'description' => 'Maximum number of users allowed per company.',
            'type' => 'quantity',
        ],
    ];

    public function mount(TenantContext $tenantContext, $company = null): void
    {
        if ($company instanceof Company && $company->exists) {
            $this->selectedCompanyId = $company->id;
        } elseif (is_numeric($company)) {
            $this->selectedCompanyId = (int) $company;
        }

        // If in company context and no company selected, default to current tenant
        $this->isCompanyContext = request()->is('company*');
        if ($this->isCompanyContext && !$this->selectedCompanyId) {
            $this->selectedCompanyId = (int) ($tenantContext->companyId() ?? 0);
        }

        $this->isCompanyRoute = request()->routeIs('companies.features');
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
        // If in company context, prevent switching away from own company
        if (request()->is('company*')) {
            return;
        }
        $this->selectedCompanyId = $id;
    }

    public function toggleFeature(int $companyId, string $featureKey, TenantContext $tenantContext): void
    {
        // Protection: if in company context, ensure we are editing our own company
        if (request()->is('company*') && $companyId != $tenantContext->companyId()) {
            return;
        }

        $company = Company::findOrFail($companyId);

        if (Feature::for($company)->active($featureKey)) {
            Feature::for($company)->deactivate($featureKey);
        } else {
            Feature::for($company)->activate($featureKey);
        }

        session()->flash('status', "Feature status updated for {$company->name}.");
    }

    public function updateQuantity(int $companyId, string $featureKey, int $value, TenantContext $tenantContext): void
    {
        // Protection: if in company context, ensure we are editing our own company
        if (request()->is('company*') && $companyId != $tenantContext->companyId()) {
            return;
        }

        $company = Company::findOrFail($companyId);
        Feature::for($company)->activate($featureKey, max(0, $value));
        session()->flash('status', "Quantity limit updated for {$company->name}.");
    }

    public function getSelectedCompanyProperty()
    {
        return $this->selectedCompanyId ? Company::find($this->selectedCompanyId) : null;
    }

    public function render(TenantContext $tenantContext)
    {
        $isCompanyContext = $this->isCompanyContext;
        
        // Fetch companies for the sidebar 
        $sidebarCompanies = Company::query()
            ->where('id', '!=', auth()->user()->company_id)
            ->when($isCompanyContext, function ($q) use ($tenantContext) {
                $q->where('id', $tenantContext->companyId());
            })
            ->when(!$isCompanyContext && $this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();

        // Calculate stats — only count toggle features for sidebar
        $toggleKeys = array_keys(array_filter($this->definedFeatures, fn($f) => ($f['type'] ?? 'toggle') === 'toggle'));
        $companyStats = [];
        foreach ($sidebarCompanies as $company) {
            $activeCount = 0;
            foreach ($toggleKeys as $f) {
                if (Feature::for($company)->active($f)) {
                    $activeCount++;
                }
            }
            $companyStats[$company->id] = [
                'active' => $activeCount,
                'total' => count($toggleKeys),
                'is_any_active' => $activeCount > 0,
            ];
        }

        // Selected company details
        $activeCompany = $this->selectedCompany;
        
        // Final protection: if company context, ensure activeCompany matches tenant
        if ($isCompanyContext && $activeCompany && $activeCompany->id != $tenantContext->companyId()) {
            $activeCompany = Company::find($tenantContext->companyId());
        }

        $activeFeatures = [];
        $activePercentage = 0;
        $onCount = 0;
        $offCount = 0;

        if ($activeCompany) {
            foreach ($this->definedFeatures as $f => $def) {
                if (($def['type'] ?? 'toggle') === 'quantity') {
                    // Store the actual numeric value, not a boolean
                    $activeFeatures[$f] = Feature::for($activeCompany)->value($f);
                } else {
                    $status = Feature::for($activeCompany)->active($f);
                    $activeFeatures[$f] = $status;
                    if ($status)
                        $onCount++;
                    else
                        $offCount++;
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
            'isCompanyContext' => $isCompanyContext,
        ]);
    }
}
