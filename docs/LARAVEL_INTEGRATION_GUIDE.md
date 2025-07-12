# Laravel Integration Guide

This guide provides step-by-step instructions for integrating the Booking Deal Dashboard into your Laravel application.

## Prerequisites

- Laravel application at `/var/www/dev.x.xwander.fi/`
- PHP 8.2+
- Node.js and npm for asset compilation
- Composer

## Integration Steps

### Step 1: Copy View Files

Copy the Blade templates to your Laravel views directory:

```bash
# From the repository root
sudo cp -r src/views/* /var/www/dev.x.xwander.fi/http/resources/views/guide/booking/deal/dashboard/

# Set proper permissions
sudo chown -R www-data:www-data /var/www/dev.x.xwander.fi/http/resources/views/guide/booking/deal/dashboard/
```

### Step 2: Copy SCSS Files

Copy the styles to your SCSS directory:

```bash
sudo cp src/scss/booking_deal_dashboard.scss /var/www/dev.x.xwander.fi/http/resources/scss/pages/
```

Add the import to your main SCSS file (`resources/scss/app.scss`):

```scss
@import "pages/booking_deal_dashboard";
```

### Step 3: Add Routes

Add the dashboard route to `routes/web.php` (around line 236, after existing booking routes):

```php
// Dashboard route
Route::get('booking/deal/id/{deal}/dashboard', [BookingDealController::class, 'dashboard'])
    ->name('booking.deal.dashboard');
```

### Step 4: Update Controller

Add the dashboard method to `app/Http/Controllers/Booking/DealController.php`:

```php
public function dashboard(Deal $deal, Request $request)
{
    $deal->load([
        'booking_items.source.vendor',
        'transactions',
        'contact',
        'owner'
    ]);
    
    $accommodations = $deal->booking_items->where('type', 'accommodation');
    $services = $deal->booking_items->where('type', '!=', 'accommodation')
        ->groupBy('type');
    
    $totalPaid = $deal->transactions->where('amount', '>', 0)->sum('amount');
    $balance = $deal->total_amount - $totalPaid;
    
    $data = compact('deal', 'accommodations', 'services', 'totalPaid', 'balance') 
        + $this->getViewData();
    
    return Response::view('guide.booking.deal.dashboard.dashboard', $data);
}
```

### Step 5: Clear Caches and Compile Assets

```bash
# Clear Laravel caches
php artisan route:clear
php artisan view:clear
php artisan config:clear

# Compile assets
npm install
npm run dev
```

### Step 6: Add Navigation Link

In the existing deal view (`resources/views/guide/booking/deal/show.blade.php`), add a link to the dashboard:

```blade
<!-- Add after the HubSpot link -->
<a href="{{ route('booking.deal.dashboard', $deal) }}" class="btn btn-info btn-sm">
    <i class="fa-solid fa-dashboard"></i> View Dashboard
</a>
```

## Testing

1. Navigate to: `https://dev.erp.xwander.fi/booking/deal/id/16790622275/dashboard`
2. Verify all panels load correctly
3. Test responsive design on mobile devices
4. Check that all data displays accurately

## Customization

### Modifying Styles

The main SCSS file (`booking_deal_dashboard.scss`) uses CSS variables for easy theming:

```scss
:root {
    --dashboard-primary: var(--bs-primary);
    --dashboard-panel-bg: #ffffff;
    --dashboard-panel-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
```

### Adding New Panels

1. Create a new Blade template in the dashboard directory
2. Include it in the main dashboard template
3. Add corresponding styles in the SCSS file

## Troubleshooting

### 404 Error
- Clear route cache: `php artisan route:clear`
- Verify the route exists: `php artisan route:list | grep dashboard`

### Styles Not Loading
- Recompile assets: `npm run dev`
- Clear browser cache
- Check that SCSS import is added to app.scss

### Permission Denied
- Fix file ownership: `sudo chown -R www-data:www-data .`
- Check nginx user permissions

## Rollback

If you need to remove the dashboard:

1. Remove the route from web.php
2. Remove the dashboard method from DealController
3. Delete the dashboard views directory
4. Remove the SCSS import from app.scss
5. Recompile assets

## Support

For issues or questions:
1. Check the documentation in `/docs`
2. Review the example implementations in `/examples`
3. Create an issue on GitHub: https://github.com/accoladians/erp-booking-deal-dashboard