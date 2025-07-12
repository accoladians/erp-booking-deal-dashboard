@extends('page.layout')

@section('content')
<div class="booking-deal-dashboard">
    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col">
            <div class="page-title-box">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $deal->name }}</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('booking.deal.list') }}">Deals</a></li>
                                <li class="breadcrumb-item active">{{ $deal->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('booking.deal.show', $deal) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit Mode
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dashboard Panels --}}
    <div class="row">
        {{-- Deal Info Panel --}}
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.deal-info-panel')
        </div>
        
        {{-- Guests Panel --}}
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.guests-panel')
        </div>
        
        {{-- Accommodation Panel --}}
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.accommodation-panel')
        </div>
        
        {{-- Services Panel --}}
        <div class="col-lg-6 mb-4">
            @include('guide.booking.deal.dashboard.services-panel')
        </div>
        
        {{-- Financial Panel --}}
        <div class="col-12 mb-4">
            @include('guide.booking.deal.dashboard.financial-panel')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
class BookingDealDashboard {
    constructor(dealId) {
        this.dealId = dealId;
        this.init();
    }
    
    init() {
        this.bindGlobalEvents();
        this.initializeTooltips();
        this.setupAutoRefresh();
    }
    
    bindGlobalEvents() {
        // Listen for deal updates
        document.addEventListener('deal:updated', () => {
            this.refreshDashboard();
        });
        
        // Handle panel refresh buttons
        document.querySelectorAll('[data-action="refresh-panel"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const panel = e.target.closest('.card');
                this.refreshPanel(panel);
            });
        });
    }
    
    initializeTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    setupAutoRefresh() {
        // Auto-refresh dashboard every 5 minutes
        setInterval(() => {
            this.refreshDashboard();
        }, 5 * 60 * 1000);
    }
    
    refreshDashboard() {
        // Implement dashboard refresh logic
        console.log('Refreshing dashboard...');
        // This would typically make an AJAX call to get updated data
    }
    
    refreshPanel(panel) {
        // Implement individual panel refresh
        const panelType = panel.classList[1]; // Get panel type from class
        console.log(`Refreshing ${panelType}...`);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new BookingDealDashboard({{ $deal->id }});
});
</script>
@endsection

@section('styles')
<style>
/* Print styles */
@media print {
    .btn-group,
    .page-header,
    .sidebar,
    .navbar {
        display: none !important;
    }
    
    .booking-deal-dashboard .card {
        break-inside: avoid;
        margin-bottom: 20px !important;
    }
    
    .col-lg-6 {
        width: 50% !important;
        float: left !important;
    }
}

/* Dashboard specific styles */
.booking-deal-dashboard .card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.booking-deal-dashboard .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Ensure equal height panels in rows */
.booking-deal-dashboard .row {
    display: flex;
    flex-wrap: wrap;
}

.booking-deal-dashboard .row > [class*='col-'] {
    display: flex;
    flex-direction: column;
}

.booking-deal-dashboard .card {
    flex: 1;
}
</style>
@endsection