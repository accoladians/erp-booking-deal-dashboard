{{-- Deal Info Panel Component --}}
<div class="card panel-deal-info h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-file-contract me-2"></i>Deal Information
        </h5>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-primary" data-action="edit-deal" data-deal-id="{{ $deal->id }}">
                <i class="fas fa-edit"></i> Edit
            </button>
            <a href="{{ $hubspotDeals::getHubspotUrl($deal->id) }}" class="btn btn-outline-info" target="_blank">
                <i class="fab fa-hubspot"></i> HubSpot
            </a>
            <a href="{{ route('guest.deal.show', $deal->id) }}" class="btn btn-outline-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> Guest View
            </a>
        </div>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4">Deal ID</dt>
            <dd class="col-sm-8">
                <code>{{ $deal->id }}</code>
                <span class="badge {{ $deal->dealstage == 'closedwon' ? 'bg-success' : 'bg-primary' }} ms-2">
                    {{ $dealStages[$deal->dealstage] ?? $deal->dealstage }}
                </span>
            </dd>
            
            <dt class="col-sm-4">Name</dt>
            <dd class="col-sm-8">{{ $deal->name }}</dd>
            
            <dt class="col-sm-4">Period</dt>
            <dd class="col-sm-8">
                <i class="fas fa-calendar-alt me-1"></i>
                {{ $deal->arrival_date->format('M d, Y') }} - {{ $deal->departure_date->format('M d, Y') }}
                <span class="text-muted">({{ $deal->arrival_date->diffInDays($deal->departure_date) }} nights)</span>
            </dd>
            
            <dt class="col-sm-4">Owner</dt>
            <dd class="col-sm-8">
                @if($deal->owner)
                    <i class="fas fa-user me-1"></i>
                    {{ $deal->owner->first_name }} {{ $deal->owner->last_name }}
                @else
                    <span class="text-muted">Not assigned</span>
                @endif
            </dd>
            
            @if($deal->description)
            <dt class="col-sm-4">Description</dt>
            <dd class="col-sm-8">
                <div class="expandable-text" data-expanded="false">
                    <p class="mb-1">{{ Str::limit($deal->description, 150) }}</p>
                    @if(strlen($deal->description) > 150)
                        <a href="#" class="text-primary small" data-toggle="expand">Show more</a>
                        <div class="full-text d-none">{{ $deal->description }}</div>
                    @endif
                </div>
            </dd>
            @endif
            
            @if($deal->accommodation)
            <dt class="col-sm-4">Accommodation Notes</dt>
            <dd class="col-sm-8">
                <div class="text-muted small">{{ $deal->accommodation }}</div>
            </dd>
            @endif
            
            <dt class="col-sm-4">Last Updated</dt>
            <dd class="col-sm-8 text-muted small">
                {{ $deal->updated_at->diffForHumans() }}
                @if($deal->last_sync_at)
                    <br>Synced: {{ $deal->last_sync_at->diffForHumans() }}
                @endif
            </dd>
        </dl>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Expandable text functionality
    document.querySelectorAll('[data-toggle="expand"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const container = this.closest('.expandable-text');
            const fullText = container.querySelector('.full-text');
            const isExpanded = container.dataset.expanded === 'true';
            
            if (isExpanded) {
                container.querySelector('p').innerHTML = '{{ Str::limit($deal->description, 150) }}';
                this.textContent = 'Show more';
                container.dataset.expanded = 'false';
            } else {
                container.querySelector('p').innerHTML = fullText.textContent;
                this.textContent = 'Show less';
                container.dataset.expanded = 'true';
            }
        });
    });
    
    // Edit deal button
    document.querySelector('[data-action="edit-deal"]').addEventListener('click', function() {
        // Could open modal or redirect to edit page
        window.location.href = '{{ route("booking.deal.show", $deal) }}';
    });
});
</script>
@endpush