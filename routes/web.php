<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
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
use App\Livewire\User\UserListing;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserEdit;
use App\Livewire\Travelhub\TravelHub;
use App\Livewire\Company\CompanyListing;
use App\Livewire\Branch\BranchListing;
use App\Livewire\Branch\BranchCreate;
use App\Livewire\Branch\BranchEdit;
use App\Livewire\Admin\Features\FeaturesListing;
use App\Livewire\Roles\RolesPermissions;

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
    $allowed = ['business_trip', 'personal_trip', 'annual_trip', 'guest'];

    abort_unless(in_array($type, $allowed, true), 404);

    $request->session()->put('trip_type', $type);

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
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/profile/family/create', FamilyCreate::class)->name('family.create');
    Route::get('/profile/family/{id}/edit', FamilyEdit::class)->name('family.edit');

    Route::get('/settings', Setting::class)->name('settings');

    Route::get('/corporate-settings', CorporateSettings::class)->name('corporate.settings');
    Route::get('/tmc-settings', TmcSettings::class)->name('tmc.settings');


    Route::middleware(['superadmin'])->group(function () {
        Route::get('/settings', SuperAdminSettings::class)->name('settings');
        Route::get('/companies', CompanyListing::class)->name('companies.index')->middleware('can:View Company');
        Route::get('/branches', BranchListing::class)->name('branches.index')->middleware('can:View Branch');
        Route::get('/branches/create', BranchCreate::class)->name('branches.create')->middleware('can:Create Branch');
        Route::get('/branches/{id}/edit', BranchEdit::class)->name('branches.edit')->middleware('can:Edit Branch');
        Route::get('/companies/create', CompanyCreate::class)->name('companies.create')->middleware('can:Create Company');
        Route::get('/companies/{id}/edit', CompanyEdit::class)->name('companies.edit')->middleware('can:Edit Company');
        Route::get('/companies/{company}/features', FeaturesListing::class)->name('companies.features')->middleware('can:Manage Features');
        Route::get('/features', FeaturesListing::class)->name('features')->middleware('can:Manage Features');
        Route::get('/users', UserListing::class)->name('users.index')->middleware('can:View Users');
        Route::get('/users/create', UserCreate::class)->name('users.create')->middleware('can:Create User');
        Route::get('/users/{id}/edit', UserEdit::class)->name('users.edit')->middleware('can:Edit User');
        Route::get('/roles-permissions', RolesPermissions::class)->name('roles.index')->middleware('can:Manage Roles and Permissions');

        // Impersonation
        Route::get('/impersonate/take/{user}', [\App\Http\Controllers\ImpersonateController::class, 'take'])->name('impersonate.take');
    });






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
