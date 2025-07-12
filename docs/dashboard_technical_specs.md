# Technical Specifications - Booking Deal Dashboard

## Dashboard Layout Specifications

### Grid System
- **Framework**: Bootstrap 5 responsive grid
- **Container**: `container-fluid` with custom max-width
- **Breakpoints**: 
  - Mobile: < 768px (single column)
  - Tablet: 768px - 991px (2 columns)
  - Desktop: ≥ 992px (optimized layout)

### Panel Layout Matrix

```
Desktop (≥992px):
┌─────────────────────┬─────────────────────┐
│   Deal Info (6)     │    Guests (6)       │
├─────────────────────┼─────────────────────┤
│ Accommodation (6)   │   Services (6)      │
├─────────────────────┴─────────────────────┤
│      Prices & Transactions (12)           │
└───────────────────────────────────────────┘

Mobile (<768px):
┌─────────────────────┐
│   Deal Info (12)    │
├─────────────────────┤
│    Guests (12)      │
├─────────────────────┤
│ Accommodation (12)  │
├─────────────────────┤
│   Services (12)     │
├─────────────────────┤
│ Prices & Trans (12) │
└─────────────────────┘
```

## Component Specifications

### 1. Deal Info Panel
```html
<div class="col-lg-6">
    <div class="card panel-deal-info">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Deal Information</h5>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" data-action="edit">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <a href="{{ hubspot_url }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="fab fa-hubspot"></i> HubSpot
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Dynamic content -->
        </div>
    </div>
</div>
```

**Data Fields**:
- Deal ID & Name
- Period (arrival/departure dates with date range picker)
- Description (expandable text area)
- Stage (dropdown with HubSpot stages)
- Owner (sales representative)

### 2. Guests Panel
```html
<div class="col-lg-6">
    <div class="card panel-guests">
        <div class="card-header">
            <h5 class="mb-0">Guests</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-4 text-center">
                    <div class="pax-counter pax-total">
                        <span class="count">{{ pax_total }}</span>
                        <small class="d-block">Total</small>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="pax-counter pax-adults">
                        <span class="count">{{ pax_adults }}</span>
                        <small class="d-block">Adults</small>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="pax-counter pax-kids">
                        <span class="count">{{ pax_kids }}</span>
                        <small class="d-block">Kids</small>
                    </div>
                </div>
            </div>
            <div class="guest-list">
                <!-- Guest table/list -->
            </div>
        </div>
    </div>
</div>
```

**Features**:
- Visual PAX counters with icons
- Primary contact display
- Quick add guest button
- Expandable guest details

### 3. Accommodation Panel
```html
<div class="col-lg-6">
    <div class="card panel-accommodation">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Accommodation</h5>
            <button class="btn btn-sm btn-success" data-action="add-accommodation">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
```

### 4. Services Panel
```html
<div class="col-lg-6">
    <div class="card panel-services">
        <div class="card-header">
            <h5 class="mb-0">Services</h5>
        </div>
        <div class="card-body">
            <div class="accordion" id="servicesAccordion">
                <!-- Grouped by service type -->
                <div class="accordion-item">
                    <h6 class="accordion-header">
                        <button class="accordion-button" type="button">
                            Activities ({{ count }})
                        </button>
                    </h6>
                    <div class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <!-- Service items table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 5. Prices & Transactions Panel
```html
<div class="col-12">
    <div class="card panel-financial">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#summary">Summary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#transactions">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#payment-status">Payment Status</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Tab panels -->
            </div>
        </div>
    </div>
</div>
```

## SCSS Structure

### Main Dashboard Styles
```scss
// resources/scss/pages/booking_deal_dashboard.scss

