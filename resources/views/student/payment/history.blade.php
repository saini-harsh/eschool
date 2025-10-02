@extends('layouts.student')
@section('title', 'Payment History')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment History</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.payments.index') }}">Payments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">History</li>
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

    @if($payments->count() > 0)
        <!-- Payment History Table -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Payment History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payment Ref</th>
                                <th>Fee Name</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $payment->payment_reference }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $payment->studentFee->feeStructure->fee_name }}</h6>
                                            <small class="text-muted">
                                                {{ $payment->studentFee->feeStructure->schoolClass->name ?? 'N/A' }}
                                                @if($payment->studentFee->feeStructure->section)
                                                    - {{ $payment->studentFee->feeStructure->section->name }}
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $payment->getFormattedPaymentDate() }}</span>
                                            <br>
                                            <small class="text-muted">{{ $payment->getFormattedPaymentDateWithTime() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($payment->payment_status == 'completed') bg-success
                                            @elseif($payment->payment_status == 'pending') bg-warning
                                            @elseif($payment->payment_status == 'failed') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->receipt_number)
                                            <span class="text-muted">{{ $payment->receipt_number }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('student.payments.show', $payment) }}">
                                                        <i class="ti ti-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                @if($payment->payment_status == 'completed')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('student.payments.receipt', $payment) }}">
                                                            <i class="ti ti-receipt me-2"></i>View Receipt
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('student.payments.download-receipt', $payment) }}">
                                                            <i class="ti ti-download me-2"></i>Download PDF
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Total Paid</h6>
                                <h4 class="mb-0">₹{{ number_format($payments->where('payment_status', 'completed')->sum('amount'), 2) }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-check" style="font-size: 2rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Total Transactions</h6>
                                <h4 class="mb-0">{{ $payments->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-credit-card" style="font-size: 2rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Completed</h6>
                                <h4 class="mb-0">{{ $payments->where('payment_status', 'completed')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- No Payment History -->
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-credit-card-off" style="font-size: 4rem; color: #ccc;"></i>
                </div>
                <h5 class="text-muted mb-2">No Payment History</h5>
                <p class="text-muted mb-4">You haven't made any payments yet.</p>
                <a href="{{ route('student.payments.index') }}" class="btn btn-primary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Payment Dashboard
                </a>
            </div>
        </div>
    @endif

</div>
<!-- End Content -->

@endsection
