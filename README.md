# ERP Booking Deal Dashboard

A modular Laravel dashboard component for displaying comprehensive booking deal information in a clean, organized interface.

## Overview

This repository contains a standalone implementation of the Booking Deal Dashboard feature for the xWander ERP system. The dashboard provides a comprehensive view of booking deals including accommodations, services, guest information, and financial summaries.

## Features

- **Deal Overview Panel**: Key booking information at a glance
- **Guest Information Panel**: Detailed guest and contact details
- **Accommodations Panel**: All accommodation bookings with vendor info
- **Services Panel**: Additional services grouped by type
- **Financial Summary Panel**: Transaction history and balance information
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

## Repository Structure

```
erp-booking-deal-dashboard/
├── src/                           # Source code
│   ├── views/                     # Blade template files
│   │   ├── dashboard.blade.php    # Main dashboard layout
│   │   ├── deal-info-panel.blade.php
│   │   ├── guests-panel.blade.php
│   │   ├── accommodation-panel.blade.php
│   │   ├── services-panel.blade.php
│   │   └── financial-panel.blade.php
│   ├── scss/                      # Styles
│   │   └── booking_deal_dashboard.scss
│   └── js/                        # JavaScript (if needed)
├── docs/                          # Documentation
│   ├── 01_project_overview.md
│   ├── 02_system_architecture.md
│   ├── 03_implementation_guide.md
│   ├── 04_testing_guide.md
│   ├── dashboard_technical_specs.md
│   └── LARAVEL_INTEGRATION_GUIDE.md
├── examples/                      # Integration examples
│   ├── routes/
│   └── controllers/
└── tests/                         # Test files
```

## Quick Start

### Prerequisites

- Laravel application (8.x or higher)
- PHP 8.2+
- Node.js and npm
- Composer

### Installation

1. Clone this repository:
```bash
git clone https://github.com/accoladians/erp-booking-deal-dashboard.git
cd erp-booking-deal-dashboard
```

2. Follow the integration guide:
```bash
cat docs/LARAVEL_INTEGRATION_GUIDE.md
```

### Basic Integration

1. Copy view files to your Laravel application:
```bash
sudo cp -r src/views/* /path/to/laravel/resources/views/guide/booking/deal/dashboard/
```

2. Copy SCSS file and compile:
```bash
sudo cp src/scss/booking_deal_dashboard.scss /path/to/laravel/resources/scss/pages/
npm run dev
```

3. Add route to `routes/web.php`:
```php
Route::get('booking/deal/id/{deal}/dashboard', [BookingDealController::class, 'dashboard'])
    ->name('booking.deal.dashboard');
```

4. Add dashboard method to your controller (see `examples/controllers/DashboardMethod.php`)

## Documentation

- [Project Overview](docs/01_project_overview.md) - High-level project description
- [System Architecture](docs/02_system_architecture.md) - Technical architecture details
- [Implementation Guide](docs/03_implementation_guide.md) - Step-by-step implementation
- [Testing Guide](docs/04_testing_guide.md) - Testing procedures and examples
- [Laravel Integration Guide](docs/LARAVEL_INTEGRATION_GUIDE.md) - Detailed Laravel integration steps
- [Technical Specifications](docs/dashboard_technical_specs.md) - Complete technical specs

## Development

### Making Changes

1. Create a feature branch:
```bash
git checkout -b feature/your-feature-name
```

2. Make your changes to the files in `src/`

3. Test in your local Laravel environment

4. Commit and push:
```bash
git add .
git commit -m "Description of changes"
git push origin feature/your-feature-name
```

5. Create a pull request on GitHub

### Deployment to Production

After changes are tested and approved:

1. Merge to main branch on GitHub
2. Pull changes to GitLab repository
3. Deploy through standard GitLab CI/CD pipeline

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is proprietary software owned by Accoladians/xWander.

## Support

For questions or issues:
- Create an issue on GitHub
- Contact the development team
- Check the documentation in `/docs`