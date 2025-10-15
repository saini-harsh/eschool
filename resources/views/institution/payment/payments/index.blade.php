@extends('layouts.institution')
@section('title', 'Payment History')
@section('content')

@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
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
            <h5 class="fw-bold">Payment History</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payment History</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i>Record New Payment
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Search and Filter Section -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="datatable-search">
            <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
        </div>
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center border rounded table-grid me-2">
                <a href="javascript:void(0);" class="btn p-1 btn-primary"><i class="ti ti-list"></i></a>
                <a href="javascript:void(0);" class="btn p-1"><i class="ti ti-layout-grid"></i></a>
            </div>
            <div class="dropdown me-2">
                <a href="javascript:void(0);"
                    class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="ti ti-filter me-1"></i>Filter
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                    <div class="card mb-0">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0">Filter Payments</h6>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);"
                                        class="link-danger text-decoration-underline">Clear All</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('institution.payments.index') }}" method="GET" id="filter-form">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Payment Method</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="true">
                                            Select Method
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu w-100">
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="cash" {{ in_array('cash', (array) request('payment_method', [])) ? 'checked' : '' }}>
                                                    Cash
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="online" {{ in_array('online', (array) request('payment_method', [])) ? 'checked' : '' }}>
                                                    Online
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="bank_transfer" {{ in_array('bank_transfer', (array) request('payment_method', [])) ? 'checked' : '' }}>
                                                    Bank Transfer
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="cheque" {{ in_array('cheque', (array) request('payment_method', [])) ? 'checked' : '' }}>
                                                    Cheque
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Student Name</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="student_name" placeholder="Search by student name..." value="{{ request('student_name') }}">
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Class & Section</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="true">
                                            Select Class & Section
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu w-100">
                                            @if(isset($classes) && $classes->count() > 0)
                                                @foreach($classes as $class)
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                            <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="{{ $class->id }}_all" {{ in_array($class->id . '_all', (array) request('class_section', [])) ? 'checked' : '' }}>
                                                            {{ $class->name }} - All Sections
                                                        </label>
                                                    </li>
                                                    @if($class->sections && $class->sections->count() > 0)
                                                        @foreach($class->sections as $section)
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="{{ $class->id }}_{{ $section->id }}" {{ in_array($class->id . '_' . $section->id, (array) request('class_section', [])) ? 'checked' : '' }}>
                                                                    {{ $class->name }} - {{ $section->name }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @else
                                                <li>
                                                    <span class="dropdown-item text-muted">No classes available</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-outline-white me-2"
                                    id="close-filter">Close</button>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <a href="javascript:void(0);"
                    class="dropdown-toggle btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                    data-bs-toggle="dropdown">
                    <i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : 
                    @php
                        $sortBy = request('sort_by', 'newest');
                        $sortLabels = [
                            'newest' => 'Newest',
                            'oldest' => 'Oldest', 
                            'amount_high' => 'Amount (High to Low)',
                            'amount_low' => 'Amount (Low to High)'
                        ];
                    @endphp
                    {{ $sortLabels[$sortBy] ?? 'Newest' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-1">
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1 {{ request('sort_by', 'newest') == 'newest' ? 'active' : '' }}">Newest</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1 {{ request('sort_by') == 'oldest' ? 'active' : '' }}">Oldest</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1 {{ request('sort_by') == 'amount_high' ? 'active' : '' }}">Amount (High to Low)</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1 {{ request('sort_by') == 'amount_low' ? 'active' : '' }}">Amount (Low to High)</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="row">
        <div class="col-12">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-ight">
                            <tr>
                                <th>Student</th>
                                <th>Receipt No.</th>
                                <th>Fee Structure</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                <th class="no-sort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">
                                                    <a href="{{ route('institution.payments.show', $payment->id) }}">
                                                        {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $payment->student->admission_number ?: 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">
                                                    <a href="{{ route('institution.payments.show', $payment->id) }}" class="text-primary">
                                                        {{ $payment->receipt_number }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">{{ $payment->feeStructure->name }}</h6>
                                                <small class="text-muted">{{ $payment->feeStructure->schoolClass->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0 text-success fw-bold">₹{{ number_format($payment->amount, 2) }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'online' ? 'primary' : ($payment->payment_method == 'bank_transfer' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">{{ $payment->payment_date->format('d M Y') }}</h6>
                                                <small class="text-muted">{{ $payment->payment_date->format('h:i A') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <span class="badge bg-success">Completed</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center">
                                            <a href="{{ route('institution.payments.show', $payment->id) }}" 
                                               class="btn btn-icon btn-sm btn-outline-white border-0" title="View Receipt">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('institution.payments.show', $payment->id) }}" 
                                               class="btn btn-icon btn-sm btn-outline-white border-0" title="Download Receipt" 
                                               onclick="window.open(this.href, '_blank'); return false;">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-credit-card-off" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">No Payments Found</h5>
                    <p class="text-muted">No payment records have been created yet.</p>
                    <a href="{{ route('institution.fee-structure.index') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Record First Payment
                    </a>
                </div>
            @endif
          
        </div>
    </div>
    <!-- End Payment History -->

</div>
<!-- End Content -->

<script>
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000); // Hide after 3 seconds

    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Close filter dropdown
        document.getElementById('close-filter')?.addEventListener('click', function() {
            const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
        });

        // Filter form submission
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const params = new URLSearchParams();
                
                // Add form data to params
                for (let [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }
                
                // Redirect with filter parameters
                const url = new URL(window.location);
                url.search = params.toString();
                window.location.href = url.toString();
            });
        }

        // Clear all filters
        const clearAllLink = document.querySelector('a[href="javascript:void(0);"].link-danger');
        if (clearAllLink) {
            clearAllLink.addEventListener('click', function() {
                window.location.href = '{{ route("institution.payments.index") }}';
            });
        }

        // Reset individual filters
        document.querySelectorAll('.link-primary').forEach(function(resetLink) {
            if (resetLink.textContent.trim() === 'Reset') {
                resetLink.addEventListener('click', function() {
                    const parentDiv = this.closest('.mb-3');
                    const checkboxes = parentDiv.querySelectorAll('input[type="checkbox"]');
                    const textInputs = parentDiv.querySelectorAll('input[type="text"]');
                    
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                    
                    textInputs.forEach(function(input) {
                        input.value = '';
                    });
                });
            }
        });

        // Sort functionality
        const sortDropdown = document.querySelector('.dropdown:last-child .dropdown-menu');
        if (sortDropdown) {
            sortDropdown.querySelectorAll('a.dropdown-item').forEach(function(sortLink) {
                sortLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const sortValue = this.textContent.trim().toLowerCase();
                    let sortParam = 'newest';
                    
                    switch(sortValue) {
                        case 'oldest':
                            sortParam = 'oldest';
                            break;
                        case 'amount (high to low)':
                            sortParam = 'amount_high';
                            break;
                        case 'amount (low to high)':
                            sortParam = 'amount_low';
                            break;
                    }
                    
                    // Update sort button text
                    const sortButton = document.querySelector('.dropdown:last-child [data-bs-toggle="dropdown"]');
                    if (sortButton) {
                        sortButton.innerHTML = '<i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : ' + this.textContent.trim();
                    }
                    
                    // Redirect with sort parameter
                    const url = new URL(window.location);
                    url.searchParams.set('sort_by', sortParam);
                    window.location.href = url.toString();
                });
            });
        }

        // Search functionality
        const searchInput = document.querySelector('.datatable-search .input-text');
        if (searchInput) {
            searchInput.addEventListener('click', function() {
                const searchBox = document.createElement('input');
                searchBox.type = 'text';
                searchBox.className = 'form-control form-control-sm';
                searchBox.placeholder = 'Search payments...';
                searchBox.style.width = '200px';
                
                this.parentNode.replaceChild(searchBox, this);
                searchBox.focus();
                
                searchBox.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const url = new URL(window.location);
                        if (this.value.trim()) {
                            url.searchParams.set('student_name', this.value.trim());
                        } else {
                            url.searchParams.delete('student_name');
                        }
                        window.location.href = url.toString();
                    }
                });
                
                searchBox.addEventListener('blur', function() {
                    const searchIcon = document.createElement('a');
                    searchIcon.href = 'javascript:void(0);';
                    searchIcon.className = 'input-text';
                    searchIcon.innerHTML = '<i class="ti ti-search"></i>';
                    
                    this.parentNode.replaceChild(searchIcon, this);
                });
            });
        }
    });
</script>

@endsection

@push('styles')
<style>
    .avatar-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    
    .table-nowrap td {
        white-space: nowrap;
    }
    
    .datatable-search .input-text {
        cursor: pointer;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .datatable-search .input-text:hover {
        border-color: #6366f1;
        color: #6366f1;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .btn-icon:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    
    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }
    
    .fs-14 {
        font-size: 14px;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
        padding: 12px 8px;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
