{{-- Guests Panel Component --}}
<div class="card panel-guests h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Guests
        </h5>
        <button class="btn btn-sm btn-success" data-action="add-guest">
            <i class="fas fa-user-plus"></i> Add Guest
        </button>
    </div>
    <div class="card-body">
        {{-- PAX Counters --}}
        <div class="row text-center mb-4">
            <div class="col-4">
                <div class="pax-counter pax-total">
                    <div>
                        <span class="count">{{ $deal->pax_total }}</span>
                        <small class="d-block mt-1">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="pax-counter pax-adults">
                    <div>
                        <span class="count">{{ $deal->pax_adults }}</span>
                        <small class="d-block mt-1">Adults</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="pax-counter pax-kids">
                    <div>
                        <span class="count">{{ $deal->pax_kids }}</span>
                        <small class="d-block mt-1">Kids</small>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Primary Contact --}}
        <div class="guest-list">
            <h6 class="text-muted mb-3">Primary Contact</h6>
            @if($deal->contact)
                <div class="d-flex align-items-center p-3 bg-light rounded mb-3">
                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        {{ strtoupper(substr($deal->contact->first_name, 0, 1) . substr($deal->contact->last_name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                            @if($deal->contact->email)
                                <a href="mailto:{{ $deal->contact->email }}" class="ms-2">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                        </h6>
                        <div class="text-muted small">
                            @if($deal->contact->email)
                                <i class="fas fa-envelope me-1"></i> {{ $deal->contact->email }}<br>
                            @endif
                            @if($deal->contact->phone_number)
                                <i class="fas fa-phone me-1"></i> {{ $deal->contact->phone_number }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('contact.show', $deal->contact->hs_object_id) }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            View Profile
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No primary contact assigned to this deal.
                    <a href="#" class="alert-link ms-2" data-action="assign-contact">Assign Contact</a>
                </div>
            @endif
            
            {{-- Additional Guests --}}
            @if($deal->booking_items->pluck('contact')->filter()->unique('hs_object_id')->count() > 1)
                <h6 class="text-muted mb-3 mt-4">Additional Guests</h6>
                <div class="list-group">
                    @foreach($deal->booking_items->pluck('contact')->filter()->unique('hs_object_id') as $guest)
                        @if(!$deal->contact || $guest->hs_object_id != $deal->contact->hs_object_id)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $guest->first_name }} {{ $guest->last_name }}</h6>
                                    <small class="text-muted">{{ $guest->email }}</small>
                                </div>
                                <a href="{{ route('contact.show', $guest->hs_object_id) }}" 
                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add guest functionality
    document.querySelector('[data-action="add-guest"]').addEventListener('click', function() {
        // This could open a modal or redirect to contact creation
        alert('Add guest functionality to be implemented');
    });
    
    // Assign contact functionality  
    const assignContactLink = document.querySelector('[data-action="assign-contact"]');
    if (assignContactLink) {
        assignContactLink.addEventListener('click', function(e) {
            e.preventDefault();
            // This could open a contact selection modal
            alert('Assign contact functionality to be implemented');
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.pax-counter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    font-size: 2.5rem;
    font-weight: bold;
}

.pax-counter.pax-total {
    background-color: #0d6efd;
    color: white;
}

.pax-counter.pax-adults {
    background-color: #0dcaf0;
    color: white;
}

.pax-counter.pax-kids {
    background-color: #ffc107;
    color: #000;
}

.pax-counter small {
    font-size: 0.875rem;
    font-weight: normal;
}

.avatar-sm {
    width: 48px;
    height: 48px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .pax-counter {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
}
</style>
@endpush