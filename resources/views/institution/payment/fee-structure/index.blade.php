@extends('layouts.institution')
@section('title', 'Fee Structure')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Fee Structure</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Fee Structure</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('institution.fee-structure.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Add Fee Structure
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Fee Structure List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fee Structure List</h5>
                </div>
                <div class="card-body">
                    @if($feeStructures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Amount</th>
                                        <th>Fee Type</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feeStructures as $feeStructure)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $feeStructure->name }}</h6>
                                                    @if($feeStructure->description)
                                                        <small class="text-muted">{{ Str::limit($feeStructure->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $feeStructure->schoolClass->name ?? 'N/A' }}</td>
                                            <td>{{ $feeStructure->section->name ?? 'All Sections' }}</td>
                                            <td>
                                                <span class="fw-semibold">₹{{ number_format($feeStructure->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $feeStructure->fee_type == 'monthly' ? 'primary' : ($feeStructure->fee_type == 'quarterly' ? 'info' : 'success') }}">
                                                    {{ ucfirst($feeStructure->fee_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $feeStructure->start_date->format('d M Y') }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox" 
                                                           data-id="{{ $feeStructure->id }}" 
                                                           {{ $feeStructure->status ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('institution.fee-structure.show', $feeStructure->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('institution.fee-structure.edit', $feeStructure->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                            data-id="{{ $feeStructure->id }}" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-receipt-off" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                            <h5 class="text-muted">No Fee Structures Found</h5>
                            <p class="text-muted">Start by creating your first fee structure.</p>
                            <a href="{{ route('institution.fee-structure.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Add Fee Structure
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Fee Structure List -->

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
                } else {
                    toastr.error('Failed to update status');
                    // Revert the toggle
                    $('.status-toggle[data-id="' + id + '"]').prop('checked', !status);
                }
            },
            error: function() {
                toastr.error('An error occurred');
                // Revert the toggle
                $('.status-toggle[data-id="' + id + '"]').prop('checked', !status);
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
                            location.reload();
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