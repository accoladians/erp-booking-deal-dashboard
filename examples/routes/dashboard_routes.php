<?php
// Add these routes to your routes/web.php file

// Dashboard route - shows the new modular dashboard
Route::get('booking/deal/id/{deal}/dashboard', [BookingDealController::class, 'dashboard'])
    ->name('booking.deal.dashboard');

// API routes for AJAX data loading (optional)
Route::prefix('api/booking/deal')->group(function () {
    Route::get('{deal}/summary', [BookingDealController::class, 'apiSummary'])
        ->name('api.booking.deal.summary');
    Route::get('{deal}/guests', [BookingDealController::class, 'apiGuests'])
        ->name('api.booking.deal.guests');
    Route::get('{deal}/accommodations', [BookingDealController::class, 'apiAccommodations'])
        ->name('api.booking.deal.accommodations');
    Route::get('{deal}/services', [BookingDealController::class, 'apiServices'])
        ->name('api.booking.deal.services');
    Route::get('{deal}/transactions', [BookingDealController::class, 'apiTransactions'])
        ->name('api.booking.deal.transactions');
});