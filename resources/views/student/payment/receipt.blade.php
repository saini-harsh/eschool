@extends('layouts.student')
@section('title', 'Payment Receipt')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Receipt</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.payments.index') }}">Payments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Receipt</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-primary me-2" onclick="window.print()">
                <i class="ti ti-printer me-1"></i>Print Receipt
            </button>
            <a href="{{ route('student.payments.download-receipt', $payment) }}" class="btn btn-outline-primary">
                <i class="ti ti-download me-1"></i>Download PDF
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Receipt Content -->
    <div class="card" id="receipt-content">
        <div class="card-body">
            <div class="receipt-container">
                <div class="header text-center mb-4">
                    <h2 class="institution-name text-primary">{{ $payment->institution->name }}</h2>
                    <h3 class="receipt-title">PAYMENT RECEIPT</h3>
                    <p class="receipt-number text-muted">Receipt No: {{ $payment->receipt_number }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-section">
                            <h5 class="text-primary border-bottom pb-2 mb-3">Student Information</h5>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Name:</strong></div>
                                <div class="col-7">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Admission No:</strong></div>
                                <div class="col-7">{{ $payment->student->admission_number ?? 'N/A' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Class:</strong></div>
                                <div class="col-7">{{ $payment->studentFee->feeStructure->schoolClass->name ?? 'N/A' }}</div>
                            </div>
                            @if($payment->studentFee->feeStructure->section)
                            <div class="row mb-2">
                                <div class="col-5"><strong>Section:</strong></div>
                                <div class="col-7">{{ $payment->studentFee->feeStructure->section->name ?? 'N/A' }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-section">
                            <h5 class="text-primary border-bottom pb-2 mb-3">Payment Information</h5>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Payment Ref:</strong></div>
                                <div class="col-7">{{ $payment->payment_reference }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Payment Date:</strong></div>
                                <div class="col-7">{{ $payment->payment_date->format('d M Y') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Payment Method:</strong></div>
                                <div class="col-7">
                                    <span class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Status:</strong></div>
                                <div class="col-7">
                                    <span class="badge bg-success">{{ ucfirst($payment->payment_status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="payment-summary bg-light p-3 rounded mb-4">
                    <h5 class="text-primary mb-3">Fee Details</h5>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Fee Name:</strong></div>
                        <div class="col-7">{{ $payment->studentFee->feeStructure->fee_name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Fee Type:</strong></div>
                        <div class="col-7">{{ ucfirst($payment->studentFee->feeStructure->fee_type) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Total Fee Amount:</strong></div>
                        <div class="col-7">₹{{ number_format($payment->studentFee->amount, 2) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Previously Paid:</strong></div>
                        <div class="col-7">₹{{ number_format($payment->studentFee->paid_amount - $payment->amount, 2) }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-5"><strong class="text-success">Amount Paid:</strong></div>
                        <div class="col-7"><strong class="text-success fs-5">₹{{ number_format($payment->amount, 2) }}</strong></div>
                    </div>
                </div>

                @if($payment->payment_notes)
                <div class="mb-4">
                    <h5 class="text-primary">Notes</h5>
                    <p class="text-muted fst-italic">{{ $payment->payment_notes }}</p>
                </div>
                @endif

                <div class="footer text-center mt-4 pt-3 border-top">
                    <p class="mb-2"><strong>Thank you for your payment!</strong></p>
                    <p class="text-muted mb-1">This is a computer-generated receipt. No signature required.</p>
                    <p class="text-muted mb-1">Generated on: {{ now()->format('d M Y h:i A') }}</p>
                    <p class="text-muted">For any queries, please contact the institution office.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-center gap-2 mt-3">
        <a href="{{ route('student.payments.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back to Dashboard
        </a>
        <a href="{{ route('student.payments.history') }}" class="btn btn-outline-primary">
            <i class="ti ti-history me-1"></i>Payment History
        </a>
    </div>

</div>
<!-- End Content -->

@endsection

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, .card-header, .d-flex.justify-content-center {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
    .receipt-container {
        max-width: 100% !important;
    }
}
</style>
@endpush
