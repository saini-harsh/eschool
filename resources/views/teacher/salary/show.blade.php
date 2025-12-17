@extends('layouts.teacher')
@section('title', 'Salary Details')
@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Salary Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('teacher.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.salary.index') }}">My Salary</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('teacher.salary.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Info Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Payment Information</h6>
                    @if($payment->status == 'paid')
                        <span class="badge bg-success fs-6"><i class="ti ti-check me-1"></i>Paid</span>
                    @elseif($payment->status == 'pending')
                        <span class="badge bg-warning fs-6"><i class="ti ti-clock me-1"></i>Pending</span>
                    @elseif($payment->status == 'processing')
                        <span class="badge bg-info fs-6"><i class="ti ti-loader me-1"></i>Processing</span>
                    @elseif($payment->status == 'failed')
                        <span class="badge bg-danger fs-6"><i class="ti ti-x me-1"></i>Failed</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Salary Period</label>
                            <p class="fw-bold fs-5 mb-0">{{ $months[$payment->month] ?? '' }} {{ $payment->year }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Transaction ID</label>
                            <p class="fw-semibold mb-0">{{ $payment->transaction_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Payment Method</label>
                            <p class="fw-semibold mb-0">
                                @if($payment->payment_method == 'razorpay')
                                    <i class="ti ti-building-bank me-1"></i> RazorpayX (Bank Transfer)
                                @elseif($payment->payment_method == 'bank_transfer')
                                    <i class="ti ti-building-bank me-1"></i> Bank Transfer
                                @elseif($payment->payment_method == 'cash')
                                    <i class="ti ti-cash me-1"></i> Cash
                                @elseif($payment->payment_method == 'cheque')
                                    <i class="ti ti-file-text me-1"></i> Cheque
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Payment Date</label>
                            <p class="fw-semibold mb-0">
                                @if($payment->payment_date)
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, h:i A') }}
                                @else
                                    <span class="text-muted">Not yet paid</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($payment->razorpay_payout_id)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>Razorpay Payout ID:</strong> {{ $payment->razorpay_payout_id }}
                        </div>
                    @endif

                    @if($payment->failure_reason)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong>Failure Reason:</strong> {{ $payment->failure_reason }}
                        </div>
                    @endif

                    @if($payment->notes)
                        <div class="mt-3">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Salary Breakdown</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td><strong>Base Salary</strong></td>
                                <td class="text-end"><strong>₹{{ number_format($payment->base_salary, 2) }}</strong></td>
                            </tr>

                            @if($payment->salary_breakdown && isset($payment->salary_breakdown['earnings']))
                                <tr class="table-light">
                                    <td colspan="2"><strong class="text-success">Earnings</strong></td>
                                </tr>
                                @foreach($payment->salary_breakdown['earnings'] as $earning)
                                    <tr>
                                        <td class="ps-4">
                                            {{ $earning['name'] }}
                                            @if($earning['is_percentage'])
                                                <small class="text-muted">({{ $earning['percentage'] }}%)</small>
                                            @endif
                                        </td>
                                        <td class="text-end text-success">+₹{{ number_format($earning['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="ps-4"><strong>Total Earnings</strong></td>
                                    <td class="text-end text-success"><strong>+₹{{ number_format($payment->total_earnings, 2) }}</strong></td>
                                </tr>
                            @endif

                            @if($payment->salary_breakdown && isset($payment->salary_breakdown['deductions']))
                                <tr class="table-light">
                                    <td colspan="2"><strong class="text-danger">Deductions</strong></td>
                                </tr>
                                @foreach($payment->salary_breakdown['deductions'] as $deduction)
                                    <tr>
                                        <td class="ps-4">
                                            {{ $deduction['name'] }}
                                            @if($deduction['is_percentage'])
                                                <small class="text-muted">({{ $deduction['percentage'] }}%)</small>
                                            @endif
                                        </td>
                                        <td class="text-end text-danger">-₹{{ number_format($deduction['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="ps-4"><strong>Total Deductions</strong></td>
                                    <td class="text-end text-danger"><strong>-₹{{ number_format($payment->total_deductions, 2) }}</strong></td>
                                </tr>
                            @endif

                            <tr class="table-primary">
                                <td><strong class="fs-5">Net Salary</strong></td>
                                <td class="text-end"><strong class="fs-5">₹{{ number_format($payment->net_salary, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body text-center py-4">
                    @if($payment->status == 'paid')
                        <div class="avatar avatar-xxl bg-success-subtle text-success mb-3">
                            <i class="ti ti-check fs-1"></i>
                        </div>
                        <h5 class="text-success">Payment Credited</h5>
                        <p class="text-muted mb-0">Your salary has been credited to your bank account.</p>
                    @elseif($payment->status == 'pending')
                        <div class="avatar avatar-xxl bg-warning-subtle text-warning mb-3">
                            <i class="ti ti-clock fs-1"></i>
                        </div>
                        <h5 class="text-warning">Payment Pending</h5>
                        <p class="text-muted mb-0">Your salary is pending. It will be processed soon.</p>
                    @elseif($payment->status == 'processing')
                        <div class="avatar avatar-xxl bg-info-subtle text-info mb-3">
                            <i class="ti ti-loader fs-1"></i>
                        </div>
                        <h5 class="text-info">Being Processed</h5>
                        <p class="text-muted mb-0">Your salary is being processed. Please wait.</p>
                    @elseif($payment->status == 'failed')
                        <div class="avatar avatar-xxl bg-danger-subtle text-danger mb-3">
                            <i class="ti ti-x fs-1"></i>
                        </div>
                        <h5 class="text-danger">Payment Failed</h5>
                        <p class="text-muted mb-0">There was an issue processing your salary. Please contact administration.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Summary -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Quick Summary</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Base Salary</span>
                            <span>₹{{ number_format($payment->base_salary, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Earnings</span>
                            <span class="text-success">+₹{{ number_format($payment->total_earnings, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Deductions</span>
                            <span class="text-danger">-₹{{ number_format($payment->total_deductions, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <strong>Net Salary</strong>
                            <strong class="text-primary">₹{{ number_format($payment->net_salary, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
