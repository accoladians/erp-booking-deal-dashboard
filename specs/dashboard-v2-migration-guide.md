# Dashboard v2.0 Migration Guide

## Overview
This guide provides step-by-step instructions for migrating from Dashboard v1.0 to v2.0.

## Pre-Migration Checklist

### System Requirements
- [ ] Laravel 10.x or higher
- [ ] PHP 8.2+
- [ ] Redis server installed
- [ ] Node.js 18+ for build tools
- [ ] WebSocket server capability

### Database Preparations
```sql
-- Backup current data
mysqldump -u xwander_x -p xwander_x > backup_before_v2.sql

-- Add new indexes
CREATE INDEX idx_deal_health ON booking_deal(id, payment_status, vendor_payment_status);
CREATE INDEX idx_vendor_confirmations ON booking_item(booking_deal_id, vendor_id, status);
CREATE INDEX idx_timeline_dates ON booking_item(booking_deal_id, start_date, end_date);

-- Create materialized views
CREATE MATERIALIZED VIEW mv_deal_health_metrics AS
SELECT ... (see technical spec);
```

## Migration Steps

### Step 1: Install Dependencies
```bash
# Backend dependencies
composer require pusher/pusher-php-server
composer require predis/predis

# Frontend dependencies
npm install --save-dev laravel-echo pusher-js
npm install chart.js d3 alpinejs
```

### Step 2: Configuration
```php
// config/broadcasting.php
'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ],
    ],
],

// .env additions
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=eu
```

### Step 3: Deploy New Code
```bash
# Deploy v2 code
cd /var/www/dev.x.xwander.fi/http
git pull origin feature/dashboard-v2

# Run migrations
sudo php artisan migrate

# Clear caches
sudo php artisan cache:clear
sudo php artisan route:clear
sudo php artisan view:clear
sudo php artisan config:clear

# Build assets
sudo npm run build
```

### Step 4: Enable Real-time Features
```bash
# Start queue workers for broadcasting
sudo php artisan queue:work --queue=broadcasts

# Start WebSocket server (if using Laravel WebSockets)
sudo php artisan websockets:serve
```

## Data Migration

### Cache Warming
```bash
# Warm dashboard caches for active deals
sudo php artisan dashboard:warm-cache --days=30
```

### User Permissions
```sql
-- Grant new permissions for v2 features
INSERT INTO permissions (name, guard_name) VALUES
('dashboard.v2.view', 'web'),
('dashboard.v2.actions', 'web'),
('dashboard.v2.admin', 'web');

-- Assign to roles
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r
WHERE p.name LIKE 'dashboard.v2.%' AND r.name IN ('admin', 'agent');
```

## Testing Migration

### Smoke Tests
1. Access v2 dashboard: `/booking/deal-dashboard2/id/16790622275`
2. Verify real-time updates work
3. Test all major components load
4. Confirm API endpoints respond
5. Check WebSocket connections

### Performance Tests
```bash
# Load test the new dashboard
ab -n 1000 -c 10 https://dev.erp.xwander.fi/booking/deal-dashboard2/id/16790622275

# Monitor Redis performance
redis-cli monitor

# Check Laravel Horizon dashboard
php artisan horizon
```

## Rollback Plan

### Quick Rollback
```bash
# Revert to v1 routes
sudo php artisan down --message="Reverting to v1" --retry=60

# Restore previous code
git checkout v1.0.0

# Clear caches
sudo php artisan cache:clear
sudo php artisan route:clear

# Bring back online
sudo php artisan up
```

### Database Rollback
```sql
-- Remove v2 indexes if causing issues
DROP INDEX idx_deal_health;
DROP INDEX idx_vendor_confirmations;
DROP INDEX idx_timeline_dates;

-- Drop materialized views
DROP MATERIALIZED VIEW mv_deal_health_metrics;
```

## User Training

### Key Changes for Users
1. **New URL**: `/booking/deal-dashboard2/` instead of `/booking/deal-dashboard/`
2. **Real-time Updates**: No need to refresh
3. **New Features**: AI insights, predictions, bulk actions
4. **Mobile Optimized**: Better mobile experience

### Training Resources
- Video walkthrough: (to be created)
- User manual: `/docs/dashboard-v2-manual.pdf`
- Quick reference card: `/docs/dashboard-v2-quick-ref.pdf`

## Monitoring Post-Migration

### Key Metrics to Monitor
```bash
# Application logs
tail -f storage/logs/laravel.log | grep dashboard2

# WebSocket connections
php artisan websockets:statistics

# Redis memory usage
redis-cli info memory

# Queue performance
php artisan queue:monitor
```

### Alert Thresholds
- Page load time > 2 seconds
- WebSocket disconnect rate > 5%
- Redis memory usage > 80%
- Queue backlog > 1000 jobs

## FAQ

### Q: Can I still use v1?
A: Yes, v1 remains at `/booking/deal-dashboard/` during transition period.

### Q: What if real-time updates don't work?
A: The dashboard falls back to polling every 30 seconds automatically.

### Q: How do I report issues?
A: Use the in-dashboard feedback button or email support@xwander.fi

### Q: When will v1 be deprecated?
A: 90 days after v2 reaches 80% adoption rate.

---

*This migration guide ensures smooth transition from v1 to v2*