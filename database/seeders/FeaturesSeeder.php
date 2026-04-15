<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Pennant\Feature;

class FeaturesSeeder extends Seeder
{
    /**
     * Seed the default global feature flags.
     *
     * These are the system-wide defaults (no company scope).
     * Per-company overrides are managed via the Features UI.
     */
    public function run(): void
    {
        // ── Travel Module Features ──────────────────────────────────
        Feature::activate('flights-module');
        Feature::activate('hotels-module');
        Feature::activate('cars-module');
        Feature::activate('concierge-module');
        Feature::activate('travel-hub-module');

        // ── Admin Module Features ───────────────────────────────────
        Feature::activate('companies-module');       // Access to company management
        Feature::activate('branches-module');        // Access to branch management
        Feature::activate('users-module');           // Access to user management
        Feature::activate('roles-permissions-module'); // Access to roles & permissions
        Feature::activate('feature-management-module'); // Access to feature toggles

        // ── Quantity Limits (default allowances per company) ────────
        Feature::activate('companies-quantity', 10);    // Max companies allowed
        Feature::activate('branches-quantity', 20);     // Max branches per company
        Feature::activate('users-quantity', 100);       // Max users per company

        $this->command->info('✅ Features seeded with defaults.');
    }
}
