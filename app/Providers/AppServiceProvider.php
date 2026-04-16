<?php

namespace App\Providers;

use App\Models\Company;
use App\Observers\CompanyObserver;
use App\Support\TenantContext;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, fn () => new TenantContext());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Implicitly grant "Super Admin" role all permissions globally
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Resolve Pennant scope to the current company
        Feature::resolveScopeUsing(fn () => app(TenantContext::class)->currentCompany(request()));

        // ── Travel Module Features ───
        Feature::define('flights-module', fn () => true);
        Feature::define('hotels-module', fn () => true);
        Feature::define('cars-module', fn () => true);
        Feature::define('concierge-module', fn () => true);
        Feature::define('travel-hub-module', fn () => true);

        // ── Admin Module Features ────
        Feature::define('companies-module', fn () => true);
        Feature::define('branches-module', fn () => true);
        Feature::define('users-module', fn () => true);
        Feature::define('roles-permissions-module', fn () => true);
        Feature::define('feature-management-module', fn () => true);

        // ── Quantity Limits ──────────
        Feature::define('companies-quantity', fn () => 10);
        Feature::define('branches-quantity', fn () => 20);
        Feature::define('users-quantity', fn () => 100);

        // ── Observers ────────────────
        Company::observe(CompanyObserver::class);
    }
}
