<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Pages\Booking\BookingConfirmationSuccess;
use App\Livewire\Pages\Booking\PaymentPage;
use App\Livewire\Pages\Booking\ReviewBooking;
use App\Livewire\Pages\Booking\TravellerAddonsMeal;
use App\Livewire\Pages\Booking\TravellerDetails;
use App\Livewire\Pages\Flights\ListingOneway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home\Index;

Route::redirect('/', '/login');


Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');


Route::livewire('/home', Index::class)->name('home');
Route::livewire('/flight-listing-oneway.html', ListingOneway::class)->name('flight.listing.oneway');
Route::livewire('/review-booking.html', ReviewBooking::class)->name('booking.review');
Route::livewire('/traveller-details.html', TravellerDetails::class)->name('booking.traveller_details');
Route::livewire('/traveller-addons-meal.html', TravellerAddonsMeal::class)->name('booking.traveller_addons_meal');
Route::livewire('/payment.html', PaymentPage::class)->name('booking.payment');
Route::livewire('/booking-confirmation-success.html', BookingConfirmationSuccess::class)->name('booking.confirmation_success');


Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// Route::view('/dashboard', 'dashboard')
//     ->middleware('auth')
//     ->name('dashboard');
