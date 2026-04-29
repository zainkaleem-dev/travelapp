<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Flightsearch\FlightSearch;
use App\Livewire\Flightslist\FlightList;
use App\Livewire\Passengerdetail\PassengerDetail;
use App\Livewire\Additionalservices\AdditionalServices;
use App\Livewire\Chooseseat\ChooseSeat;
use App\Livewire\Flightconfirmation\FlightConfirmation;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\SignUp;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\PasswordSetup;
use App\Livewire\Profile\Profile;
use App\Livewire\Settings\Setting;
use App\Livewire\Hotel\Hotel;
use App\Livewire\Car\Car;
use App\Livewire\Concierge\Concierge;
use App\Livewire\Profile\Family\FamilyCreate;
use App\Livewire\Profile\Family\FamilyEdit;
use App\Livewire\Corporate\CorporateSettings;
use App\Livewire\Tmc\TmcSettings;
use App\Livewire\Admin\SuperAdminSettings;
use App\Livewire\Company\CompanyCreate;
use App\Livewire\Company\CompanyEdit;
use App\Livewire\Company\CompanyShow;
use App\Livewire\Company\CompanyAttachments;
use App\Livewire\Company\CompanyBranches;
use App\Livewire\Company\CompanyUserRoles;
use App\Livewire\Company\CompanyBillingEntity;
use App\Livewire\User\UserListing;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserEdit;
use App\Livewire\Travelhub\TravelHub;
use App\Livewire\Company\CompanyListing;
use App\Livewire\Branch\BranchListing;
use App\Livewire\Branch\BranchCreate;
use App\Livewire\Branch\BranchEdit;
use App\Livewire\Admin\Features\FeaturesListing;
use App\Livewire\AuditLog\AuditLogs;
use App\Livewire\AuditLog\AuditLogView;
use App\Livewire\TripPurpose\TripPurpose;
use App\Livewire\TripPurpose\TripPurposeEdit;
use App\Livewire\TripPurpose\TripPurposeView;
use App\Livewire\IntegrationApi\IntegrationsApi;
use App\Livewire\Roles\RolesPermissions;
use App\Livewire\CountriesAndCities\CountriesAndCities;
use App\Livewire\Airports\Airports;

Route::get('/lang/{locale}', function (Request $request, string $locale) {
    $locale = strtolower($locale);

    abort_unless(in_array($locale, ['en', 'ar'], true), 404);

    $request->session()->put('locale', $locale);

    return redirect()->back();
})->name('lang.switch');

Route::get('/currency/{code}', function (Request $request, string $code) {
    $code = strtoupper($code);

    abort_unless(preg_match('/^[A-Z]{3}$/', $code) === 1, 404);

    $request->session()->put('currency', $code);

    return redirect()->back();
})->name('currency.switch');

Route::get('/trip-type/{type}', function (Request $request, string $type) {
    $allowed = array_keys(\App\Models\UserSetting::tripTypeOptions());

    abort_unless(in_array($type, $allowed, true), 404);

    $request->session()->put('trip_type', $type);

    if (auth()->check()) {
        \App\Models\UserSetting::query()->updateOrCreate(
            [],
            ['trip_type' => $type]
        );
    }

    return redirect()->back();
})->name('trip.type.switch');
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Root URL
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if (!$user->has_set_password && !$user->hasRole('Super Admin')) {
            return redirect()->route('password.setup');
        }
        // 1. Global Super Admin landing
        if ($user->can('Manage Global System')) {
            return redirect()->route('companies.index');
        }

        // 2. Tenant Admin landing (Organization/Company Admins)
        if ($user->can('View Users')) {
            return redirect()->route('users.index');
        }

        if ($user->can('View Branch')) {
            return redirect()->route('branches.index');
        }

        if ($user->can('View Company')) {
            return redirect()->route('companies.index');
        }
        if ($user->can('Manage Roles And Permissions')) {
            return redirect()->route('roles.index');
        }
        if ($user->can('Manage Features')) {
            return redirect()->route('features.index');
        }

        // 3. Agent/User landing
        return redirect()->route('flights.search');
    }
    return redirect()->route('login');
})->name('root');


// Login page (Livewire full-page component)
Route::get('/login', Login::class)
    ->middleware('guest')
    ->name('login');

