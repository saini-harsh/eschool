@extends('layouts.institution')
@section('title', 'Bulk Salary Processing')
@section('content')
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Bulk Salary Processing</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.salary.payments.index') }}">Salary Payments</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bulk Process</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <form id="bulkForm">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Payment Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Month <span class="text-danger">*</span></label>
                                <select name="month" class="form-select" required>
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <select name="year" class="form-select" required>
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="razorpay">RazorpayX (Bank Transfer)</option>
                                    <option value="bank_transfer">Manual Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="include_teachers" value="1" class="form-check-input" id="includeTeachers" checked>
                                    <label class="form-check-label" for="includeTeachers">Include Teachers ({{ $teachers->count() }})</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="include_staff" value="1" class="form-check-input" id="includeStaff" checked>
                                    <label class="form-check-label" for="includeStaff">Include Non-Working Staff ({{ $staff->count() }})</label>
                                </div>
                            </div>

                            <hr>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="ti ti-check me-1"></i> Create Salary Payments
                                </button>
                            </div>

                            <p class="text-muted small mt-3 mb-0">
                                <i class="ti ti-info-circle me-1"></i>
                                This will create pending salary payment records for all selected employees. 
                                You can then process them individually or in bulk.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Teachers List -->
                    <div class="card mb-3" id="teachersCard">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Teachers with Salary Configured</h6>
                            <span class="badge bg-primary">{{ $teachers->count() }}</span>
                        </div>
                        <div class="card-body">
                            @if($teachers->count() > 0)
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead class="sticky-top bg-white">
                                            <tr>
                                                <th>Name</th>
                                                <th>Employee ID</th>
                                                <th>Salary</th>
                                                <th>Bank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teachers as $teacher)
                                                <tr>
                                                    <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                                    <td><code>{{ $teacher->employee_id ?? 'N/A' }}</code></td>
                                                    <td>₹{{ number_format($teacher->salary, 2) }}</td>
                                                    <td>
                                                        @if($teacher->hasBankDetails())
                                                            <span class="badge bg-success"><i class="ti ti-check"></i></span>
                                                        @else
                                                            <span class="badge bg-warning"><i class="ti ti-alert-triangle"></i></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="ti ti-user-off fs-1"></i>
                                    <p class="mt-2 mb-0">No teachers with salary configured</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Staff List -->
                    <div class="card" id="staffCard">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Non-Working Staff with Salary Configured</h6>
                            <span class="badge bg-primary">{{ $staff->count() }}</span>
                        </div>
                        <div class="card-body">
                            @if($staff->count() > 0)
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead class="sticky-top bg-white">
                                            <tr>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Salary</th>
                                                <th>Bank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($staff as $s)
                                                <tr>
                                                    <td>{{ $s->first_name }} {{ $s->last_name }}</td>
                                                    <td>{{ $s->designation ?? 'N/A' }}</td>
                                                    <td>₹{{ number_format($s->salary, 2) }}</td>
                                                    <td>
                                                        @if($s->hasBankDetails())
                                                            <span class="badge bg-success"><i class="ti ti-check"></i></span>
                                                        @else
                                                            <span class="badge bg-warning"><i class="ti ti-alert-triangle"></i></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="ti ti-user-off fs-1"></i>
                                    <p class="mt-2 mb-0">No staff with salary configured</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle visibility based on checkboxes
    $('#includeTeachers').on('change', function() {
        $('#teachersCard').toggle($(this).is(':checked'));
    });

    $('#includeStaff').on('change', function() {
        $('#staffCard').toggle($(this).is(':checked'));
    });

    // Form submission
    $('#bulkForm').on('submit', function(e) {
        e.preventDefault();

        if (!$('#includeTeachers').is(':checked') && !$('#includeStaff').is(':checked')) {
            toastr.error('Please select at least one employee group');
            return;
        }

        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

        $.ajax({
            url: '{{ route("institution.salary.payments.bulk-process") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    if (response.errors && response.errors.length > 0) {
                        response.errors.forEach(err => toastr.warning(err));
                    }
                    
                    setTimeout(() => {
                        window.location.href = response.redirect_url;
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Salary Payments');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Failed to create payments');
                }
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Salary Payments');
            }
        });
    });
});
</script>
@endpush
