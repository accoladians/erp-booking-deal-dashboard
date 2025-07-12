# Implementation Guide - Step by Step

## Prerequisites

### Server Access Requirements
1. SSH access to the server
2. Sudo privileges for nginx configuration
3. Write access to Laravel application directory
4. Access to MySQL database

### Development Environment
- PHP 7.4+ with Laravel requirements
- Node.js and npm for asset compilation
- Composer for PHP dependencies
- Git for version control

## Phase 1: Server Configuration

### Step 1: Configure Nginx IP Whitelist

If the new server needs to access the application without VPN:

```bash
# 1. Backup existing configuration
sudo cp /etc/nginx/conf.d/httpauth /etc/nginx/conf.d/httpauth.backup

# 2. Edit the configuration
sudo nano /etc/nginx/conf.d/httpauth

# 3. Add your server IP (replace YOUR_SERVER_IP)
satisfy any;

allow 127.0.0.1;
allow 65.109.180.45; ## vpn2.accolade.fi
allow YOUR_SERVER_IP; ## New server

auth_basic "Restricted site";
auth_basic_user_file  /etc/nginx/conf.d/.htpasswd;
deny all;

# 4. Test and reload nginx
sudo nginx -t
sudo systemctl reload nginx
```

### Step 2: Locate Laravel Application

```bash
# Find the current release
ls -la /var/www/dev.x.xwander.fi/revisions/

# Note the latest release directory (e.g., release-dev-35991)
cd /var/www/dev.x.xwander.fi/revisions/release-dev-XXXXX
```

## Phase 2: Create Test Route

### Step 1: Add Test Route

Edit `routes/web.php`:

```php
// Add after existing booking routes (around line 236)
// Test route for new dashboard
Route::get('booking/deal/id/{deal}/dashboard', [BookingDealController::class, 'dashboard'])
    ->name('booking.deal.dashboard');

// Hello world test route
Route::get('booking/newdealui/helloworld', [BookingDealController::class, 'helloWorld'])
    ->name('booking.newdealui.helloworld');
```

### Step 2: Add Controller Methods

Edit `app/Http/Controllers/Booking/DealController.php`:

```php
/**
 * Display new modular dashboard for a deal
 */
public function dashboard(Deal $deal, Request $request)
{
    $deal->load([
        'booking_items.source.vendor',
        'transactions',
        'contact',
        'owner'
    ]);
    
    // Group booking items by type
    $accommodations = $deal->booking_items->where('type', 'accommodation');
    $services = $deal->booking_items->where('type', '!=', 'accommodation')
        ->groupBy('type');
    
    $data = compact('deal', 'accommodations', 'services') + $this->getViewData();
    
    return Response::view('guide.booking.deal.dashboard', $data);
}

/**
 * Hello World test page
 */
public function helloWorld()
{
    return Response::view('guide.booking.newdealui.helloworld');
}
```

### Step 3: Create Test View

Create directory and file:

```bash
# Create directory
sudo mkdir -p resources/views/guide/booking/newdealui

# Create hello world view
sudo nano resources/views/guide/booking/newdealui/helloworld.blade.php
```

Content:
```blade
@extends('page.layout')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Hello World - New Deal UI Test</h4>
            </div>
            <div class="card-body text-center">
                <h1>Dashboard Development Ready!</h1>
                <p>If you can see this, the setup is working correctly.</p>
                <hr>
                <p>Server: {{ request()->getHost() }}</p>
                <p>Time: {{ now() }}</p>
                <p>Your IP: {{ request()->ip() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Step 4: Clear Laravel Cache

```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### Step 5: Test Access

Visit: `https://dev.erp.xwander.fi/booking/newdealui/helloworld`

## Phase 3: Implement Dashboard

### Step 1: Create Dashboard View Structure

```bash
# Create dashboard directory
sudo mkdir -p resources/views/guide/booking/deal/dashboard

# Create main dashboard view
sudo nano resources/views/guide/booking/deal/dashboard.blade.php
```

### Step 2: Main Dashboard Template

Create `resources/views/guide/booking/deal/dashboard.blade.php`:

