@extends('layouts.student')
@section('title', 'Payment Dashboard')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Dashboard</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payments</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Payment Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Fees</h6>
                            <h4 class="mb-0">₹{{ number_format($totalFees, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-receipt" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Paid</h6>
                            <h4 class="mb-0">₹{{ number_format($totalPaid, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-check" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Balance Due</h6>
                            <h4 class="mb-0">₹{{ number_format($totalBalance, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Overdue</h6>
                            <h4 class="mb-0">{{ $overdueFees }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('student.payments.pending') }}" class="btn btn-outline-warning">
                            <i class="ti ti-clock me-1"></i>View Pending Payments
                        </a>
                        <a href="{{ route('student.payments.history') }}" class="btn btn-outline-info">
                            <i class="ti ti-history me-1"></i>Payment History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Fees and Payments -->
    <div class="row">
        <!-- Recent Fees -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Fees</h6>
                    <a href="{{ route('student.payments.pending') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($studentFees->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($studentFees->take(5) as $fee)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $fee->feeStructure->fee_name }}</h6>
                                        <small class="text-muted">
                                            {{ $fee->feeStructure->schoolClass->name ?? 'N/A' }}
                                            @if($fee->feeStructure->section)
                                                - {{ $fee->feeStructure->section->name }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">₹{{ number_format($fee->amount, 2) }}</span>
                                        <br>
                                        <span class="badge 
                                            @if($fee->status == 'paid') bg-success
                                            @elseif($fee->status == 'partial') bg-warning
                                            @elseif($fee->status == 'overdue') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ti ti-receipt-off" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">No fees found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Payments</h6>
                    <a href="{{ route('student.payments.history') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($payments->take(5) as $payment)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $payment->studentFee->feeStructure->fee_name }}</h6>
                                        <small class="text-muted">
                                            {{ $payment->getFormattedPaymentDate() }} - 
                                            {{ ucfirst($payment->payment_method) }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</span>
                                        <br>
                                        <span class="badge bg-success">Completed</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ti ti-credit-card-off" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">No payments found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Status Overview -->
    @if($studentFees->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Fee Status Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fee Name</th>
                                        <th>Class</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentFees as $fee)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $fee->feeStructure->fee_name }}</h6>
                                                    @if($fee->feeStructure->description)
                                                        <small class="text-muted">{{ Str::limit($fee->feeStructure->description, 30) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                {{ $fee->feeStructure->schoolClass->name ?? 'N/A' }}
                                                @if($fee->feeStructure->section)
                                                    <br><small class="text-muted">{{ $fee->feeStructure->section->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold">₹{{ number_format($fee->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-warning">₹{{ number_format($fee->balance_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $fee->isOverdue() ? 'text-danger' : ($fee->getDueDateStatus() == 'due_today' ? 'text-warning' : 'text-success') }}">
                                                    {{ $fee->getFormattedDueDate() }}
                                                </span>
                                                @if($fee->isOverdue())
                                                    <br><small class="text-danger">
                                                        <i class="ti ti-alert-circle me-1"></i>
                                                        Overdue by {{ $fee->getDaysOverdue() }} days
                                                    </small>
                                                @elseif($fee->getDueDateStatus() == 'due_today')
                                                    <br><small class="text-warning">
                                                        <i class="ti ti-clock me-1"></i>
                                                        Due today
                                                    </small>
                                                @elseif($fee->getDueDateStatus() == 'due_soon')
                                                    <br><small class="text-warning">
                                                        <i class="ti ti-clock me-1"></i>
                                                        Due in {{ $fee->getDaysUntilDue() }} days
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($fee->status == 'paid') bg-success
                                                    @elseif($fee->status == 'partial') bg-warning
                                                    @elseif($fee->status == 'overdue') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($fee->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('student.payments.fee-details', $fee) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
<!-- End Content -->

@endsection
