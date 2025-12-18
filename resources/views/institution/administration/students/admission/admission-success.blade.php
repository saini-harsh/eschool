@extends('layouts.institution')
@section('title', 'Admission Success')
@section('content')

    <div class="content">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <!-- Success Header -->
                <div class="card mb-4 border-success">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="ti ti-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="text-success fw-bold mb-3">Admission Submitted Successfully!</h2>
                        <p class="text-muted mb-4">The admission form has been submitted and payment records have been
                            created.</p>

                        <div class="alert alert-info d-inline-block">
                            <strong>Admission Number:</strong> {{ $admission->admission_number ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Admission Details -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ti ti-user me-2"></i>Admission Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Student Name:</strong> {{ $admission->first_name }} {{ $admission->last_name }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Admission Date:</strong>
                                {{ $admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') : 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Class:</strong> {{ $admission->schoolClass->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Roll Number:</strong> {{ $admission->roll_number ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong> {{ $admission->phone ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong> {{ $admission->email ?? 'N/A' }}
                            </div>
                            @if ($admission->discount_category)
                                <div class="col-md-12 mb-3">
                                    <div class="p-2 bg-light border rounded">
                                        <strong>Discount Applied:</strong>
                                        @if ($admission->discount_category == 'orphanage')
                                            Orphanage
                                        @elseif($admission->discount_category == 'teacher_child')
                                            Teacher's Child
                                        @elseif($admission->discount_category == 'personal_selection')
                                            Personal Selection
                                        @else
                                            {{ $admission->discount_category }}
                                        @endif
                                        - Amount: ₹{{ number_format($admission->discount_amount, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Records -->
                <div class="row">
                    <!-- Admission Fee Payment -->
                    @if ($admissionPayments->where('feeStructure.fee_type', 'onetime')->count() > 0)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="ti ti-receipt me-2"></i>Admission Fee Payment</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($admissionPayments->where('feeStructure.fee_type', 'onetime') as $payment)
                                        <div class="mb-3 pb-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>Receipt Number:</strong>
                                                <span class="badge bg-primary">{{ $payment->receipt_number }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong> ₹{{ number_format($payment->amount, 2) }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Date:</strong>
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
                                            </div>
                                            <a href="{{ route('institution.admission.receipt.admission', [$admission->id, $payment->id]) }}"
                                                target="_blank" class="btn btn-sm btn-primary">
                                                <i class="ti ti-printer me-1"></i>Print Receipt
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tuition Fee Payment -->
                    @if ($tuitionFeePayments->count() > 0)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="ti ti-receipt me-2"></i>Tuition Fee Payment</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($tuitionFeePayments as $payment)
                                        <div class="mb-3 pb-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>Receipt Number:</strong>
                                                <span
                                                    class="badge bg-warning text-dark">{{ $payment->receipt_number }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong> ₹{{ number_format($payment->payment_amount, 2) }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>Months Paid:</strong>
                                                @if ($payment->selected_months)
                                                    @php
                                                        $months = [
                                                            '',
                                                            'January',
                                                            'February',
                                                            'March',
                                                            'April',
                                                            'May',
                                                            'June',
                                                            'July',
                                                            'August',
                                                            'September',
                                                            'October',
                                                            'November',
                                                            'December',
                                                        ];
                                                        $monthNames = array_map(function ($m) use ($months) {
                                                            return $months[$m] ?? $m;
                                                        }, $payment->selected_months);
                                                    @endphp
                                                    {{ implode(', ', $monthNames) }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Date:</strong>
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
                                            </div>
                                            <a href="{{ route('institution.admission.receipt.tuition', [$admission->id, $payment->id]) }}"
                                                target="_blank" class="btn btn-sm btn-warning">
                                                <i class="ti ti-printer me-1"></i>Print Receipt
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('institution.admission.print', $admission->id) }}" target="_blank"
                                class="btn btn-primary btn-lg">
                                <i class="ti ti-printer me-2"></i>Print Admission Form
                            </a>
                            <a href="{{ route('institution.admission.admission-form') }}"
                                class="btn btn-outline-secondary btn-lg">
                                <i class="ti ti-plus me-2"></i>Submit Another Admission
                            </a>
                            <a href="{{ route('institution.students.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="ti ti-arrow-left me-2"></i>Back to Students
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
