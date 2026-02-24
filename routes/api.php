<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;

Route::get('token', [FlightApiController::class, 'getToken']);

//flight booking routes
Route::get('/flights-search', [FlightApiController::class, 'searchFlights']);
Route::post('/flight-pricing', [FlightApiController::class, 'priceFlightOffers']);
Route::post('/flight-book', [FlightApiController::class, 'bookFlight']);
Route::get('/flight-price-analysis', [FlightApiController::class, 'flightPriceAnalysis']);


//flight scheduling routes
Route::get('/flight-schedule/{carrier}/{flight}/{date}', [FlightApiController::class, 'getFlightSchedule']);
Route::get('/flight-delay', [FlightApiController::class, 'predictFlightDelay']);
Route::get('/airport-ontime/{airport}/{date}', [FlightApiController::class, 'predictAirportOnTime']);


?>