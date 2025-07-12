<?php
// Add this method to your app/Http/Controllers/Booking/DealController.php

/**
 * Display the new modular dashboard for a booking deal
 *
 * @param Deal $deal
 * @param Request $request
 * @return Response
 */
public function dashboard(Deal $deal, Request $request)
{
    // Eager load all necessary relationships to avoid N+1 queries
    $deal->load([
        'booking_items.source.vendor',
        'booking_items.contact',
        'transactions',
        'contact',
        'owner'
    ]);
    
    // Group booking items by type for easier display
    $accommodations = $deal->booking_items->where('type', 'accommodation');
    $services = $deal->booking_items->where('type', '!=', 'accommodation')
        ->groupBy('type');
    
    // Calculate financial summary
    $totalPaid = $deal->transactions
        ->where('amount', '>', 0)
        ->sum('amount');
    $balance = $deal->total_amount - $totalPaid;
    
    // Get additional view data (breadcrumbs, user info, etc.)
    $data = compact('deal', 'accommodations', 'services', 'totalPaid', 'balance') 
        + $this->getViewData();
    
    return Response::view('guide.booking.deal.dashboard.dashboard', $data);
}