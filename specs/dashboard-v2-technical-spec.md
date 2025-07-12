# Dashboard v2.0 Technical Specification

## Architecture Overview

### URL Structure
- **Primary Route**: `/booking/deal-dashboard2/id/{deal}`
- **API Base**: `/api/v2/dashboard/{deal}/`
- **WebSocket**: `wss://dev.erp.xwander.fi/dashboard`

### Technology Stack
- **Backend**: Laravel 10.x + PHP 8.2
- **Real-time**: Laravel Echo + Pusher/Redis
- **Frontend**: Alpine.js 3.x + Livewire 3.x
- **Visualization**: Chart.js 4.x + D3.js 7.x
- **CSS**: Bootstrap 5 + Custom SCSS
- **Build**: Vite 5.x

## Component Architecture

### 1. Route Implementation
```php
// routes/web.php
Route::prefix('booking/deal-dashboard2')->group(function () {
    Route::get('id/{deal}', [DealDashboardV2Controller::class, 'show'])
        ->name('booking.deal.dashboard.v2');
    
    // API endpoints
    Route::prefix('api')->group(function () {
        Route::get('{deal}/health-score', [DashboardApiController::class, 'healthScore']);
        Route::get('{deal}/timeline', [DashboardApiController::class, 'timeline']);
        Route::get('{deal}/financial', [DashboardApiController::class, 'financial']);
        Route::get('{deal}/vendors', [DashboardApiController::class, 'vendors']);
        Route::get('{deal}/communications', [DashboardApiController::class, 'communications']);
        Route::get('{deal}/predictions', [DashboardApiController::class, 'predictions']);
    });
});

// WebSocket channels
Broadcast::channel('dashboard.{dealId}', function ($user, $dealId) {
    return $user->canViewDeal($dealId);
});
```

### 2. Controller Structure
```php
// app/Http/Controllers/Booking/DealDashboardV2Controller.php
class DealDashboardV2Controller extends Controller
{
    public function show(Deal $deal)
    {
        $this->authorize('view', $deal);
        
        // Minimal initial data for fast load
        $initialData = [
            'deal' => $deal->only(['id', 'name', 'arrival_date', 'departure_date']),
            'user' => auth()->user(),
            'permissions' => $this->getUserPermissions($deal),
        ];
        
        return view('guide.booking.deal.dashboard2.index', $initialData);
    }
}
```

### 3. Service Layer Architecture
```php
// app/Services/Dashboard/
├── DealHealthService.php       // AI-powered health scoring
├── TimelineService.php         // Interactive timeline data
├── FinancialAnalyticsService.php // Financial predictions
├── VendorMatrixService.php     // Vendor status management
├── CommunicationAIService.php  // Smart message generation
└── PredictiveInsightsService.php // ML-based predictions
```

### 4. Frontend Component Structure
```
resources/views/guide/booking/deal/dashboard2/
├── index.blade.php              // Main layout with loading skeleton
├── components/
│   ├── health-monitor.blade.php
│   ├── timeline-gantt.blade.php
│   ├── financial-intelligence.blade.php
│   ├── vendor-matrix.blade.php
│   ├── ai-communications.blade.php
│   ├── activity-feed.blade.php
│   └── predictive-insights.blade.php
└── partials/
    ├── loading-skeleton.blade.php
    └── error-boundary.blade.php
```

## Real-time Data Architecture

### WebSocket Events
```javascript
// Broadcasting events
class DealUpdated implements ShouldBroadcast
{
    public function broadcastOn() {
        return new Channel('dashboard.' . $this->deal->id);
    }
    
    public function broadcastAs() {
        return 'deal.updated';
    }
    
    public function broadcastWith() {
        return [
            'section' => $this->section,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
```

