# System Architecture Documentation

## Laravel Application Structure

### File Locations (Based on dev.erp.xwander.fi analysis)

```
/var/www/dev.x.xwander.fi/revisions/release-dev-{version}/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Booking/
│   │           └── DealController.php          # Main controller
│   └── Models/
│       ├── Booking/
│       │   ├── Deal.php                        # Primary deal model
│       │   └── BookingItem.php                 # Related bookings
│       ├── Hubspot/
│       │   └── Contact.php                     # Guest model
│       └── Account/
│           └── Transaction.php                 # Financial records
├── database/
│   └── migrations/
│       ├── 2024_10_04_102752_create_booking_deal.php
│       └── 2024_12_20_110524_alter_booking_deal_add_accommodation_arrival_checklist.php
├── resources/
│   ├── views/
│   │   └── guide/
│   │       └── booking/
│   │           └── deal/
│   │               ├── show.blade.php          # Current main view
│   │               ├── _form.blade.php         # Form partial
│   │               ├── _list.blade.php         # List view
│   │               └── _delete_modal.blade.php
│   └── scss/
│       ├── app.scss                            # Main SCSS
│       ├── pages/
│       │   └── dashboard.scss
│       └── components/
│           └── _card.scss
├── routes/
│   └── web.php                                 # Route definitions
└── public/
    └── build/                                  # Compiled assets
```

## Database Schema

### booking_deal Table
```sql
CREATE TABLE booking_deal (
    id BIGINT UNSIGNED PRIMARY KEY,              -- HubSpot deal ID
    name VARCHAR(255) NOT NULL,                  -- Deal title
    arrival_date DATE NOT NULL,                  -- Check-in date
    departure_date DATE NOT NULL,                -- Check-out date
    accommodation TEXT,                          -- Accommodation details
    arrival_checklist TEXT,                      -- Arrival tasks
    description TEXT,                            -- Deal description
    dealstage VARCHAR(255) NOT NULL,             -- HubSpot pipeline stage
    pax_total INT DEFAULT 0,                     -- Total guests
    pax_adults INT DEFAULT 0,                    -- Adult count
    pax_kids INT DEFAULT 0,                      -- Children count
    total_amount DECIMAL(10,2) DEFAULT 0,        -- Deal value
    payment_status ENUM('Not Paid', 'Paid', 'Deposit Paid') DEFAULT 'Not Paid',
    vendor_payment_status ENUM('Not Paid', 'Paid', 'Deposit Paid', 'Partially Paid', 'Free of Charge') DEFAULT 'Not Paid',
    hs_contact_id BIGINT UNSIGNED,               -- Primary guest reference
    hs_owner_id BIGINT UNSIGNED NOT NULL,        -- Sales rep ID
    dev_allowed BOOLEAN DEFAULT FALSE,           -- Dev environment flag
    last_sync_at TIMESTAMP,                      -- HubSpot sync time
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Related Tables
- **booking_item**: Individual services linked to deals
- **hs_contact**: Guest information from HubSpot
- **account_transaction**: Financial transactions
- **account_transaction_hs_associations**: Links transactions to deals

## Model Relationships

### Deal Model
```php
class Deal extends Model {
    // Relationships
    belongsTo: contact (via hs_contact_id)
    belongsTo: owner (via hs_owner_id)
    hasMany: booking_items (via hs_deal_id)
    hasManyThrough: transactions (via TransactionHsAssociations)
}
```

### BookingItem Model
```php
class BookingItem extends Model {
    // Relationships
    belongsTo: deal (via hs_deal_id)
    belongsTo: vendor
    belongsTo: source
    belongsTo: contact
    belongsTo: owner
}
```

## Current Routes

```php
// Booking deal routes in web.php
Route::get('booking/deal/list', [BookingDealController::class, 'list'])->name('booking.deal.list');
Route::get('booking/deal/new', [BookingDealController::class, 'show'])->name('booking.deal.new');
Route::get('booking/deal/id/{deal}', [BookingDealController::class, 'show'])->name('booking.deal.show');
Route::put('booking/deal/id/{deal}', [BookingDealController::class, 'store']);
Route::delete('booking/deal/id/{deal}', [BookingDealController::class, 'destroy']);
Route::post('booking/deal/new', [BookingDealController::class, 'store']);
```

## Controller Methods

### DealController Key Methods

1. **list()** - Display paginated deals with filtering
2. **show()** - Display single deal (current form view)
3. **store()** - Create/update deal with HubSpot sync
4. **destroy()** - Delete deal
5. **syncHSLineItems()** - Sync booking items to HubSpot

## Authentication & Security

### Current Setup
- HTTP Basic Authentication via nginx
- IP whitelist in `/etc/nginx/conf.d/httpauth`
- Laravel middleware: 'auth'
- CSRF protection on forms

### Server Access
- Production environment checks via `App::isProduction()`
- Development flag: `dev_allowed` in database
- HubSpot API integration with environment-specific keys

## Asset Compilation
- Uses Vite or Laravel Mix
- SCSS compiled to CSS
- JavaScript bundled
- Assets served from `/public/build/`

## External Integrations

### HubSpot CRM
- Deals API for CRUD operations
- Contacts API for guest management
- Line Items API for booking items
- Companies API (referenced)

### Services Used
- `App\Services\Hubspot\Deals`
- `App\Services\Hubspot\Contacts`
- `App\Services\Hubspot\LineItems`
- `App\Services\Hubspot\Companies`