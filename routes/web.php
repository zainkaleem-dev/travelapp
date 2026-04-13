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
use App\Livewire\Admin\CompanyIndex;
use App\Livewire\User\UserListing;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserEdit;
use App\Livewire\Admin\Roles\RoleIndex; 
use App\Livewire\Travelhub\TravelHub; 
use App\Livewire\Company\CompanyListing;
use App\Livewire\SubCompany\SubCompanyIndex as SubCompanyAdminIndex;
use App\Livewire\Branch\BranchListing;
use App\Livewire\Branch\BranchCreate;
use App\Livewire\Branch\BranchEdit;

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
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Root URL
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('flights.search')
        : redirect()->route('login');
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
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard'); 
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/profile/family/create', FamilyCreate::class)->name('family.create');
    Route::get('/profile/family/{id}/edit', FamilyEdit::class)->name('family.edit');

    Route::get('/settings', Setting::class)->name('settings');

    Route::get('/corporate-settings', CorporateSettings::class)->name('corporate.settings');
    Route::get('/tmc-settings', TmcSettings::class)->name('tmc.settings');
    Route::get('/super-admin-settings', SuperAdminSettings::class)->name('superadmin.settings');

    Route::middleware(['superadmin'])->prefix('super-admin')->group(function () { 
        Route::get('/companies', CompanyIndex::class)->name('superadmin.companies.index'); 
        Route::get('/branches', BranchListing::class)->name('superadmin.branches');
        Route::get('/branches/create', BranchCreate::class)->name('superadmin.branches.create');
        Route::get('/branches/{id}/edit', BranchEdit::class)->name('superadmin.branches.edit');
        Route::get('/companies/create', CompanyCreate::class)->name('superadmin.companies.create');
        Route::get('/companies/{id}/edit', CompanyEdit::class)->name('superadmin.companies.edit');
        Route::get('/users', UserListing::class)
            ->middleware(['superadmin.company_query', 'superadmin.tenant'])
            ->name('superadmin.users');
        Route::get('/users/create', UserCreate::class)->name('superadmin.users.create');
        Route::get('/users/{id}/edit', UserEdit::class)->name('superadmin.users.edit');
        Route::get('/roles', RoleIndex::class)->name('superadmin.roles');
    }); 
 
    Route::get('/company/companies', CompanyListing::class)
        ->middleware(['company.tenant', 'company.admin'])
        ->name('company.companies.index');
 
    Route::get('/subcompany', SubCompanyAdminIndex::class)
        ->middleware(['company.tenant', 'subcompany.admin'])
        ->name('subcompany.index');

    Route::get('/hotels', Hotel::class)->name('hotels'); 
    Route::get('/cars', Car::class)->name('cars');
    Route::get('/concierge', Concierge::class)->name('concierge');
    Route::get('/travel-hub', TravelHub::class)->name('travel.hub');

    Route::get('/flights-search', FlightSearch::class)
        ->name('flights.search');

    Route::get('/flights-list', FlightList::class)
        ->name('flights.list');

    Route::get('/passenger-details', PassengerDetail::class)
        ->name('passenger.details');

    Route::get('/seating', ChooseSeat::class)
        ->name('seating');

    Route::get('/additional-services', AdditionalServices::class)
        ->name('additional.services');

    Route::get('/flight-confirmation', FlightConfirmation::class)
        ->name('flight.confirmation');

});
