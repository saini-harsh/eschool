@extends('layouts.teacher')
@section('title', 'My Salary')
@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">My Salary</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('teacher.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Salary</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-currency-rupee fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Current Salary</p>
                            <h4 class="mb-0 text-white">₹{{ number_format($stats['current_salary'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-check fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Total Received</p>
                            <h4 class="mb-0 text-white">₹{{ number_format($stats['total_received'], 2) }}</h4>
                            <small class="text-white-50">{{ $stats['count_paid'] }} payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-clock fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Pending Amount</p>
                            <h4 class="mb-0 text-white">₹{{ number_format($stats['total_pending'], 2) }}</h4>
                            <small class="text-white-50">{{ $stats['count_pending'] }} payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-calendar fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Current Month</p>
                            <h4 class="mb-0 text-white">{{ date('F Y') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.salary.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="ti ti-filter me-1"></i> Filter</button>
                    <a href="{{ route('teacher.salary.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Salary History Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-bold mb-0">Salary History</h6>
        </div>
        <div class="card-body p-0">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Period</th>
                                <th>Base Salary</th>
                                <th>Earnings</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $months[$payment->month] ?? '' }} {{ $payment->year }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->transaction_id }}</small>
                                    </td>
                                    <td>₹{{ number_format($payment->base_salary, 2) }}</td>
                                    <td class="text-success">+₹{{ number_format($payment->total_earnings, 2) }}</td>
                                    <td class="text-danger">-₹{{ number_format($payment->total_deductions, 2) }}</td>
                                    <td><strong>₹{{ number_format($payment->net_salary, 2) }}</strong></td>
                                    <td>
                                        @if($payment->status == 'paid')
                                            <span class="badge bg-success"><i class="ti ti-check me-1"></i>Paid</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning"><i class="ti ti-clock me-1"></i>Pending</span>
                                        @elseif($payment->status == 'processing')
                                            <span class="badge bg-info"><i class="ti ti-loader me-1"></i>Processing</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge bg-danger"><i class="ti ti-x me-1"></i>Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_date)
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.salary.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ti ti-receipt-off fs-1 text-muted"></i>
                    <p class="mt-3 mb-0 text-muted">No salary records found</p>
                    @if(request()->hasAny(['status', 'year']))
                        <a href="{{ route('teacher.salary.index') }}" class="btn btn-outline-primary mt-3">Clear Filters</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Bank Details Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="fw-bold mb-0"><i class="ti ti-building-bank me-2"></i>My Bank Details</h6>
        </div>
        <div class="card-body">
            @if($teacher->bank_account_number)
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Bank Name</label>
                        <p class="fw-semibold mb-0">{{ $teacher->bank_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Branch</label>
                        <p class="fw-semibold mb-0">{{ $teacher->bank_branch ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Account Number</label>
                        <p class="fw-semibold mb-0">{{ substr($teacher->bank_account_number, 0, 4) }}****{{ substr($teacher->bank_account_number, -4) }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">IFSC Code</label>
                        <p class="fw-semibold mb-0">{{ $teacher->bank_ifsc_code ?? '-' }}</p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="ti ti-alert-triangle me-2"></i>
                    Bank details not configured. Please contact administration to update your bank details.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
