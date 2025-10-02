@extends('layouts.institution')
@section('title', 'Fee Structure Invoice')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Fee Structure Invoice</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('institution.fee-structure.index') }}">Fee Structure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-primary me-2" onclick="window.print()">
                <i class="ti ti-printer me-1"></i>Print Invoice
            </button>
            <a href="{{ route('institution.fee-structure.download-invoice', $feeStructure) }}" class="btn btn-outline-primary">
                <i class="ti ti-download me-1"></i>Download PDF
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Invoice Content -->
    <div class="card" id="invoice-content">
        <div class="card-body">
            <div class="invoice-container">
                <div class="header text-center mb-4">
                    <h2 class="institution-name text-primary">{{ $feeStructure->institution->name }}</h2>
                    <h3 class="invoice-title">FEE STRUCTURE INVOICE</h3>
                    <p class="invoice-number text-muted">Invoice Date: {{ now()->format('d M Y') }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-section">
                            <h5 class="text-primary border-bottom pb-2 mb-3">Fee Information</h5>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Fee Name:</strong></div>
                                <div class="col-7">{{ $feeStructure->fee_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Fee Type:</strong></div>
                                <div class="col-7">
                                    <span class="badge bg-info">{{ ucfirst($feeStructure->fee_type) }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Payment Frequency:</strong></div>
                                <div class="col-7">
                                    <span class="badge bg-secondary">{{ ucfirst($feeStructure->payment_frequency) }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Amount:</strong></div>
                                <div class="col-7">
                                    <strong class="text-success fs-5">₹{{ number_format($feeStructure->amount, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-section">
                            <h5 class="text-primary border-bottom pb-2 mb-3">Applicable To</h5>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Class:</strong></div>
                                <div class="col-7">{{ $feeStructure->schoolClass->name ?? 'N/A' }}</div>
                            </div>
                            @if($feeStructure->section)
                            <div class="row mb-2">
                                <div class="col-5"><strong>Section:</strong></div>
                                <div class="col-7">{{ $feeStructure->section->name }}</div>
                            </div>
                            @else
                            <div class="row mb-2">
                                <div class="col-5"><strong>Section:</strong></div>
                                <div class="col-7">All Sections</div>
                            </div>
                            @endif
                            @if($feeStructure->due_date)
                            <div class="row mb-2">
                                <div class="col-5"><strong>Due Date:</strong></div>
                                <div class="col-7">{{ $feeStructure->due_date->format('d M Y') }}</div>
                            </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-5"><strong>Mandatory:</strong></div>
                                <div class="col-7">
                                    @if($feeStructure->is_mandatory)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($feeStructure->description)
                <div class="mb-4">
                    <h5 class="text-primary">Description</h5>
                    <p class="text-muted">{{ $feeStructure->description }}</p>
                </div>
                @endif

                <div class="payment-summary bg-light p-3 rounded mb-4">
                    <h5 class="text-primary mb-3">Fee Summary</h5>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Fee Name:</strong></div>
                        <div class="col-7">{{ $feeStructure->fee_name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Fee Type:</strong></div>
                        <div class="col-7">{{ ucfirst($feeStructure->fee_type) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Payment Frequency:</strong></div>
                        <div class="col-7">{{ ucfirst($feeStructure->payment_frequency) }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-5"><strong class="text-success">Total Amount:</strong></div>
                        <div class="col-7"><strong class="text-success fs-4">₹{{ number_format($feeStructure->amount, 2) }}</strong></div>
                    </div>
                </div>

                <div class="footer text-center mt-4 pt-3 border-top">
                    <p class="mb-2"><strong>Fee Structure Invoice</strong></p>
                    <p class="text-muted mb-1">This fee structure is applicable to all students in the specified class/section.</p>
                    <p class="text-muted mb-1">Generated on: {{ now()->format('d M Y h:i A') }}</p>
                    <p class="text-muted">For any queries, please contact the institution office.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-center gap-2 mt-3">
        <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back to Fee Structures
        </a>
        <a href="{{ route('institution.fee-structure.edit', $feeStructure) }}" class="btn btn-outline-primary">
            <i class="ti ti-edit me-1"></i>Edit Fee Structure
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
    .invoice-container {
        max-width: 100% !important;
    }
}
</style>
@endpush
