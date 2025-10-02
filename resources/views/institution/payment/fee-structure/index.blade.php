@extends('layouts.institution')
@section('title', 'Fee Structure Management')
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

@if (session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-alert-circle me-2"></i>
                    {{ session('error') }}
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
            <h5 class="fw-bold">Fee Structure Management</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">Payment</li>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Fee Structure Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">Fee Structures</h6>
        </div>
        <div class="card-body">
            @if($feeStructures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fee Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Frequency</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feeStructures as $feeStructure)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $feeStructure->fee_name }}</h6>
                                            @if($feeStructure->description)
                                                <small class="text-muted">{{ Str::limit($feeStructure->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $feeStructure->schoolClass->name ?? 'N/A' }}</td>
                                    <td>{{ $feeStructure->section->name ?? 'All Sections' }}</td>
                                    <td>
                                        <span class="fw-bold text-success">₹{{ number_format($feeStructure->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($feeStructure->fee_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($feeStructure->payment_frequency) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $feeStructure->getDueDateStatus() == 'overdue' ? 'text-danger' : ($feeStructure->getDueDateStatus() == 'due_today' ? 'text-warning' : 'text-success') }}">
                                            {{ $feeStructure->getFormattedDueDate() }}
                                        </span>
                                        @if($feeStructure->getDueDateStatus() == 'due_soon')
                                            <br><small class="text-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                Due in {{ abs($feeStructure->getDaysUntilDue()) }} days
                                            </small>
                                        @elseif($feeStructure->getDueDateStatus() == 'overdue')
                                            <br><small class="text-danger">
                                                <i class="ti ti-alert-circle me-1"></i>
                                                Overdue by {{ abs($feeStructure->getDaysUntilDue()) }} days
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   data-id="{{ $feeStructure->id }}"
                                                   {{ $feeStructure->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('institution.fee-structure.edit', $feeStructure) }}">
                                                        <i class="ti ti-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('institution.fee-structure.invoice', $feeStructure) }}">
                                                        <i class="ti ti-receipt me-2"></i>View Invoice
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('institution.fee-structure.download-invoice', $feeStructure) }}">
                                                        <i class="ti ti-download me-2"></i>Download PDF
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('institution.fee-structure.destroy', $feeStructure) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this fee structure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
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
                    <h6 class="text-muted">No fee structures found</h6>
                    <p class="text-muted">Create your first fee structure to get started.</p>
                    <a href="{{ route('institution.fee-structure.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Add Fee Structure
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Status toggle functionality
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const feeStructureId = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            fetch(`/institution/payment/fee-structure/${feeStructureId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.content').insertBefore(alert, document.querySelector('.content').firstChild);
                } else {
                    // Revert toggle if failed
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert toggle if failed
                this.checked = !this.checked;
            });
        });
    });
});
</script>
@endpush
