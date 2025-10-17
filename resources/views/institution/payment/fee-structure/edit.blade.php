@extends('layouts.institution')
@section('title', 'Edit Fee Structure')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Edit Fee Structure</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('institution.fee-structure.index') }}">Fee Structure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

    <!-- Edit Fee Structure Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Fee Structure Information</h5>
                </div>
                <div class="card-body">
                    <form id="feeStructureForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Fee Structure Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $feeStructure->name }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="fee_type" name="fee_type" required>
                                        <option value="">Select Fee Type</option>
                                        <option value="monthly" {{ $feeStructure->fee_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ $feeStructure->fee_type == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ $feeStructure->fee_type == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="onetime" {{ $feeStructure->fee_type == 'onetime' ? 'selected' : '' }}>One Time</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class</label>
                                    <select class="form-select" id="class_id" name="class_id">
                                        <option value="">Select Class (Optional)</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ $feeStructure->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">Select Section (Optional)</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ $feeStructure->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" value="{{ $feeStructure->amount }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpickr" id="due_date" name="due_date" value="{{ $feeStructure->due_date->format('Y-m-d') }}" placeholder="Select due date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter fee structure description (optional)">{{ $feeStructure->description }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Update Fee Structure
                            </button>
                            <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Fee Structure Form -->

</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Flatpickr for date input
    flatpickr("#due_date", {
        dateFormat: "Y-m-d",
        allowInput: true,
        placeholder: "Select due date"
    });

    // Load sections when class is selected
    $('#class_id').change(function() {
        const classId = $(this).val();
        const sectionSelect = $('#section_id');
        const currentSectionId = '{{ $feeStructure->section_id }}';
        
        sectionSelect.html('<option value="">Loading sections...</option>');
        
        if (classId) {
            $.ajax({
                url: `/institution/fee-structure/sections/${classId}`,
                method: 'GET',
                success: function(response) {
                    sectionSelect.html('<option value="">Select Section (Optional)</option>');
                    response.forEach(function(section) {
                        const selected = section.id == currentSectionId ? 'selected' : '';
                        sectionSelect.append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
                    });
                },
                error: function() {
                    sectionSelect.html('<option value="">Error loading sections</option>');
                }
            });
        } else {
            sectionSelect.html('<option value="">Select Section (Optional)</option>');
        }
    });

    // Form submission
    $('#feeStructureForm').submit(function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("institution.fee-structure.update", $feeStructure->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = '{{ route("institution.fee-structure.index") }}';
                } else {
                    toastr.error(response.message || 'Failed to update fee structure');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const field = $(`[name="${key}"]`);
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while updating the fee structure');
                }
            }
        });
    });
});
</script>
@endpush