### Frontend WebSocket Handler
```javascript
// resources/js/dashboard-v2.js
class DashboardV2 {
    constructor(dealId) {
        this.dealId = dealId;
        this.channel = Echo.channel(`dashboard.${dealId}`);
        this.setupListeners();
    }
    
    setupListeners() {
        this.channel.listen('.deal.updated', (e) => {
            this.updateSection(e.section, e.data);
        });
        
        this.channel.listen('.payment.received', (e) => {
            this.showNotification('Payment received', e.amount);
            this.refreshFinancial();
        });
        
        this.channel.listen('.vendor.confirmed', (e) => {
            this.updateVendorMatrix(e.vendorId, e.status);
        });
    }
}
```

## API Specifications

### Health Score Endpoint
```
GET /api/v2/dashboard/{dealId}/health-score

Response:
{
    "score": 87,
    "factors": {
        "payment": { "score": 95, "status": "healthy" },
        "vendors": { "score": 75, "issues": ["2 pending confirmations"] },
        "documents": { "score": 60, "issues": ["Missing passports"] },
        "communication": { "score": 90, "status": "on_track" }
    },
    "insights": [
        {
            "type": "risk",
            "severity": "medium",
            "message": "High cancellation risk based on profile",
            "action": "recommend_followup"
        }
    ],
    "recommendations": [
        "Send payment reminder in 2 days",
        "Confirm glacier tour by tomorrow"
    ]
}
```

### Timeline Data Structure
```
GET /api/v2/dashboard/{dealId}/timeline

Response:
{
    "timeline": {
        "start": "2024-03-15",
        "end": "2024-03-18",
        "items": [
            {
                "id": "acc_001",
                "type": "accommodation",
                "name": "Hotel Borg",
                "start": "2024-03-15T15:00:00Z",
                "end": "2024-03-18T11:00:00Z",
                "status": "confirmed",
                "vendor_id": 123
            },
            {
                "id": "act_001",
                "type": "activity",
                "name": "Glacier Hike",
                "start": "2024-03-15T09:00:00Z",
                "duration": 480,
                "status": "pending",
                "conflicts": []
            }
        ]
    },
    "suggestions": [
        "Add buffer time between activities",
        "Consider weather forecast for outdoor activities"
    ]
}
```

## Database Optimizations

### New Indexes
```sql
-- Optimize health score calculations
CREATE INDEX idx_deal_health ON booking_deal(id, payment_status, vendor_payment_status);
CREATE INDEX idx_vendor_confirmations ON booking_item(booking_deal_id, vendor_id, status);

-- Speed up timeline queries
CREATE INDEX idx_timeline_dates ON booking_item(booking_deal_id, start_date, end_date);

-- Financial analytics
CREATE INDEX idx_financial_analysis ON account_transaction_hs_association(hs_object_id, type)
    WHERE type = 'Deal';
```

### Materialized Views
```sql
-- Deal health metrics
CREATE MATERIALIZED VIEW mv_deal_health_metrics AS
SELECT 
    d.id,
    d.payment_status,
    COUNT(DISTINCT bi.id) as total_items,
    COUNT(DISTINCT CASE WHEN bi.status = 'confirmed' THEN bi.id END) as confirmed_items,
    COUNT(DISTINCT t.id) as transaction_count,
    COALESCE(SUM(t.amount), 0) as total_paid
FROM booking_deal d
LEFT JOIN booking_item bi ON d.id = bi.booking_deal_id
LEFT JOIN account_transaction_hs_association a ON d.id = a.hs_object_id
LEFT JOIN account_transaction t ON a.transaction_id = t.id
GROUP BY d.id;

-- Refresh every 5 minutes
CREATE EVENT refresh_deal_health_metrics
ON SCHEDULE EVERY 5 MINUTE
DO REFRESH MATERIALIZED VIEW mv_deal_health_metrics;
```

## Caching Strategy

### Redis Cache Keys
```
dashboard:v2:deal:{dealId}:health_score     TTL: 5 min
dashboard:v2:deal:{dealId}:timeline         TTL: 2 min
dashboard:v2:deal:{dealId}:financial        TTL: 5 min
dashboard:v2:deal:{dealId}:vendors          TTL: 2 min
dashboard:v2:deal:{dealId}:predictions      TTL: 15 min
```

