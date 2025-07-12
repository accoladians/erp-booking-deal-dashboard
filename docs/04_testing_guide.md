# Testing Guide - Booking Deal Dashboard

## Testing Environment Setup

### Prerequisites
1. Access to dev.erp.xwander.fi
2. Valid test deal ID (e.g., 16790622275)
3. Browser developer tools
4. Network monitoring tools

### Test User Credentials
```
URL: https://dev.erp.xwander.fi
Username: [Will be provided separately]
Password: [Will be provided separately]
```

## Functional Testing Checklist

### 1. Initial Access Tests
- [ ] Hello world page loads at `/booking/newdealui/helloworld`
- [ ] Dashboard loads at `/booking/deal/id/{id}/dashboard`
- [ ] No JavaScript errors in console
- [ ] All CSS styles load correctly

### 2. Deal Info Panel Tests
- [ ] Deal information displays correctly
- [ ] Edit button redirects to edit page
- [ ] HubSpot link opens in new tab
- [ ] Guest view link works
- [ ] Expandable description works (if applicable)
- [ ] Dates display in correct format
- [ ] Stage badge shows correct color

### 3. Guests Panel Tests
- [ ] PAX counters show correct numbers
- [ ] Total = Adults + Kids
- [ ] Primary contact displays if exists
- [ ] Warning shown if no contact assigned
- [ ] Contact profile link works
- [ ] Additional guests list correctly
- [ ] Add guest button present

### 4. Accommodation Panel Tests
- [ ] Accommodations list in table format
- [ ] Check-in/out dates display correctly
- [ ] Property names show
- [ ] Status badges have correct colors
- [ ] View button opens accommodation details
- [ ] Add accommodation button works
- [ ] Empty state displays if no accommodations
- [ ] Summary calculations are correct

### 5. Services Panel Tests
- [ ] Services grouped by type
- [ ] Accordion functionality works
- [ ] Service counts in badges are accurate
- [ ] Table data displays correctly
- [ ] Status badges show
- [ ] Price formatting is correct
- [ ] Empty state shows if no services
- [ ] Add activity button present

### 6. Financial Panel Tests

#### Summary Tab
- [ ] Total deal value displays
- [ ] Paid amount calculates correctly
- [ ] Balance due is accurate
- [ ] Payment progress bar shows correct percentage
- [ ] Payment status badges display
- [ ] Action buttons are present

#### Transactions Tab
- [ ] Transaction list loads
- [ ] Sorting by date works
- [ ] Transaction links open in new tab
- [ ] Amounts show correct colors (green/red)
- [ ] Total calculation is accurate
- [ ] Empty state displays if no transactions

#### Payment Status Tab
- [ ] Customer payment status shows
- [ ] Vendor payment status displays
- [ ] Timeline renders correctly
- [ ] Timeline events in chronological order

## Responsive Design Testing

### Desktop (≥992px)
- [ ] Two-column layout for top panels
- [ ] Proper spacing between panels
- [ ] All content readable
- [ ] No horizontal scrolling

### Tablet (768px - 991px)
- [ ] Panels stack appropriately
- [ ] PAX counters resize
- [ ] Tables remain readable
- [ ] Navigation remains functional

### Mobile (<768px)
- [ ] Single column layout
- [ ] All panels stack vertically
- [ ] Touch interactions work
- [ ] Tables scroll horizontally if needed
- [ ] Buttons remain clickable

## Cross-Browser Testing

### Chrome/Edge (Latest)
- [ ] All features work
- [ ] No console errors
- [ ] Smooth animations

### Firefox (Latest)
- [ ] All features work
- [ ] CSS renders correctly
- [ ] JavaScript functions properly

### Safari (14+)
- [ ] All features work
- [ ] No webkit-specific issues
- [ ] Touch events work on iOS

## Performance Testing

### Page Load
- [ ] Initial load < 2 seconds
- [ ] All panels render within 3 seconds
- [ ] No visible layout shifts

### Interactions
- [ ] Panel switches < 100ms
- [ ] Accordion animations smooth
- [ ] No lag on hover effects

### Memory Usage
- [ ] No memory leaks after 10 minutes
- [ ] Browser remains responsive
- [ ] No excessive DOM nodes

## Accessibility Testing

### Keyboard Navigation
- [ ] Tab through all interactive elements
- [ ] Enter/Space activate buttons
- [ ] Escape closes modals
- [ ] Focus indicators visible

### Screen Reader
- [ ] All panels have proper headings
- [ ] Tables have headers
- [ ] Buttons have descriptive labels
- [ ] Status information announced

### Color Contrast
- [ ] Text meets WCAG AA standards
- [ ] Badges remain readable
- [ ] Links distinguishable

## Data Validation Testing

### Edge Cases
- [ ] Deal with no accommodations
- [ ] Deal with no services
- [ ] Deal with no transactions
- [ ] Deal with no contact
- [ ] Very long deal names
- [ ] Large number of services (50+)

### Data Accuracy
- [ ] Calculations match database
- [ ] Dates in correct timezone
- [ ] Currency formatting consistent
- [ ] Status badges match data

## Security Testing

### Authentication
- [ ] Requires login to access
- [ ] Session timeout works
- [ ] No data exposed in URLs

### Authorization
- [ ] User can only see their deals
- [ ] Role permissions respected
- [ ] No unauthorized API access

### XSS Prevention
- [ ] User input properly escaped
- [ ] No script injection possible
- [ ] HTML entities rendered safely

## Integration Testing

### HubSpot Integration
- [ ] Links to HubSpot work
- [ ] Data syncs correctly
- [ ] No API errors

### Database Queries
- [ ] Efficient queries (no N+1)
- [ ] Proper eager loading
- [ ] No slow queries

## Print Testing
- [ ] Print layout renders correctly
- [ ] Unnecessary elements hidden
- [ ] Page breaks appropriate
- [ ] All data visible

## Error Handling

### Network Errors
- [ ] Graceful handling of API failures
- [ ] User-friendly error messages
- [ ] Retry mechanisms work

### Invalid Data
- [ ] Missing data handled gracefully
- [ ] Null values don't break UI
- [ ] Proper fallbacks displayed

## Regression Testing

After any changes:
- [ ] Existing functionality still works
- [ ] No new console errors
- [ ] Performance not degraded
- [ ] All tests still pass

## User Acceptance Testing

### Stakeholder Review
- [ ] Layout meets expectations
- [ ] Information hierarchy correct
- [ ] Workflow improvements confirmed
- [ ] Feature completeness verified

### User Feedback
- [ ] Intuitive navigation
- [ ] Clear information presentation
- [ ] Improved over old interface
- [ ] No missing critical features

## Bug Report Template

When reporting issues:

```markdown
**Bug Description**: [Clear description]
**Steps to Reproduce**:
1. [Step 1]
2. [Step 2]

**Expected Result**: [What should happen]
**Actual Result**: [What actually happens]
**Browser/Device**: [Chrome 120 on Windows 11]
**Deal ID**: [Test deal ID]
**Screenshot**: [Attach if applicable]
**Console Errors**: [Copy any errors]
```

## Test Data

### Sample Deal IDs for Testing
- Standard deal: 16790622275
- Deal with many services: [TBD]
- Deal with no accommodations: [TBD]
- Deal with complex transactions: [TBD]

### Test Scenarios
1. **Happy Path**: Deal with all data present
2. **Minimal Deal**: Only required fields
3. **Complex Deal**: Multiple accommodations and services
4. **Edge Cases**: Unusual data combinations