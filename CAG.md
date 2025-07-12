# Context Augmented Generation (CAG) - ERP Booking Deal Dashboard

## Project Overview
Building an advanced booking deal dashboard for xWander ERP system. Currently at v1.0.0 (basic static panels), developing v2.0.0 with real-time data and advanced features.

## Key URLs
- **Production Dashboard v1**: https://dev.erp.xwander.fi/booking/deal-dashboard/id/{deal}
- **Development Dashboard v2**: https://dev.erp.xwander.fi/booking/deal-dashboard2/id/{deal} (planned)
- **GitHub Repository**: https://github.com/accoladians/erp-booking-deal-dashboard
- **Wiki Documentation**: https://github.com/accoladians/erp-booking-deal-dashboard/wiki
- **Example Deal**: 16790622275

## System Context

### Laravel Application
- **Root**: `/var/www/dev.x.xwander.fi/http` → `revisions/release-dev-*`
- **Controllers**: `app/Http/Controllers/Booking/DealController.php`
- **Views**: `resources/views/guide/booking/deal/dashboard*/`
- **Routes**: `routes/web.php` (lines 238-245 for v1)
- **SCSS**: `resources/scss/pages/`

### Key Database Tables
```sql
booking_deal (81 records) -- HubSpot Deal ID as PK
booking_item (VIEW) -- Combines activities + accommodations
booking_activity (225) -- Tours and experiences
booking_accommodation (340) -- Hotels and lodging
vendor (50+) -- Service providers
account_transaction -- Financial tracking
hs_contact (74) -- Customer data
```

### Integration Points
1. **HubSpot CRM** (Primary)
   - Deals, Contacts, Services (custom object 0-162)
   - Access Token: See /var/www/.env HUBSPOT_ACCESS_TOKEN
   - Webhook processing via MCP

2. **Bokun** (European activities)
   - V1 + V2 APIs
   - Real-time availability
   - Vendor ID mapping

3. **FareHarbor** (North American tours)
   - API integration
   - Booking management

4. **Financial APIs**
   - Revolut, OP Corporate, PayPal
   - Multi-currency support

## Dashboard v2 Vision

### Core Innovations
1. **Real-time Data Streaming**
   - WebSocket connections for live updates
   - AJAX panel loading with caching
   - Progressive data enhancement

2. **Intelligent Analytics**
   - Revenue forecasting
   - Vendor performance scoring
   - Customer behavior patterns
   - Automated anomaly detection

3. **Action-Oriented Interface**
   - One-click operations
   - Bulk actions support
   - Smart suggestions
   - Workflow automation

4. **360° Deal View**
   - Timeline visualization
   - Communication history
   - Financial reconciliation
   - Vendor status matrix

## Technical Architecture

### Frontend Stack
- **Framework**: Alpine.js + Livewire (for real-time)
- **Charts**: Chart.js or ApexCharts
- **DataTables**: Enhanced with server-side processing
- **UI**: Bootstrap 5 + custom components

### Backend Patterns
- **Repository Pattern** for data access
- **Service Classes** for business logic
- **Event Broadcasting** for real-time updates
- **Job Queues** for heavy operations

### Caching Strategy
- **Redis** for real-time data (5-min TTL)
- **Database Views** for complex queries
- **Eager Loading** for relationships
- **API Response Caching**

## Development Commands
```bash
# Always use sudo on dev server
cd /var/www/dev.x.xwander.fi/http
sudo php artisan route:clear view:clear config:clear
sudo npm run build
sudo php artisan route:list | grep dashboard
```

## Context Library References
- **Complete System Overview**: @/var/www/docs/context-library/applications/erp-system-detailed.md
- **Database Schema**: @/var/www/erp-booking-deal-dashboard/wiki/database-schema-map.md
- **Laravel Patterns**: @/var/www/erp-booking-deal-dashboard/wiki/Laravel-ERP-Patterns.md
- **Recovery Guide**: @/var/www/docs/context-library/applications/booking-dashboard-recovery.md

## Next Session Checklist
1. Read this CAG.md first
2. Check current route implementation
3. Review specs/ directory for v2 plans
4. Continue from latest todo list
5. Update context library as you work

## Current Todo Status
- [x] Tag v1.0.0
- [x] Create CAG.md
- [ ] Design dashboard v2 concept
- [ ] Create comprehensive specs
- [ ] Implement /booking/deal-dashboard2/ route
- [ ] Build real-time components
- [ ] Integrate advanced analytics

## Key Insights for v2
1. **HubSpot is Central** - All operations sync through HubSpot
2. **Vendors are Complex** - Multi-system integration needed
3. **Financial Data is Rich** - Untapped analytics potential
4. **Real-time is Expected** - Users need live updates
5. **Mobile is Critical** - 40% usage on mobile devices

---
*Last updated: 2025-07-12*
*This CAG provides essential context for AI assistants working on the project*