### Cache Warming
```php
// app/Jobs/WarmDashboardCache.php
class WarmDashboardCache implements ShouldQueue
{
    public function handle()
    {
        $activeDeals = Deal::whereIn('dealstage', Deal::ACTIVE_STAGES)
            ->where('arrival_date', '>=', now()->subDays(7))
            ->where('arrival_date', '<=', now()->addDays(30))
            ->get();
            
        foreach ($activeDeals as $deal) {
            dispatch(new CacheDealDashboard($deal->id));
        }
    }
}
```

## Performance Requirements

### Loading Performance
- **Time to First Byte**: < 200ms
- **First Contentful Paint**: < 500ms
- **Time to Interactive**: < 1.5s
- **Largest Contentful Paint**: < 2s

### Runtime Performance
- **API Response Time**: < 100ms (cached), < 500ms (uncached)
- **WebSocket Latency**: < 50ms
- **UI Update Time**: < 16ms (60 FPS)
- **Memory Usage**: < 50MB

### Scalability
- Support 1000+ concurrent dashboard users
- Handle 10,000+ WebSocket connections
- Process 100+ updates per second
- Cache hit ratio > 90%

## Security Implementation

### Authorization
```php
// app/Policies/DashboardPolicy.php
class DashboardPolicy
{
    public function viewDashboard(User $user, Deal $deal)
    {
        return $user->hasRole(['admin', 'agent']) ||
               $user->id === $deal->owner_id ||
               $user->deals->contains($deal->id);
    }
    
    public function performActions(User $user, Deal $deal)
    {
        return $user->hasRole('admin') ||
               ($user->id === $deal->owner_id && $user->hasPermission('deal.edit'));
    }
}
```

### API Security
- Rate limiting: 60 requests/minute per user
- API authentication via Sanctum
- WebSocket authentication via private channels
- Input validation on all endpoints
- XSS protection with CSP headers

## Testing Strategy

### Unit Tests
```php
// tests/Unit/Services/DealHealthServiceTest.php
class DealHealthServiceTest extends TestCase
{
    public function test_calculates_health_score_correctly()
    {
        $deal = Deal::factory()->create([
            'payment_status' => 'Deposit Paid',
            'vendor_payment_status' => 'Not Paid'
        ]);
        
        $service = new DealHealthService();
        $score = $service->calculateScore($deal);
        
        $this->assertBetween(70, 80, $score);
    }
}
```

### Integration Tests
- Test WebSocket broadcasting
- Test API endpoints with real data
- Test cache invalidation
- Test database view refreshing

### End-to-End Tests
```javascript
// tests/e2e/dashboard-v2.spec.js
test('dashboard loads and updates in real-time', async ({ page }) => {
    await page.goto('/booking/deal-dashboard2/id/16790622275');
    await expect(page.locator('.health-score')).toBeVisible();
    
    // Trigger payment update
    await page.evaluate(() => {
        window.Echo.channel('dashboard.16790622275')
            .broadcast('payment.received', { amount: 1000 });
    });
    
    // Verify update
    await expect(page.locator('.payment-notification')).toBeVisible();
});
```

## Deployment Checklist

### Pre-deployment
- [ ] Run test suite
- [ ] Check performance benchmarks
- [ ] Verify WebSocket configuration
- [ ] Test cache warming
- [ ] Review security headers

### Deployment
- [ ] Enable maintenance mode
- [ ] Deploy code
- [ ] Run migrations
- [ ] Create materialized views
- [ ] Clear all caches
- [ ] Warm caches
- [ ] Test WebSocket connections

### Post-deployment
- [ ] Monitor error logs
- [ ] Check performance metrics
- [ ] Verify real-time updates
- [ ] Test critical paths
- [ ] Monitor cache hit rates

---

*This specification provides the complete technical blueprint for Dashboard v2.0 implementation*