// Signup page (Livewire full-page component)
Route::get('/signup', SignUp::class)
    ->middleware('guest')
    ->name('signup');

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Backward compatibility: old register URL -> signup
Route::redirect('/register', '/signup')->name('register');
Route::get('/forgot-password', ForgotPassword::class)
    ->middleware('guest')
    ->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)
    ->middleware('guest')
    ->name('password.reset');
Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (!$request->hasValidSignature()) {
        abort(403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('login')->with('success', 'Email verified successfully. Please login.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
Route::get('/privacy', fn() => 'Privacy policy')->name('privacy');
Route::get('/terms', fn() => 'Terms of service')->name('terms');

// Social auth (requires Laravel Socialite)
Route::get('/auth/google', fn() => 'Google OAuth redirect')->name('auth.google');
Route::get('/auth/facebook', fn() => 'Facebook OAuth redirect')->name('auth.facebook');


Route::middleware(['auth'])->group(function () {
    Route::get('/password-setup', PasswordSetup::class)->name('password.setup');
});

Route::middleware(['auth', 'password.set'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/profile/family/create', FamilyCreate::class)->name('family.create');
    Route::get('/profile/family/{id}/edit', FamilyEdit::class)->name('family.edit');

    Route::get('/settings', Setting::class)->name('settings');

    Route::get('/corporate-settings', CorporateSettings::class)->name('corporate.settings');
    Route::get('/tmc-settings', TmcSettings::class)->name('tmc.settings');


    Route::middleware(['superadmin'])->group(function () {
        Route::get('/companies', CompanyListing::class)->name('companies.index')->middleware('can:View Company');
        Route::get('/companies/{company}/context', function (\App\Models\Company $company) {
            $user = auth()->user();
            abort_unless($user, 401);

            /** @var \App\Support\TenantContext $tenantContext */
            $tenantContext = app(\App\Support\TenantContext::class);
            $manageableIds = $tenantContext->getManageableHierarchy($user);
            abort_unless(in_array($company->id, $manageableIds, true), 403);

            session(['active_company_id' => $company->id]);

            return redirect()->route('companies.show', $company->id);
        })->name('companies.context');

        Route::get('/companies/{company}/login-as-admin', function (\App\Models\Company $company) {
            $user = auth()->user();
            abort_unless($user, 401);

            /** @var \App\Support\TenantContext $tenantContext */
            $tenantContext = app(\App\Support\TenantContext::class);
            $manageableIds = $tenantContext->getManageableHierarchy($user);
            abort_unless(in_array($company->id, $manageableIds, true), 403);

            // Prefer Organization Admin for this company, fallback to Company Admin.
            $targetUserId = \Illuminate\Support\Facades\DB::table('users')
                ->join('model_has_roles', function ($join) use ($company) {
                    $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.model_type', '=', \App\Models\User::class)
                        ->where('model_has_roles.company_id', '=', $company->id);
                })
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('users.company_id', $company->id)
                ->whereIn('roles.name', ['Organization Admin', 'Company Admin'])
                ->orderByRaw("FIELD(roles.name, 'Organization Admin', 'Company Admin')")
                ->select('users.id')
                ->value('users.id');

            if (!$targetUserId) {
                return redirect()->back()->with('error', 'No Organization Admin user found for this organization.');
            }

            // Set context for subsequent feature/permission checks while impersonating.
            session(['active_company_id' => $company->id]);

            return redirect()->route('impersonate.take', (int) $targetUserId);
        })->name('companies.login_as_admin');
        Route::get('/branches', BranchListing::class)->name('branches.index')->middleware('can:View Branch');
        Route::get('/branches/create', BranchCreate::class)->name('branches.create')->middleware('can:Create Branch');
        Route::get('/branches/{id}/edit', BranchEdit::class)->name('branches.edit')->middleware('can:Edit Branch');
        Route::get('/companies/create', CompanyCreate::class)->name('companies.create')->middleware('can:Create Company');
        Route::get('/companies/{id}', CompanyShow::class)->name('companies.show')->middleware('can:View Company');
        Route::get('/companies/{id}/attachments', CompanyAttachments::class)->name('companies.attachments')->middleware('can:View Company');
        Route::get('/companies/{id}/branches', CompanyBranches::class)->name('companies.branches')->middleware('can:View Company');
        Route::get('/companies/{id}/user-roles', CompanyUserRoles::class)->name('companies.user-roles')->middleware('can:View Company');
        Route::get('/companies/{id}/roles-permissions', RolesPermissions::class)->name('companies.roles-permissions')->middleware('can:Manage Roles and Permissions');
        Route::get('/companies/{id}/billing-entity', CompanyBillingEntity::class)->name('companies.billing-entity')->middleware('can:View Company');
        Route::get('/companies/{id}/edit', CompanyEdit::class)->name('companies.edit')->middleware('can:Edit Company');
        Route::get('/companies/{company}/features', FeaturesListing::class)->name('companies.features')->middleware('can:Manage Features');
        Route::get('/features', FeaturesListing::class)->name('features')->middleware('can:Manage Features');
        Route::get('/users', UserListing::class)->name('users.index')->middleware('can:View Users');
        Route::get('/users/create', UserCreate::class)->name('users.create')->middleware('can:Create User');
        Route::get('/users/{id}/edit', UserEdit::class)->name('users.edit')->middleware('can:Edit User');
        Route::get('/roles-permissions', RolesPermissions::class)->name('roles.index')->middleware('can:Manage Roles and Permissions');
        Route::get('/trip-purpose', TripPurpose::class)->name('admin.trip-purpose')->middleware('can:Manage Global System');
        Route::get('/trip-purpose/create', \App\Livewire\TripPurpose\TripPurposeCreate::class)->name('admin.trip-purpose.create')->middleware('can:Manage Global System');
        Route::get('/trip-purpose/{tripPurpose}', TripPurposeView::class)->name('admin.trip-purpose.view')->middleware('can:Manage Global System');
        Route::get('/trip-purpose/{tripPurpose}/edit', TripPurposeEdit::class)->name('admin.trip-purpose.edit')->middleware('can:Manage Global System');
        Route::get('/integrations-api', IntegrationsApi::class)->name('admin.integrations-api')->middleware('can:Manage Global System');
        Route::get('/audit-logs', AuditLogs::class)->name('admin.audit-logs')->middleware('can:Manage Global System');
        Route::get('/audit-logs/{activityLog}', AuditLogView::class)->name('admin.audit-logs.view')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities', CountriesAndCities::class)->name('admin.countries-and-cities')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/country/create', \App\Livewire\CountriesAndCities\CountryCreate::class)->name('admin.countries.create')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/country/{country}', \App\Livewire\CountriesAndCities\CountryView::class)->name('admin.countries.view')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/country/{country}/edit', \App\Livewire\CountriesAndCities\CountryEdit::class)->name('admin.countries.edit')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/city/create', \App\Livewire\CountriesAndCities\CityCreate::class)->name('admin.cities.create')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/city/{city}', \App\Livewire\CountriesAndCities\CityView::class)->name('admin.cities.view')->middleware('can:Manage Global System');
        Route::get('/countries-and-cities/city/{city}/edit', \App\Livewire\CountriesAndCities\CityEdit::class)->name('admin.cities.edit')->middleware('can:Manage Global System');
        Route::get('/airports', Airports::class)->name('admin.airports')->middleware('can:Manage Global System');
        Route::get('/airports/create', \App\Livewire\Airports\AirportCreate::class)->name('admin.airports.create')->middleware('can:Manage Global System');
        Route::get('/airports/{airport}', \App\Livewire\Airports\AirportView::class)->name('admin.airports.view')->middleware('can:Manage Global System');
        Route::get('/airports/{airport}/edit', \App\Livewire\Airports\AirportEdit::class)->name('admin.airports.edit')->middleware('can:Manage Global System');

        // Impersonation
        Route::get('/impersonate/take/{user}', [\App\Http\Controllers\ImpersonateController::class, 'take'])->name('impersonate.take');
    });

    Route::get('/impersonate/leave', [\App\Http\Controllers\ImpersonateController::class, 'leave'])->name('impersonate.leave');






    Route::get('/hotels', Hotel::class)->middleware('feature.access:hotels-module')->name('hotels');
    Route::get('/cars', Car::class)->middleware('feature.access:cars-module')->name('cars');
    Route::get('/concierge', Concierge::class)->middleware('feature.access:concierge-module')->name('concierge');
    Route::get('/travel-hub', TravelHub::class)->middleware('feature.access:travel-hub-module')->name('travel.hub');

    Route::middleware(['feature.access:flights-module'])->group(function () {
        Route::get('/flights-search', FlightSearch::class)->name('flights.search');
        Route::get('/flights-list', FlightList::class)->name('flights.list');
        Route::get('/passenger-details', PassengerDetail::class)->name('passenger.details');
        Route::get('/seating', ChooseSeat::class)->name('seating');
        Route::get('/additional-services', AdditionalServices::class)->name('additional.services');
        Route::get('/flight-confirmation', FlightConfirmation::class)->name('flight.confirmation');
    });

});
