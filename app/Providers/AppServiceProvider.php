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
        $this->app->singleton(TenantContext::class, fn() => new TenantContext());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Implicitly grant "Global Super Admins" all permissions.
        // We use a raw DB query here to avoid recursion with model-level overrides.
        Gate::before(function ($user, $ability) {
            return \Illuminate\Support\Facades\Cache::remember(
                "user_{$user->id}_is_global_super_admin",
                now()->addMinutes(10),
                fn() => \Illuminate\Support\Facades\DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->where('model_has_roles.model_type', \App\Models\User::class)
                    ->where('roles.name', 'Super Admin')
                    ->whereNull('model_has_roles.company_id')
                    ->exists()
            ) ? true : null;
        });

        // Resolve Pennant scope to the current company
        Feature::resolveScopeUsing(fn() => app(TenantContext::class)->currentCompany(request()));

        // Register custom Blade directive for Feature Gating with Super Admin Bypass
        \Illuminate\Support\Facades\Blade::if('featureOrAdmin', function ($feature) {
            $user = auth()->user();
            if ($user && $user->can('Manage Global System')) {
                return true;
            }
            return Feature::active($feature);
        });

        // ── Travel Module Features ───
        Feature::define('flights-module', fn() => true);
        Feature::define('hotels-module', fn() => true);
        Feature::define('cars-module', fn() => true);
        Feature::define('concierge-module', fn() => true);
        Feature::define('travel-hub-module', fn() => true);

        // ── Admin Module Features ────
        Feature::define('companies-module', fn() => true);
        Feature::define('branches-module', fn() => true);
        Feature::define('users-module', fn() => true);
        Feature::define('roles-permissions-module', fn() => true);
        Feature::define('feature-management-module', fn() => true);

        // ── Quantity Limits ──────────
        Feature::define('companies-quantity', fn() => 10);
        Feature::define('branches-quantity', fn() => 20);
        Feature::define('users-quantity', fn() => 100);

        // ── Observers ────────────────
        Company::observe(CompanyObserver::class);
    }
}
