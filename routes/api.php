<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;

Route::get('token', [FlightApiController::class, 'getToken']);


Route::prefix('flights')->group(function () {

    // -------------------------------------------------------
    // 🔍 Search
    // -------------------------------------------------------
    Route::get('search', [FlightController::class, 'searchFlights']);
    Route::post('search', [FlightController::class, 'searchFlightsPost']);

    // -------------------------------------------------------
    // 🌍 Destinations & Inspiration
    // -------------------------------------------------------
    Route::get('destinations', [FlightController::class, 'getFlightDestinations']); // 👈 new

    // -------------------------------------------------------
    // 🤖 AI Prediction
    // -------------------------------------------------------
    Route::post('prediction', [FlightController::class, 'predictFlightChoice']);

    // -------------------------------------------------------
    // 💰 Pricing & Upselling
    // -------------------------------------------------------
    Route::post('price', [FlightController::class, 'priceFlightOffer']);
    Route::post('upselling', [FlightController::class, 'upsellFlightOffer']);

    // -------------------------------------------------------
    // 💺 Seat Maps
    // -------------------------------------------------------
    Route::post('seatmaps', [FlightController::class, 'getSeatMapByFlightOffer']);
    Route::get('seatmaps', [FlightController::class, 'getSeatMapByOrderId']);

    // -------------------------------------------------------
    // 📋 Booking / Orders
    // -------------------------------------------------------
    Route::post('orders', [FlightController::class, 'bookFlight']);
    Route::get('orders/{orderId}', [FlightController::class, 'getFlightOrder'])->where('orderId', '.*');
    Route::delete('orders/{orderId}', [FlightController::class, 'deleteFlightOrder'])->where('orderId', '.*');

});

Route::prefix('analytics')->group(function () {

    // -------------------------------------------------------
    // 📊 Analytics
    // -------------------------------------------------------
    Route::get('itinerary-price-metrics', [FlightController::class, 'getItineraryPriceMetrics']);

});




?>