```blade
@extends('page.layout')

@section('content')
<div class="booking-deal-dashboard">
    <div class="row">
        <!-- Deal Info Panel -->
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.deal-info-panel')
        </div>
        
        <!-- Guests Panel -->
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.guests-panel')
        </div>
        
        <!-- Accommodation Panel -->
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.accommodation-panel')
        </div>
        
        <!-- Services Panel -->
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.services-panel')
        </div>
        
        <!-- Financial Panel -->
        <div class="col-12 mb-4">
            @include('guide.booking.deal.dashboard.financial-panel')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        new BookingDealDashboard({{ $deal->id }});
    });
</script>
@endsection
```

### Step 3: Create Panel Components

Create each panel file in `resources/views/guide/booking/deal/dashboard/`:

1. **deal-info-panel.blade.php**
2. **guests-panel.blade.php**
3. **accommodation-panel.blade.php**
4. **services-panel.blade.php**
5. **financial-panel.blade.php**

### Step 4: Add SCSS Styles

Create `resources/scss/pages/booking_deal_dashboard.scss`:

```scss
@import "../variables_common";

.booking-deal-dashboard {
    // Styles from technical specs
}
```

Import in main SCSS:
```scss
// In resources/scss/app.scss
@import "pages/booking_deal_dashboard";
```

### Step 5: Compile Assets

```bash
# Install dependencies if needed
npm install

# Compile assets
npm run dev

# For production
npm run build
```

## Phase 4: Add Navigation

### Step 1: Add Dashboard Link

In the existing deal view (`resources/views/guide/booking/deal/show.blade.php`), add a link to the new dashboard:

```blade
<!-- Add after the HubSpot link -->
<a href="{{ route('booking.deal.dashboard', $deal) }}" class="btn btn-info btn-sm">
    <i class="fa-solid fa-dashboard"></i> View Dashboard
</a>
```

### Step 2: Add Toggle Option

Allow users to switch between views:

```javascript
// Add to page
<script>
function toggleDashboardView() {
    const preference = localStorage.getItem('dealViewPreference') || 'form';
    if (preference === 'dashboard') {
        window.location.href = '{{ route("booking.deal.dashboard", $deal) }}';
    }
}

// Check preference on load
toggleDashboardView();
</script>
```

## Phase 5: Testing

### Step 1: Manual Testing Checklist

- [ ] Hello world page loads
- [ ] Dashboard route works
- [ ] All panels display correctly
- [ ] Data loads properly
- [ ] Responsive on mobile
- [ ] No JavaScript errors
- [ ] Links work correctly
- [ ] Permissions respected

### Step 2: Create Test Data

```sql
-- Verify test deal exists
SELECT * FROM booking_deal WHERE id = 16790622275;

-- Check related booking items
SELECT * FROM booking_item WHERE hs_deal_id = 16790622275;

-- Check transactions
SELECT t.* FROM account_transaction t
JOIN account_transaction_hs_associations a ON t.id = a.transaction_id
WHERE a.hs_object_id = 16790622275 AND a.type = 'deal';
```

### Step 3: Performance Testing

```bash
# Test page load time
curl -w "@curl-format.txt" -o /dev/null -s https://dev.erp.xwander.fi/booking/deal/id/16790622275/dashboard

# Monitor Laravel logs
tail -f storage/logs/laravel.log
```

## Phase 6: Deployment

### Step 1: Version Control

```bash
# Create feature branch
git checkout -b feature/booking-deal-dashboard

# Add files
git add .
git commit -m "Add modular dashboard for booking deals"

# Push to repository
git push origin feature/booking-deal-dashboard
```

### Step 2: Deploy to Production

Follow existing deployment process:
- Create pull request
- Get code review
- Merge to main branch
- Deploy using existing CI/CD

### Step 3: Monitor

- Check error logs
- Monitor performance
- Gather user feedback
- Iterate based on feedback

## Troubleshooting

### Common Issues

1. **404 Error on routes**
   - Clear route cache: `php artisan route:clear`
   - Check route exists: `php artisan route:list | grep dashboard`

2. **Permission denied**
   - Check file ownership: `ls -la`
   - Fix permissions: `sudo chown -R www-data:www-data .`

3. **Styles not loading**
   - Recompile assets: `npm run dev`
   - Clear browser cache
   - Check asset versioning

4. **Data not loading**
   - Check model relationships
   - Verify database queries
   - Check Laravel logs

## Rollback Plan

If issues arise:

1. **Quick disable**: Comment out new routes
2. **Full rollback**: 
   ```bash
   git checkout main
   php artisan config:clear
   ```
3. **Nginx rollback**: Restore backup configuration
4. **Database**: No database changes required