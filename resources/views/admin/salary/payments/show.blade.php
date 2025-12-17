@extends('layouts.admin')
@section('title', 'Admin | Payment Details')
@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.salary.payments.index') }}">Salary Payments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.salary.payments.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Info -->
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
                            <label class="text-muted small">Transaction ID</label>
                            <p class="fw-semibold mb-0"><code>{{ $payment->transaction_id }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Salary Period</label>
                            <p class="fw-bold fs-5 mb-0">{{ $months[$payment->month] ?? '' }} {{ $payment->year }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Institution</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-primary">{{ $payment->institution->name ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Employee</label>
                            <p class="fw-semibold mb-0">{{ $payment->payeeName }} <small class="text-muted">({{ ucfirst($payment->payee_type) }})</small></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Payment Method</label>
                            <p class="fw-semibold mb-0">
                                @if($payment->payment_method == 'razorpay')
                                    <i class="ti ti-building-bank me-1"></i> RazorpayX (Bank Transfer)
                                @elseif($payment->payment_method == 'bank_transfer')
                                    <i class="ti ti-building-bank me-1"></i> Manual Bank Transfer
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
                        <div class="alert alert-info mb-3">
                            <strong><i class="ti ti-building-bank me-1"></i>Razorpay Payout ID:</strong> {{ $payment->razorpay_payout_id }}
                        </div>
                    @endif

                    @if($payment->failure_reason)
                        <div class="alert alert-danger mb-3">
                            <strong><i class="ti ti-alert-triangle me-1"></i>Failure Reason:</strong> {{ $payment->failure_reason }}
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

                            @if($payment->salary_breakdown && isset($payment->salary_breakdown['earnings']) && count($payment->salary_breakdown['earnings']) > 0)
                                <tr class="table-light">
                                    <td colspan="2"><strong class="text-success"><i class="ti ti-plus me-1"></i>Earnings</strong></td>
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
                                <tr class="table-success-subtle">
                                    <td class="ps-4"><strong>Total Earnings</strong></td>
                                    <td class="text-end text-success"><strong>+₹{{ number_format($payment->total_earnings, 2) }}</strong></td>
                                </tr>
                            @endif

                            @if($payment->salary_breakdown && isset($payment->salary_breakdown['deductions']) && count($payment->salary_breakdown['deductions']) > 0)
                                <tr class="table-light">
                                    <td colspan="2"><strong class="text-danger"><i class="ti ti-minus me-1"></i>Deductions</strong></td>
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
                                <tr class="table-danger-subtle">
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
                        <div class="avatar avatar-xxl bg-success-subtle text-success mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                            <i class="ti ti-check fs-1"></i>
                        </div>
                        <h5 class="text-success">Payment Completed</h5>
                        <p class="text-muted mb-0">This salary has been credited successfully.</p>
                    @elseif($payment->status == 'pending')
                        <div class="avatar avatar-xxl bg-warning-subtle text-warning mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                            <i class="ti ti-clock fs-1"></i>
                        </div>
                        <h5 class="text-warning">Payment Pending</h5>
                        <p class="text-muted mb-3">This payment is awaiting processing.</p>
                        <button type="button" class="btn btn-success process-btn" data-id="{{ $payment->id }}">
                            <i class="ti ti-send me-1"></i> Process Now
                        </button>
                    @elseif($payment->status == 'processing')
                        <div class="avatar avatar-xxl bg-info-subtle text-info mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                            <i class="ti ti-loader fs-1"></i>
                        </div>
                        <h5 class="text-info">Being Processed</h5>
                        <p class="text-muted mb-0">Payment is being processed. Please wait for confirmation.</p>
                    @elseif($payment->status == 'failed')
                        <div class="avatar avatar-xxl bg-danger-subtle text-danger mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                            <i class="ti ti-x fs-1"></i>
                        </div>
                        <h5 class="text-danger">Payment Failed</h5>
                        <p class="text-muted mb-3">This payment failed to process.</p>
                        <button type="button" class="btn btn-warning process-btn" data-id="{{ $payment->id }}">
                            <i class="ti ti-refresh me-1"></i> Retry
                        </button>
                    @endif
                </div>
            </div>

            <!-- Quick Summary -->
            <div class="card mb-4">
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

            <!-- Institution Info -->
            @if($payment->institution)
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0"><i class="ti ti-building me-2"></i>Institution</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $payment->institution->name }}</strong></p>
                    <p class="mb-1 small text-muted">{{ $payment->institution->email }}</p>
                    @if($payment->institution->hasRazorpayConfig())
                        <span class="badge bg-success"><i class="ti ti-check me-1"></i>Razorpay Configured</span>
                    @else
                        <span class="badge bg-warning"><i class="ti ti-alert-triangle me-1"></i>Razorpay Not Configured</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Process payment
    $('.process-btn').on('click', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        if (confirm('Are you sure you want to process this payment?')) {
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');
            
            $.ajax({
                url: '{{ url("admin/salary/payments") }}/' + id + '/process',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('<i class="ti ti-send me-1"></i> Process Now');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to process payment');
                    btn.prop('disabled', false).html('<i class="ti ti-send me-1"></i> Process Now');
                }
            });
        }
    });
});
</script>
@endpush
