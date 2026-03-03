<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;

Route::get('token', [FlightApiController::class, 'getToken']);


Route::prefix('flights')->group(function () {

    // -------------------------------------------------------
    // 🔍 Search
    // -------------------------------------------------------
    Route::get('search', [FlightApiController::class, 'searchFlights']);
    Route::post('search', [FlightApiController::class, 'searchFlightsPost']);

    // -------------------------------------------------------
    // 🌍 Destinations & Inspiration
    // -------------------------------------------------------
    Route::get('destinations', [FlightApiController::class, 'getFlightDestinations']); // 👈 new

    // -------------------------------------------------------
    // 🤖 AI Prediction
    // -------------------------------------------------------
    Route::post('prediction', [FlightApiController::class, 'predictFlightChoice']);

    // -------------------------------------------------------
    // 💰 Pricing & Upselling
    // -------------------------------------------------------
    Route::post('price', [FlightApiController::class, 'priceFlightOffer']);
    Route::post('upselling', [FlightApiController::class, 'upsellFlightOffer']);

    // -------------------------------------------------------
    // 💺 Seat Maps
    // -------------------------------------------------------
    Route::post('seatmaps', [FlightApiController::class, 'getSeatMapByFlightOffer']);
    Route::get('seatmaps', [FlightApiController::class, 'getSeatMapByOrderId']);

    // -------------------------------------------------------
    // 📋 Booking / Orders
    // -------------------------------------------------------
    Route::post('orders', [FlightApiController::class, 'bookFlight']);
    Route::get('orders/{orderId}', [FlightApiController::class, 'getFlightOrder'])->where('orderId', '.*');
    Route::delete('orders/{orderId}', [FlightApiController::class, 'deleteFlightOrder'])->where('orderId', '.*');

});

Route::prefix('analytics')->group(function () {

    // -------------------------------------------------------
    // 📊 Analytics
    // -------------------------------------------------------
    Route::get('itinerary-price-metrics', [FlightApiController::class, 'getItineraryPriceMetrics']);

});




?>