@extends('layouts.teacher')
@section('title', 'Teacher Dashboard | Attendance Management')

@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Attendance Management</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href=""><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('teacher.attendance.mark-page') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Mark Student Attendance
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Role Dropdown -->
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field" style="display:none;">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field" style="display:none;">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- From Date Filter -->
                    <div class="col-md-2">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control" id="from_date" name="from_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ date('d M, Y') }}">
                    </div>

                    <!-- To Date Filter -->
                    <div class="col-md-2">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="text" class="form-control" id="to_date" name="to_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ date('d M, Y') }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th colspan="30" class="text-center">Select filters and click "Filter" to view attendance records</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <tr>
                                <td colspan="30" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No attendance records found</h6>
                                    <p class="text-muted mb-0">Use the filter above to search for attendance records.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- My Attendance Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title mb-3" id="my-attendance-records-title">My Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th colspan="30" class="text-center">My attendance records will sync with the main filter date range</th>
                            </tr>
                        </thead>
                        <tbody id="my-attendance-table-body">
                            <tr>
                                <td colspan="30" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ti ti-user-check text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">My attendance records will load automatically</h6>
                                    <p class="text-muted mb-0">Use the filter above to view your attendance records.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->


@endsection

@push('scripts')
    <script src="{{ asset('custom/js/teacher/attendance.js') }}"></script>
@endpush
