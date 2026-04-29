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
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Dashboard extends Component
{
    public function getStats(): array
    {
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
            $counts[] = ActivityLog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $months, 'data' => $counts];
    }

    /**
     * Breakdown of entities for bar chart.
     */
    public function getEntityBreakdown(): array
    {
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

    /**
     * Recent activity logs for the activity feed.
     */
    public function getRecentActivity(): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.index', [
            'stats'            => $this->getStats(),
            'monthlyActivity'  => $this->getMonthlyActivity(),
            'entityBreakdown'  => $this->getEntityBreakdown(),
            'recentActivity'   => $this->getRecentActivity(),
        ]);
    }
}
