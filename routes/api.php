<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;

Route::get('token', [FlightApiController::class, 'getToken']);

Route::get('/flights-search', [FlightApiController::class, 'searchFlights']);
Route::post('/flights-search-post', [FlightApiController::class, 'searchFlightsPost']);
Route::post('/flights/book', [FlightApiController::class, 'bookFlight']);
Route::post('/flights/price', [FlightApiController::class, 'priceFlightOffer']);
Route::get('/flights/orders/{orderId}', [FlightApiController::class, 'getFlightOrder'])
    ->where('orderId', '.*'); // Allow special characters like = + / in the ID
Route::delete('/flights/orders/{orderId}', [FlightApiController::class, 'deleteFlightOrder'])
    ->where('orderId', '.*');
Route::get('/seatmaps', [FlightApiController::class, 'getSeatMapByOrderId']);



?>