.booking-deal-dashboard {
    // Panel base styles
    .card {
        height: 100%;
        transition: box-shadow 0.3s;
        
        &:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    }
    
    // PAX counters
    .pax-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        font-size: 2rem;
        font-weight: bold;
        
        &.pax-total {
            background-color: var(--bs-primary);
            color: white;
        }
        
        &.pax-adults {
            background-color: var(--bs-info);
            color: white;
        }
        
        &.pax-kids {
            background-color: var(--bs-warning);
            color: var(--bs-dark);
        }
        
        .count {
            display: block;
            line-height: 1;
        }
        
        small {
            font-size: 0.75rem;
            font-weight: normal;
        }
    }
    
    // Service groups
    .service-group {
        margin-bottom: 1rem;
        
        &-header {
            cursor: pointer;
            padding: 0.75rem;
            background-color: var(--bs-gray-100);
            border-radius: 0.25rem;
            
            &:hover {
                background-color: var(--bs-gray-200);
            }
            
            .badge {
                float: right;
            }
        }
        
        &-body {
            padding: 0.75rem;
        }
    }
    
    // Financial summary
    .financial-summary {
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--bs-gray-300);
            
            &:last-child {
                border-bottom: none;
                font-weight: bold;
                font-size: 1.25rem;
            }
        }
    }
    
    // Status badges
    .status-badge {
        &.status-confirmed {
            background-color: var(--bs-success);
            color: white;
        }
        
        &.status-pending {
            background-color: var(--bs-warning);
            color: var(--bs-dark);
        }
        
        &.status-cancelled {
            background-color: var(--bs-danger);
            color: white;
        }
    }
}

// Responsive adjustments
@media (max-width: 991px) {
    .booking-deal-dashboard {
        .pax-counter {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
}
```

## JavaScript Components

### Panel Manager
```javascript
// resources/js/booking-deal-dashboard.js

class BookingDealDashboard {
    constructor(dealId) {
        this.dealId = dealId;
        this.panels = {
            dealInfo: new DealInfoPanel(),
            guests: new GuestsPanel(),
            accommodation: new AccommodationPanel(),
            services: new ServicesPanel(),
            financial: new FinancialPanel()
        };
        this.init();
    }
    
    init() {
        this.loadDealData();
        this.bindEvents();
        this.initializeTooltips();
    }
    
    loadDealData() {
        fetch(`/api/booking/deal/${this.dealId}`)
            .then(response => response.json())
            .then(data => {
                Object.values(this.panels).forEach(panel => {
                    panel.update(data);
                });
            });
    }
    
    bindEvents() {
        // Global event listeners
        document.addEventListener('deal:updated', () => this.loadDealData());
    }
}
```

## API Endpoints

### Required Endpoints
```php
// API Routes (routes/api.php or web.php with api prefix)

Route::prefix('api/booking/deal')->group(function () {
    Route::get('{deal}', [BookingDealController::class, 'apiShow']);
    Route::get('{deal}/guests', [BookingDealController::class, 'apiGuests']);
    Route::get('{deal}/accommodation', [BookingDealController::class, 'apiAccommodation']);
    Route::get('{deal}/services', [BookingDealController::class, 'apiServices']);
    Route::get('{deal}/transactions', [BookingDealController::class, 'apiTransactions']);
    Route::post('{deal}/transaction', [BookingDealController::class, 'apiAddTransaction']);
    Route::put('{deal}/status', [BookingDealController::class, 'apiUpdateStatus']);
});
```

### Controller Methods
```php
public function apiShow(Deal $deal)
{
    $deal->load(['contact', 'owner', 'booking_items', 'transactions']);
    
    return response()->json([
        'deal' => $deal,
        'summary' => [
            'total_amount' => $deal->total_amount,
            'paid_amount' => $deal->transactions->where('amount', '>', 0)->sum('amount'),
            'balance' => $deal->total_amount - $deal->transactions->where('amount', '>', 0)->sum('amount'),
        ],
        'stats' => [
            'accommodation_count' => $deal->booking_items->where('type', 'accommodation')->count(),
            'service_count' => $deal->booking_items->where('type', '!=', 'accommodation')->count(),
        ]
    ]);
}
```

## Performance Optimizations

1. **Eager Loading**: Always load relationships to prevent N+1 queries
2. **Caching**: Cache deal data for 5 minutes
3. **Lazy Loading**: Load panel content via AJAX on demand
4. **Debouncing**: Debounce search and filter inputs
5. **Pagination**: Paginate long lists (transactions, services)

## Browser Support
- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Android)

## Accessibility
- ARIA labels on all interactive elements
- Keyboard navigation support
- Screen reader compatible
- WCAG 2.1 AA compliance