@extends('layouts.institution')
@section('title', 'Admission List')
@section('content')

    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Admission Forms</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <i class="ti ti-home me-1"></i>Home
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('institution.students.index') }}">Students</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Admissions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('institution.admission.admission-form') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>New Admission
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="ti ti-filter me-2"></i>Filters</h6>
                <a href="{{ route('institution.admission.list') }}" class="link-danger text-decoration-underline">
                    <i class="ti ti-x me-1"></i>Clear All
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('institution.admission.list') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control"
                                value="{{ request('search') }}" placeholder="Name, Admission No, Phone, Email...">
                        </div>

                        <!-- Class Filter -->
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Admission Date From -->
                        <div class="col-md-2">
                            <label for="admission_date_from" class="form-label">Admission Date From</label>
                            <input type="date" name="admission_date_from" id="admission_date_from" class="form-control"
                                value="{{ request('admission_date_from') }}">
                        </div>

                        <!-- Admission Date To -->
                        <div class="col-md-2">
                            <label for="admission_date_to" class="form-label">Admission Date To</label>
                            <input type="date" name="admission_date_to" id="admission_date_to" class="form-control"
                                value="{{ request('admission_date_to') }}">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admissions Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Admission Forms ({{ $admissions->total() }})</h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#excelPreviewModal">
                        <i class="ti ti-eye me-1"></i>Excel Preview
                    </button>
                    <a href="{{ route('institution.admission.export', request()->query()) }}"
                        class="btn btn-success btn-sm">
                        <i class="ti ti-file-excel me-1"></i>Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($admissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Admission No</th>
                                    <th>Class</th>
                                    <th>Phone</th>
                                    <th>Admission Date</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admissions as $admission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($admission->photo)
                                                    <img src="{{ asset($admission->photo) }}" alt="Photo"
                                                        class="rounded-circle me-2"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                                        {{ strtoupper(substr($admission->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $admission->first_name }}
                                                        {{ $admission->last_name }}</h6>
                                                    @if ($admission->email)
                                                        <small class="text-muted">{{ $admission->email }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $admission->admission_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $admission->schoolClass->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $admission->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if ($admission->admission_date)
                                                {{ \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            {{ $admission->created_at->format('d M, Y') }}
                                            <br>
                                            <small
                                                class="text-muted">{{ $admission->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            @if ($admission->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($admission->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('institution.admission.show', $admission->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('institution.admission.print', $admission->id) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-secondary"
                                                    title="Print Form">
                                                    <i class="ti ti-printer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $admissions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                            <i class="ti ti-file-text fs-24"></i>
                        </div>
                        <h5 class="text-muted">No Admissions Found</h5>
                        <p class="text-muted">
                            @if (request()->anyFilled(['search', 'class_id', 'admission_date_from', 'admission_date_to']))
                                Try adjusting your filters or
                            @endif
                            <a href="{{ route('institution.admission.admission-form') }}">submit a new admission form</a>.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Excel Preview Modal -->
    <div class="modal fade" id="excelPreviewModal" tabindex="-1" aria-labelledby="excelPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excelPreviewModalLabel">Excel Preview - Admission List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="{{ route('institution.admission.preview-excel', request()->query()) }}"
                        style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('institution.admission.export', request()->query()) }}" class="btn btn-success">
                        <i class="ti ti-download me-1"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit form on filter change (optional)
            document.addEventListener('DOMContentLoaded', function() {
                // Optional: Auto-submit on filter change
                // const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
                // filterInputs.forEach(input => {
                //     input.addEventListener('change', function() {
                //         document.getElementById('filterForm').submit();
                //     });
                // });
            });
        </script>
    @endpush

@endsection
