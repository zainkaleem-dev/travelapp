<?php

namespace App\Livewire\Dashboard;

use App\Models\ActivityLog;
use App\Models\Airport;
use App\Models\Branch;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\TripPurpose;
use App\Models\User;
use App\Support\TenantContext;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Dashboard extends Component
{
    public bool $isSuperAdmin = false;
    private array $manageableCompanyIds = [];

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user) return;

        // Determine Super Admin status
        $this->isSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->where('roles.name', 'Super Admin')
            ->whereNull('model_has_roles.company_id')
            ->exists();

        // Resolve manageable company IDs for non-super admins
        if (!$this->isSuperAdmin) {
            /** @var TenantContext $tenantContext */
            $tenantContext = app(TenantContext::class);
            $this->manageableCompanyIds = $tenantContext->getManageableHierarchy($user);
        }
    }

    public function getStats(): array
    {
        if ($this->isSuperAdmin) {
            // Super Admin sees everything
            return [
                'organizations' => Company::count(),
                'branches'      => Branch::count(),
                'users'         => User::count(),
                'airports'      => Airport::count(),
                'countries'     => Country::count(),
                'cities'        => City::count(),
                'trip_purposes' => TripPurpose::count(),
                'audit_logs'    => ActivityLog::count(),
            ];
        }

        // Organization Admin / other roles — scoped to their company hierarchy
        $companyIds = $this->manageableCompanyIds;

        return [
            'partners' => Company::whereIn('id', $companyIds)->count(),
            'branches' => Branch::whereIn('company_id', $companyIds)->count(),
            'users'    => User::whereIn('company_id', $companyIds)->count(),
        ];
    }

    /**
     * Monthly activity log counts for the past 6 months (line chart).
     */
    public function getMonthlyActivity(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $query = ActivityLog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);

            // Scope to manageable users for non-super admins
            if (!$this->isSuperAdmin) {
                $userIds = User::whereIn('company_id', $this->manageableCompanyIds)->pluck('id');
                $query->whereIn('user_id', $userIds);
            }

            $counts[] = $query->count();
        }

        return ['labels' => $months, 'data' => $counts];
    }

    /**
     * Breakdown of entities for bar chart.
     */
    public function getEntityBreakdown(): array
    {
        if ($this->isSuperAdmin) {
            return [
                'labels' => ['Organizations', 'Branches', 'Users', 'Airports', 'Countries', 'Cities'],
                'data'   => [
                    Company::count(),
                    Branch::count(),
                    User::count(),
                    Airport::count(),
                    Country::count(),
                    City::count(),
                ],
            ];
        }

        // Scoped for non-super admins
        $companyIds = $this->manageableCompanyIds;

        return [
            'labels' => ['Partners', 'Branches', 'Users'],
            'data'   => [
                Company::whereIn('id', $companyIds)->count(),
                Branch::whereIn('company_id', $companyIds)->count(),
                User::whereIn('company_id', $companyIds)->count(),
            ],
        ];
    }

    /**
     * Recent activity logs for the activity feed.
     */
    public function getRecentActivity(): \Illuminate\Database\Eloquent\Collection
    {
        $query = ActivityLog::with('user')->latest();

        if (!$this->isSuperAdmin) {
            $userIds = User::whereIn('company_id', $this->manageableCompanyIds)->pluck('id');
            $query->whereIn('user_id', $userIds);
        }

        return $query->take(5)->get();
    }

    public function render()
    {
        $data = [
            'stats'           => $this->getStats(),
            'isSuperAdmin'    => $this->isSuperAdmin,
            'entityBreakdown' => $this->getEntityBreakdown(),
            'recentActivity'  => $this->getRecentActivity(),
        ];

        if ($this->isSuperAdmin) {
            $data['monthlyActivity'] = $this->getMonthlyActivity();
        } else {
            $data['monthlyActivity'] = ['labels' => [], 'data' => []];
        }

        return view('livewire.dashboard.index', $data);
    }
}
