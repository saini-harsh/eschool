@extends('layouts.student')
@section('title', 'Pending Payments')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Pending Payments</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.payments.index') }}">Payments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Pending</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('student.payments.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    @if($pendingFees->count() > 0)
        <!-- Pending Fees Cards -->
        <div class="row">
            @foreach($pendingFees as $fee)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 
                        @if($fee->status == 'overdue') border-danger
                        @elseif($fee->status == 'partial') border-warning
                        @else border-secondary @endif">
                        <div class="card-header 
                            @if($fee->status == 'overdue') bg-danger text-white
                            @elseif($fee->status == 'partial') bg-warning text-white
                            @else bg-secondary text-white @endif">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">{{ $fee->feeStructure->fee_name }}</h6>
                                <span class="badge 
                                    @if($fee->status == 'overdue') bg-light text-danger
                                    @elseif($fee->status == 'partial') bg-light text-warning
                                    @else bg-light text-secondary @endif">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Class:</span>
                                    <span class="fw-bold">{{ $fee->feeStructure->schoolClass->name ?? 'N/A' }}</span>
                                </div>
                                @if($fee->feeStructure->section)
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Section:</span>
                                        <span class="fw-bold">{{ $fee->feeStructure->section->name }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Total Amount:</span>
                                    <span class="fw-bold">₹{{ number_format($fee->amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Paid Amount:</span>
                                    <span class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Balance:</span>
                                    <span class="text-warning fw-bold">₹{{ number_format($fee->balance_amount, 2) }}</span>
                                </div>
                            </div>

                            @if($fee->due_date)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Due Date:</span>
                                        <span class="fw-bold {{ $fee->isOverdue() ? 'text-danger' : ($fee->getDueDateStatus() == 'due_today' ? 'text-warning' : 'text-success') }}">
                                            {{ $fee->getFormattedDueDate() }}
                                        </span>
                                    </div>
                                    @if($fee->due_date->isPast() && $fee->status != 'paid')
                                        <small class="text-danger">
                                            <i class="ti ti-alert-circle me-1"></i>
                                            Overdue by {{ $fee->due_date->diffInDays(now()) }} days
                                        </small>
                                    @elseif($fee->due_date->isToday())
                                        <small class="text-warning">
                                            <i class="ti ti-clock me-1"></i>
                                            Due today
                                        </small>
                                    @else
                                        <small class="text-success">
                                            <i class="ti ti-check me-1"></i>
                                            Due in {{ $fee->due_date->diffInDays(now()) }} days
                                        </small>
                                    @endif
                                </div>
                            @endif

                            @if($fee->feeStructure->description)
                                <div class="mb-3">
                                    <small class="text-muted">{{ $fee->feeStructure->description }}</small>
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            @if($fee->amount > 0)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Payment Progress</small>
                                        <small class="text-muted">{{ number_format(($fee->paid_amount / $fee->amount) * 100, 1) }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                            @if($fee->status == 'paid') bg-success
                                            @elseif($fee->status == 'partial') bg-warning
                                            @else bg-secondary @endif" 
                                             role="progressbar" 
                                             style="width: {{ ($fee->paid_amount / $fee->amount) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="{{ route('student.payments.fee-details', $fee) }}" 
                                   class="btn btn-outline-primary btn-sm flex-grow-1">
                                    <i class="ti ti-eye me-1"></i>View Details
                                </a>
                                @if($fee->status != 'paid')
                                    <button class="btn btn-primary btn-sm" 
                                            onclick="showPaymentInfo('{{ $fee->id }}', '{{ $fee->balance_amount }}', '{{ $fee->feeStructure->fee_name }}')">
                                        <i class="ti ti-credit-card me-1"></i>Pay Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Payment Information Modal -->
        <div class="modal fade" id="paymentInfoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Please contact your institution's finance office to make payments. 
                            You can pay the balance amount shown below.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Fee Name:</label>
                            <p class="fw-bold" id="modalFeeName"></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Balance Amount:</label>
                            <p class="fw-bold text-warning" id="modalBalanceAmount"></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Methods:</label>
                            <ul class="list-unstyled">
                                <li><i class="ti ti-check text-success me-2"></i>Cash at Finance Office</li>
                                <li><i class="ti ti-check text-success me-2"></i>Bank Transfer</li>
                                <li><i class="ti ti-check text-success me-2"></i>Online Payment (if available)</li>
                                <li><i class="ti ti-check text-success me-2"></i>Cheque</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>Note:</strong> Please bring a copy of this fee structure when making payment. 
                            Keep the receipt for your records.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('student.payments.fee-details', '') }}" id="viewDetailsBtn" class="btn btn-primary">
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- No Pending Payments -->
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                </div>
                <h5 class="text-success mb-2">All Payments Up to Date!</h5>
                <p class="text-muted mb-4">You have no pending payments at this time.</p>
                <a href="{{ route('student.payments.index') }}" class="btn btn-primary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Payment Dashboard
                </a>
            </div>
        </div>
    @endif

</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
function showPaymentInfo(feeId, balanceAmount, feeName) {
    document.getElementById('modalFeeName').textContent = feeName;
    document.getElementById('modalBalanceAmount').textContent = '₹' + parseFloat(balanceAmount).toFixed(2);
    document.getElementById('viewDetailsBtn').href = "{{ route('student.payments.fee-details', '') }}/" + feeId;
    
    const modal = new bootstrap.Modal(document.getElementById('paymentInfoModal'));
    modal.show();
}
</script>
@endpush
