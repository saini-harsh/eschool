@extends('layouts.institution')
@section('title', 'Record Payment')
@section('content')

@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Record Payment</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('institution.payments.index') }}">Payments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Record Payment</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Payment Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('institution.payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" 
                                            id="class_id" 
                                            name="class_id" 
                                            required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select class="form-select @error('section_id') is-invalid @enderror" 
                                            id="section_id" 
                                            name="section_id">
                                        <option value="">All Sections</option>
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-select @error('student_id') is-invalid @enderror" 
                                            id="student_id" 
                                            name="student_id" 
                                            required>
                                        <option value="">Select Student</option>
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_fee_id" class="form-label">Fee Structure <span class="text-danger">*</span></label>
                                    <select class="form-select @error('student_fee_id') is-invalid @enderror" 
                                            id="student_fee_id" 
                                            name="student_fee_id" 
                                            required>
                                        <option value="">Select Fee Structure</option>
                                    </select>
                                    @error('student_fee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           value="{{ old('amount') }}" 
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" 
                                            name="payment_method" 
                                            required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                               data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                               value="{{ old('payment_date', date('d M, Y')) }}" required>
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label">Transaction ID</label>
                                    <input type="text" 
                                           class="form-control @error('transaction_id') is-invalid @enderror" 
                                           id="transaction_id" 
                                           name="transaction_id" 
                                           value="{{ old('transaction_id') }}">
                                    @error('transaction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Payment Notes</label>
                            <textarea class="form-control @error('payment_notes') is-invalid @enderror" 
                                      id="payment_notes" 
                                      name="payment_notes" 
                                      rows="3">{{ old('payment_notes') }}</textarea>
                            @error('payment_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('institution.payments.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const studentSelect = document.getElementById('student_id');
    const feeSelect = document.getElementById('student_fee_id');

    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Load sections when class changes
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        studentSelect.innerHTML = '<option value="">Select Student</option>';
        feeSelect.innerHTML = '<option value="">Select Fee Structure</option>';

        if (classId) {
            fetch(`/institution/payment/payments/sections/${classId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                });
        }
    });

    // Load students when class or section changes
    function loadStudents() {
        const classId = classSelect.value;
        const sectionId = sectionSelect.value;
        
        studentSelect.innerHTML = '<option value="">Select Student</option>';
        feeSelect.innerHTML = '<option value="">Select Fee Structure</option>';

        if (classId) {
            let url = `/institution/payment/payments/students/${classId}`;
            if (sectionId) {
                url += `/${sectionId}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.first_name} ${student.last_name} (${student.admission_number || 'N/A'})`;
                        studentSelect.appendChild(option);
                    });
                });
        }
    }

    sectionSelect.addEventListener('change', loadStudents);

    // Load fee structures when student changes
    studentSelect.addEventListener('change', function() {
        const studentId = this.value;
        feeSelect.innerHTML = '<option value="">Select Fee Structure</option>';

        if (studentId) {
            fetch(`/institution/payment/payments/student-fees/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(fee => {
                        const option = document.createElement('option');
                        option.value = fee.id;
                        option.textContent = `${fee.fee_name} - ₹${fee.balance_amount} (Due: ${fee.due_date})`;
                        option.dataset.balance = fee.balance_amount;
                        feeSelect.appendChild(option);
                    });
                });
        }
    });

    // Update amount field when fee structure changes
    feeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const amountInput = document.getElementById('amount');
        
        if (selectedOption.dataset.balance) {
            amountInput.max = selectedOption.dataset.balance;
            amountInput.placeholder = `Max: ₹${selectedOption.dataset.balance}`;
        }
    });
});
</script>
@endpush
