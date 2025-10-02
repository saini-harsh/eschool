@extends('layouts.institution')
@section('title', 'Create Fee Structure')
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
            <h5 class="fw-bold">Create Fee Structure</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">Payment</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('institution.fee-structure.index') }}">Fee Structure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Create Form -->
    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">Fee Structure Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('institution.fee-structure.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fee_name" class="form-label">Fee Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('fee_name') is-invalid @enderror" 
                                   id="fee_name" 
                                   name="fee_name" 
                                   value="{{ old('fee_name') }}" 
                                   required>
                            @error('fee_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount') }}" 
                                   step="0.01" 
                                   min="0" 
                                   required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

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
                            <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('fee_type') is-invalid @enderror" 
                                    id="fee_type" 
                                    name="fee_type" 
                                    required>
                                <option value="">Select Fee Type</option>
                                <option value="monthly" {{ old('fee_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('fee_type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ old('fee_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="one_time" {{ old('fee_type') == 'one_time' ? 'selected' : '' }}>One Time</option>
                            </select>
                            @error('fee_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_frequency" class="form-label">Payment Frequency <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_frequency') is-invalid @enderror" 
                                    id="payment_frequency" 
                                    name="payment_frequency" 
                                    required>
                                <option value="">Select Payment Frequency</option>
                                <option value="monthly" {{ old('payment_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('payment_frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ old('payment_frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="one_time" {{ old('payment_frequency') == 'one_time' ? 'selected' : '' }}>One Time</option>
                            </select>
                            @error('payment_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <div class="input-group w-auto input-group-flat">
                                <input type="text" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                       data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                       value="{{ old('due_date') }}">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            </div>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_mandatory" 
                                       name="is_mandatory" 
                                       value="1" 
                                       {{ old('is_mandatory') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_mandatory">
                                    Mandatory Fee
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>Create Fee Structure
                    </button>
                </div>
            </form>
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

    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    classSelect.addEventListener('change', function() {
        const classId = this.value;
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        
        if (classId) {
            fetch(`/institution/payment/fee-structure/sections/${classId}`)
                .then(response => response.json())
                .then(sections => {
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching sections:', error);
                });
        }
    });
});
</script>
@endpush
