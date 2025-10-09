@extends('layouts.institution')
@section('title', 'View Fee Structure')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Fee Structure Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('institution.fee-structure.index') }}">Fee Structure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
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

    <!-- Fee Structure Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Fee Structure Information</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('institution.fee-structure.edit', $feeStructure->id) }}" class="btn btn-warning btn-sm">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $feeStructure->id }}">
                                <i class="ti ti-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Fee Structure Name</h6>
                                <p class="fw-semibold">{{ $feeStructure->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Fee Type</h6>
                                <span class="badge bg-{{ $feeStructure->fee_type == 'monthly' ? 'primary' : ($feeStructure->fee_type == 'quarterly' ? 'info' : 'success') }} fs-6">
                                    {{ ucfirst($feeStructure->fee_type) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Class</h6>
                                <p class="fw-semibold">{{ $feeStructure->schoolClass->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Section</h6>
                                <p class="fw-semibold">{{ $feeStructure->section->name ?? 'All Sections' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Amount</h6>
                                <p class="fw-semibold text-primary fs-5">₹{{ number_format($feeStructure->amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Start Date</h6>
                                <p class="fw-semibold">{{ $feeStructure->start_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($feeStructure->description)
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Description</h6>
                                    <p class="fw-semibold">{{ $feeStructure->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Status</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox" 
                                           data-id="{{ $feeStructure->id }}" 
                                           {{ $feeStructure->status ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ $feeStructure->status ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Created At</h6>
                                <p class="fw-semibold">{{ $feeStructure->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Last Updated</h6>
                                <p class="fw-semibold">{{ $feeStructure->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Fee Structure Details -->

</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: `/institution/fee-structure/${id}/status`,
            method: 'POST',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update the label
                    const label = $('.status-toggle').siblings('label');
                    label.text(status ? 'Active' : 'Inactive');
                } else {
                    toastr.error('Failed to update status');
                    // Revert the toggle
                    $('.status-toggle').prop('checked', !status);
                }
            },
            error: function() {
                toastr.error('An error occurred');
                // Revert the toggle
                $('.status-toggle').prop('checked', !status);
            }
        });
    });

    // Delete confirmation
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/institution/fee-structure/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = '{{ route("institution.fee-structure.index") }}';
                        } else {
                            toastr.error('Failed to delete fee structure');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred');
                    }
                });
            }
        });
    });
});
</script>
@endpush
