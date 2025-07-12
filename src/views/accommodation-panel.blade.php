{{-- Accommodation Panel Component --}}
<div class="card panel-accommodation h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-bed me-2"></i>Accommodation
            <span class="badge bg-secondary ms-2">{{ $accommodations->count() }}</span>
        </h5>
        <button class="btn btn-sm btn-success" data-action="add-accommodation">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>
    <div class="card-body">
        @if($accommodations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accommodations as $accommodation)
                            <tr>
                                <td>
                                    <i class="fas fa-calendar-check text-success me-1"></i>
                                    {{ $accommodation->from->format('M d') }}
                                </td>
                                <td>
                                    <i class="fas fa-calendar-times text-danger me-1"></i>
                                    {{ $accommodation->to->format('M d') }}
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $accommodation->title ?: $accommodation->name }}</strong>
                                        @if($accommodation->source)
                                            <br>
                                            <small class="text-muted">
                                                {{ $accommodation->source->name }}
                                                @if($accommodation->vendor)
                                                    - {{ $accommodation->vendor->name }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $accommodation->booking_status->getColor() }}">
                                        {{ $accommodation->booking_status->value }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('booking.accommodation.show', $accommodation) }}" 
                                           class="btn btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-danger" 
                                                data-action="remove-accommodation" 
                                                data-id="{{ $accommodation->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Summary --}}
            <div class="mt-3 p-3 bg-light rounded">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Total Nights</small>
                        <div class="h5 mb-0">
                            {{ $accommodations->sum(function($a) { 
                                return $a->from->diffInDays($a->to); 
                            }) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Properties</small>
                        <div class="h5 mb-0">
                            {{ $accommodations->pluck('source.name')->filter()->unique()->count() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bed text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">No accommodation bookings added yet.</p>
                <button class="btn btn-primary" data-action="add-accommodation">
                    <i class="fas fa-plus me-2"></i>Add Accommodation
                </button>
            </div>
        @endif
        
        {{-- Accommodation Notes --}}
        @if($deal->arrival_checklist)
            <div class="mt-3">
                <h6 class="text-muted">Arrival Checklist</h6>
                <div class="small">{{ $deal->arrival_checklist }}</div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add accommodation
    document.querySelectorAll('[data-action="add-accommodation"]').forEach(btn => {
        btn.addEventListener('click', function() {
            // Redirect to accommodation booking page or open modal
            window.location.href = '{{ route("booking.accommodation.new") }}?deal_id={{ $deal->id }}';
        });
    });
    
    // Remove accommodation
    document.querySelectorAll('[data-action="remove-accommodation"]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this accommodation?')) {
                const accommodationId = this.dataset.id;
                // Implement removal logic
                console.log('Remove accommodation:', accommodationId);
            }
        });
    });
});
</script>
@endpush