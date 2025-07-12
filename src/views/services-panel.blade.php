{{-- Services Panel Component --}}
<div class="card panel-services h-100">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-concierge-bell me-2"></i>Services
            <span class="badge bg-secondary ms-2">{{ $deal->booking_items->where('type', '!=', 'accommodation')->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($services->count() > 0)
            <div class="accordion" id="servicesAccordion">
                @foreach($services as $type => $items)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ Str::slug($type) }}">
                            <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse-{{ Str::slug($type) }}" 
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="fas {{ $type == 'activity' ? 'fa-hiking' : 'fa-cube' }} me-2"></i>
                                {{ ucfirst($type) }}
                                <span class="badge bg-primary ms-2">{{ $items->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapse-{{ Str::slug($type) }}" 
                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                             data-bs-parent="#servicesAccordion">
                            <div class="accordion-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Vendor</th>
                                            <th>Status</th>
                                            <th>PAX</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{ $item->from->format('M d') }}</td>
                                                <td>
                                                    <div>
                                                        {{ $item->title ?: $item->name }}
                                                        @if($item->from->format('H:i') != '00:00')
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock"></i> 
                                                                {{ $item->from->format('H:i') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->vendor)
                                                        <small>{{ $item->vendor->name }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $item->booking_status->getColor() }} badge-sm">
                                                        {{ $item->booking_status->value }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->pax_total ?? '-' }}</td>
                                                <td class="text-end">
                                                    @if($item->payment_total)
                                                        {{ format_price($item->payment_total) }} EUR
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Services Summary --}}
            <div class="mt-3 p-3 bg-light rounded">
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted d-block">Total Services</small>
                        <div class="h5 mb-0">{{ $deal->booking_items->where('type', '!=', 'accommodation')->count() }}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Confirmed</small>
                        <div class="h5 mb-0 text-success">
                            {{ $deal->booking_items->where('type', '!=', 'accommodation')
                                ->where('booking_status', 'confirmed')->count() }}
                        </div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Pending</small>
                        <div class="h5 mb-0 text-warning">
                            {{ $deal->booking_items->where('type', '!=', 'accommodation')
                                ->where('booking_status', 'pending')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-concierge-bell text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">No services added to this deal yet.</p>
                <div class="btn-group">
                    <button class="btn btn-primary" data-action="add-activity">
                        <i class="fas fa-hiking me-2"></i>Add Activity
                    </button>
                    <button class="btn btn-outline-primary" data-action="add-service">
                        <i class="fas fa-plus me-2"></i>Add Other Service
                    </button>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Action buttons --}}
    @if($services->count() > 0)
        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between">
                <button class="btn btn-sm btn-outline-primary" data-action="add-activity">
                    <i class="fas fa-plus me-1"></i> Add Activity
                </button>
                <button class="btn btn-sm btn-outline-secondary" data-action="export-services">
                    <i class="fas fa-download me-1"></i> Export List
                </button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add activity
    document.querySelectorAll('[data-action="add-activity"]').forEach(btn => {
        btn.addEventListener('click', function() {
            window.location.href = '{{ route("booking.activity.new") }}?deal_id={{ $deal->id }}';
        });
    });
    
    // Add other service
    const addServiceBtn = document.querySelector('[data-action="add-service"]');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function() {
            // Implement add service logic
            alert('Add service functionality to be implemented');
        });
    }
    
    // Export services
    const exportBtn = document.querySelector('[data-action="export-services"]');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Implement export logic
            alert('Export functionality to be implemented');
        });
    }
});
</script>
@endpush