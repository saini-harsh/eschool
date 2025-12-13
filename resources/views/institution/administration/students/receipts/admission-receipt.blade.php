@extends('layouts.institution')
@section('title', 'Admission Fee Receipt')
@section('content')

    <div class="content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold">Admission Fee Receipt</h5>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i>Print Receipt
                </button>
                <a href="{{ route('institution.admission.success', $admission->id) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="receipt-container">
                    <div class="receipt-header">
                        <div class="header-left">
                            <div class="institution-logo">
                                @if ($admission->institution->logo)
                                    <img src="{{ asset($admission->institution->logo) }}" alt="Logo" class="logo-img">
                                @else
                                    <div class="logo-placeholder">
                                        <i class="ti ti-school"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="header-center">
                            <h2 class="institution-name">{{ $admission->institution->name }}</h2>
                            <p class="institution-address">{{ $admission->institution->address }}</p>
                            <p class="institution-contact">
                                {{ $admission->institution->phone }} | {{ $admission->institution->email }}
                            </p>
                        </div>
                        <div class="header-right">
                            <div class="receipt-title">
                                <h1>ADMISSION FEE RECEIPT</h1>
                                <div class="receipt-number">
                                    <span class="label">Receipt No:</span>
                                    <span class="value">{{ $payment->receipt_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-body">
                        <div class="main-content">
                            <div class="content-left">
                                <div class="info-section">
                                    <h3 class="section-title">Student Information</h3>
                                    <div class="info-list">
                                        <div class="info-item">
                                            <span class="label">Name:</span>
                                            <span class="value">{{ $admission->first_name }}
                                                {{ $admission->last_name }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Admission No:</span>
                                            <span class="value">{{ $admission->admission_number ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Class:</span>
                                            <span class="value">{{ $admission->schoolClass->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Phone:</span>
                                            <span class="value">{{ $admission->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h3 class="section-title">Fee Information</h3>
                                    <div class="info-list">
                                        <div class="info-item">
                                            <span class="label">Fee Type:</span>
                                            <span class="value"><span
                                                    class="fee-type">{{ $payment->feeStructure->name ?? 'Admission Fee' }}</span></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Fee Amount:</span>
                                            <span
                                                class="value">₹{{ number_format($payment->feeStructure->amount ?? $payment->amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="content-right">
                                <div class="info-section">
                                    <h3 class="section-title">Payment Details</h3>
                                    <div class="info-list">
                                        <div class="info-item">
                                            <span class="label">Payment Date:</span>
                                            <span
                                                class="value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Payment Method:</span>
                                            <span class="value"><span
                                                    class="payment-method">{{ ucfirst($payment->payment_method) }}</span></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Status:</span>
                                            <span class="value"><span
                                                    class="status-badge completed">{{ ucfirst($payment->status) }}</span></span>
                                        </div>
                                        @if ($payment->transaction_id)
                                            <div class="info-item">
                                                <span class="label">Transaction ID:</span>
                                                <span class="value">{{ $payment->transaction_id }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="amount-section">
                                    <div class="amount-box">
                                        <div class="amount-label">Amount Paid</div>
                                        <div class="amount-value">₹{{ number_format($payment->amount, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($payment->notes)
                            <div class="info-section mt-3">
                                <h3 class="section-title">Notes</h3>
                                <div class="notes-content">{{ $payment->notes }}</div>
                            </div>
                        @endif

                        <div class="footer-info">
                            <div class="footer-left">
                                <p class="footer-text">
                                    <strong>Generated On:</strong> {{ $payment->created_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                            <div class="footer-right">
                                <p class="footer-text">
                                    This is a computer generated receipt and does not require a signature.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-footer">
                        <p class="footer-text">For any queries, please contact the institution office.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Receipt Container - Compact One Page Design */
            .receipt-container {
                background: white;
                border: 2px solid #2c3e50;
                border-radius: 8px;
                padding: 0;
                margin: 20px auto;
                max-width: 900px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .receipt-header {
                background: #f8f9fa;
                color: #2c3e50;
                padding: 15px 20px;
                border-radius: 6px 6px 0 0;
                border-bottom: 2px solid #3e007c;
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-height: 80px;
            }

            .header-left {
                flex: 0 0 auto;
            }

            .header-center {
                flex: 1;
                text-align: center;
                padding: 0 20px;
            }

            .header-right {
                flex: 0 0 auto;
                text-align: right;
            }

            .logo-img {
                max-width: 120px;
                border-radius: 4px;
            }

            .logo-placeholder {
                width: 80px;
                height: 80px;
                background: rgba(52, 62, 80, 0.1);
                border: 2px solid #2c3e50;
                border-radius: 4px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
            }

            .institution-name {
                font-size: 16px;
                font-weight: bold;
                margin: 0 0 5px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .institution-address {
                font-size: 11px;
                margin: 2px 0;
                opacity: 0.9;
            }

            .institution-contact {
                font-size: 10px;
                margin: 2px 0;
                opacity: 0.9;
            }

            .receipt-title h1 {
                font-size: 14px;
                font-weight: bold;
                margin: 0 0 5px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .receipt-number {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 2px;
            }

            .receipt-number .label {
                font-weight: normal;
                font-size: 10px;
                opacity: 0.9;
            }

            .receipt-number .value {
                font-weight: bold;
                font-size: 12px;
            }

            .receipt-body {
                padding: 15px 20px;
                background: #fafbfc;
            }

            .main-content {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 15px;
            }

            .info-section {
                background: white;
                padding: 12px;
                border: 1px solid #e1e8ed;
                border-radius: 6px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .section-title {
                font-size: 11px;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 10px;
                padding-bottom: 5px;
                border-bottom: 2px solid #3e007c;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .info-list {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 4px 0;
                border-bottom: 1px dotted #bdc3c7;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-item .label {
                font-weight: 600;
                color: #34495e;
                font-size: 10px;
                flex: 0 0 40%;
            }

            .info-item .value {
                font-weight: 500;
                color: #2c3e50;
                text-align: right;
                font-size: 10px;
                flex: 1;
            }

            .fee-type {
                background: #e8f4fd;
                color: #2980b9;
                padding: 2px 6px;
                border: 1px solid #3e007c;
                border-radius: 3px;
                font-size: 9px;
                font-weight: 600;
            }

            .payment-method {
                background: #e8f5e8;
                color: #27ae60;
                padding: 2px 6px;
                border: 1px solid #2ecc71;
                border-radius: 3px;
                font-size: 9px;
                font-weight: 600;
            }

            .amount-section {
                background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
                padding: 15px;
                border-radius: 6px;
                text-align: center;
                color: white;
                margin-top: auto;
                box-shadow: 0 3px 6px rgba(46, 204, 113, 0.3);
            }

            .amount-box {
                background: rgba(255, 255, 255, 0.1);
                padding: 12px;
                border-radius: 4px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .amount-label {
                font-size: 11px;
                font-weight: 600;
                margin-bottom: 6px;
                opacity: 0.9;
            }

            .amount-value {
                font-size: 20px;
                font-weight: bold;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }

            .notes-content {
                font-style: italic;
                color: #7f8c8d;
                font-size: 10px;
                line-height: 1.4;
                margin-top: 5px;
            }

            .footer-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-top: 2px solid #ecf0f1;
                margin-bottom: 10px;
            }

            .footer-text {
                font-size: 9px;
                color: #7f8c8d;
                margin: 0;
            }

            .status-badge {
                padding: 3px 8px;
                border-radius: 15px;
                font-weight: bold;
                font-size: 9px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .status-badge.completed {
                background: #d5f4e6;
                color: #27ae60;
                border: 1px solid #2ecc71;
            }

            .receipt-footer {
                background: #f8f9fa;
                padding: 10px 20px;
                text-align: center;
                border-top: 2px solid #ecf0f1;
                border-radius: 0 0 6px 6px;
            }

            @media print {

                .btn,
                .breadcrumb,
                .page-header,
                .sidebar,
                .navbar,
                .main-header {
                    display: none !important;
                }

                @page {
                    margin: 0.2in !important;
                    size: A4;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: white !important;
                }

                .receipt-container {
                    border: 2px solid #2c3e50 !important;
                    box-shadow: none !important;
                    margin: 0 !important;
                    max-width: none !important;
                    width: 100% !important;
                }
            }
        </style>
    @endpush

@endsection
