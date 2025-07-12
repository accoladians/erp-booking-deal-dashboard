{{-- Financial Panel Component --}}
<div class="card panel-financial">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#summary-tab" role="tab">
                    <i class="fas fa-chart-line me-1"></i> Summary
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#transactions-tab" role="tab">
                    <i class="fas fa-exchange-alt me-1"></i> Transactions
                    <span class="badge bg-secondary ms-1">{{ $deal->transactions->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#payment-status-tab" role="tab">
                    <i class="fas fa-credit-card me-1"></i> Payment Status
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            {{-- Summary Tab --}}
            <div class="tab-pane fade show active" id="summary-tab" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Financial Overview</h6>
                        <div class="financial-summary">
                            <div class="summary-item">
                                <span>Total Deal Value</span>
                                <span>{{ format_price($deal->total_amount) }} EUR</span>
                            </div>
                            <div class="summary-item">
                                <span>Total Paid</span>
                                <span class="text-success">
                                    {{ format_price($deal->transactions->where('amount', '>', 0)->sum('amount')) }} EUR
                                </span>
                            </div>
                            <div class="summary-item">
                                <span>Total Refunded</span>
                                <span class="text-danger">
                                    {{ format_price(abs($deal->transactions->where('amount', '<', 0)->sum('amount'))) }} EUR
                                </span>
                            </div>
                            <div class="summary-item border-top pt-2">
                                <span>Balance Due</span>
                                <span class="{{ $deal->total_amount - $deal->transactions->sum('amount') > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ format_price($deal->total_amount - $deal->transactions->sum('amount')) }} EUR
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Payment Progress</h6>
                        @php
                            $paidPercentage = $deal->total_amount > 0 
                                ? min(100, round(($deal->transactions->where('amount', '>', 0)->sum('amount') / $deal->total_amount) * 100))
                                : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Payment Progress</span>
                                <span>{{ $paidPercentage }}%</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $paidPercentage >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $paidPercentage }}%">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row text-center mt-4">
                            <div class="col-6">
                                <h6 class="text-muted">Deal Status</h6>
                                <span class="badge {{ $deal->payment_status->getColor() }} fs-6">
                                    {{ $deal->payment_status->value }}
                                </span>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted">Vendor Status</h6>
                                <span class="badge {{ $deal->vendor_payment_status->getColor() }} fs-6">
                                    {{ $deal->vendor_payment_status->value }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary" data-action="record-payment">
                        <i class="fas fa-plus-circle me-1"></i> Record Payment
                    </button>
                    <button class="btn btn-outline-primary" data-action="send-invoice">
                        <i class="fas fa-file-invoice me-1"></i> Send Invoice
                    </button>
                    <button class="btn btn-outline-secondary" data-action="download-statement">
                        <i class="fas fa-download me-1"></i> Download Statement
                    </button>
                </div>
            </div>
            
            {{-- Transactions Tab --}}
            <div class="tab-pane fade" id="transactions-tab" role="tabpanel">
                @if($deal->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Transaction ID</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deal->transactions->sortByDesc('created_at') as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('account.transaction.show', $transaction->id) }}" target="_blank">
                                                #{{ $transaction->id }}
                                            </a>
                                        </td>
                                        <td>{{ $transaction->description ?? 'Payment' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->amount > 0 ? 'success' : 'danger' }}">
                                                {{ $transaction->amount > 0 ? 'Payment' : 'Refund' }}
                                            </span>
                                        </td>
                                        <td class="text-end {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ format_price($transaction->amount) }} {{ $transaction->currency }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    data-action="view-transaction" 
                                                    data-id="{{ $transaction->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="4">Total</th>
                                    <th class="text-end">{{ format_price($deal->transactions->sum('amount')) }} EUR</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">No transactions recorded yet.</p>
                        <button class="btn btn-primary" data-action="record-payment">
                            <i class="fas fa-plus-circle me-2"></i>Record First Payment
                        </button>
                    </div>
                @endif
            </div>
            
            {{-- Payment Status Tab --}}
            <div class="tab-pane fade" id="payment-status-tab" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Customer Payment Status</h6>
                        <div class="payment-status-card p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ $deal->payment_status->value }}</h5>
                                <span class="badge {{ $deal->payment_status->getColor() }} fs-6">
                                    <i class="fas fa-{{ $deal->payment_status->value == 'Paid' ? 'check-circle' : 'clock' }}"></i>
                                </span>
                            </div>
                            <div class="small text-muted">
                                @if($deal->payment_status->value == 'Paid')
                                    Full payment received from customer
                                @elseif($deal->payment_status->value == 'Deposit Paid')
                                    Deposit received, awaiting final payment
                                @else
                                    No payment received from customer yet
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Vendor Payment Status</h6>
                        <div class="payment-status-card p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ $deal->vendor_payment_status->value }}</h5>
                                <span class="badge {{ $deal->vendor_payment_status->getColor() }} fs-6">
                                    <i class="fas fa-{{ $deal->vendor_payment_status->value == 'Paid' ? 'check-circle' : 'clock' }}"></i>
                                </span>
                            </div>
                            <div class="small text-muted">
                                @if($deal->vendor_payment_status->value == 'Paid')
                                    All vendors have been paid
                                @elseif($deal->vendor_payment_status->value == 'Partially Paid')
                                    Some vendors paid, others pending
                                @else
                                    Vendor payments pending
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Payment Timeline --}}
                <h6 class="text-muted mb-3 mt-4">Payment Timeline</h6>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Deal Created</h6>
                            <p class="text-muted mb-0">{{ $deal->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @foreach($deal->transactions->sortBy('created_at') as $transaction)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $transaction->amount > 0 ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <h6>{{ $transaction->amount > 0 ? 'Payment Received' : 'Refund Issued' }}</h6>
                                <p class="mb-0">{{ format_price(abs($transaction->amount)) }} EUR</p>
                                <p class="text-muted mb-0">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Record payment
    document.querySelectorAll('[data-action="record-payment"]').forEach(btn => {
        btn.addEventListener('click', function() {
            // Open payment modal or redirect
            alert('Record payment functionality to be implemented');
        });
    });
    
    // Send invoice
    document.querySelector('[data-action="send-invoice"]')?.addEventListener('click', function() {
        alert('Send invoice functionality to be implemented');
    });
    
    // Download statement
    document.querySelector('[data-action="download-statement"]')?.addEventListener('click', function() {
        alert('Download statement functionality to be implemented');
    });
    
    // View transaction details
    document.querySelectorAll('[data-action="view-transaction"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const transactionId = this.dataset.id;
            window.open(`/account/transaction/${transactionId}`, '_blank');
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.financial-summary .summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.financial-summary .summary-item:last-child {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.1rem;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -21px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px #e9ecef;
}

.timeline-content {
    padding-left: 10px;
}
</style>
@endpush