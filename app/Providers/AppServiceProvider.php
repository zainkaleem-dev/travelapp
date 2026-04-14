<?php

namespace App\Providers;

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

        // Implicitly grant "super_admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        // Resolve Pennant scope to the current company
        Feature::resolveScopeUsing(fn () => app(TenantContext::class)->currentCompany(request()));

        // Define System Features
        Feature::define('hotels-module', fn () => true); // Default to true, overridden by DB
        Feature::define('cars-module', fn () => true);
        Feature::define('concierge-module', fn () => true);
        Feature::define('travel-hub-module', fn () => true);
        Feature::define('flights-module', fn () => true);
    }
}
