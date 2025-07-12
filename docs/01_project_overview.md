# Booking Deal Dashboard UI Redesign - Project Overview

## Project Goal
Redesign the existing Laravel-based booking deal interface at `https://dev.erp.xwander.fi/booking/deal/id/{id}` from a single-page form layout to a modern, modular dashboard with distinct panels for better organization and user experience.

## Current System Analysis

### Technology Stack
- **Backend**: Laravel (PHP) - Located at `/var/www/dev.x.xwander.fi/revisions/release-dev-{version}/`
- **Frontend**: Blade templates, Bootstrap 5, jQuery
- **Database**: MySQL/MariaDB
- **External Integration**: HubSpot CRM API
- **Authentication**: HTTP Basic Auth with IP whitelist

### Current UI Issues
1. All information displayed in a single vertical form
2. Poor visual hierarchy and information grouping
3. Limited overview of deal components
4. Difficult to navigate between sections
5. Not optimized for large screens

## Proposed Solution

### Modular Dashboard Design
Transform the interface into a responsive grid-based dashboard with 5 main panels:

1. **Deal Info Panel** - Core deal information
2. **Guests Panel** - Guest counts and contact details
3. **Accommodation Panel** - Accommodation bookings table
4. **Services Panel** - Activities and other services
5. **Prices & Transactions Panel** - Financial overview and history

### Key Benefits
- Better information organization
- Improved visual hierarchy
- Quick access to all deal aspects
- Responsive design for all screen sizes
- Enhanced user experience

## Implementation Approach

### Phase 1: Infrastructure Setup
- Configure server access (IP whitelist)
- Create test routes and pages
- Verify deployment process

### Phase 2: Dashboard Development
- Create modular Blade components
- Implement responsive grid layout
- Add AJAX data loading
- Style with custom SCSS

### Phase 3: Integration
- Connect to existing data models
- Maintain backward compatibility
- Implement gradual rollout

## Success Criteria
1. All existing data accessible in new format
2. Page load time under 2 seconds
3. Mobile responsive design
4. No disruption to existing workflows
5. Positive user feedback

## Timeline Estimate
- Phase 1: 1 day (setup and testing)
- Phase 2: 3-4 days (development)
- Phase 3: 2 days (integration and testing)
- Total: ~1 week for MVP