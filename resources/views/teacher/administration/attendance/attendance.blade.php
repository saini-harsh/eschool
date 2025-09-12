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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                    <i class="ti ti-plus me-1"></i>Mark Student Attendance
                </button>
                <button type="button" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#markMyAttendanceModal">
                    <i class="ti ti-user-check me-1"></i>Mark My Attendance
                </button>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Student Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Class Dropdown -->
                    <div class="col-md-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section Dropdown -->
                    <div class="col-md-3" id="section-field" style="display:none;">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-3">
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
                <h6 class="card-title mb-3">Student Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student Name</th>
                                <th>Class/Section</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Marked By</th>
                                <th>Confirmed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <tr>
                                <td colspan="7" class="text-center py-5">
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
                <h6 class="card-title mb-3">My Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Marked By</th>
                                <th>Confirmed</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="my-attendance-table-body">
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    <button type="button" class="btn btn-outline-primary" id="load-my-attendance-btn">
                                        <i class="ti ti-refresh me-1"></i>Load My Attendance
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!-- Mark Student Attendance Modal -->
    <div class="modal fade" id="markAttendanceModal" tabindex="-1" aria-labelledby="markAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAttendanceModalLabel">Mark Student Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mark-attendance-form">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="modal-class" class="form-label">Class *</label>
                                <select class="form-select" id="modal-class" name="class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="modal-section" class="form-label">Section *</label>
                                <select class="form-select" id="modal-section" name="section_id" required>
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="modal-date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="modal-date" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-primary mt-4" id="load-students-btn">
                                    <i class="ti ti-users me-1"></i>Load Students
                                </button>
                            </div>
                        </div>

                        <div id="students-list" style="display:none;">
                            <h6 class="mb-3">Mark Attendance for Students</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Roll Number</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students-table-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-attendance-btn" style="display:none;">
                        <i class="ti ti-check me-1"></i>Save Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark My Attendance Modal -->
    <div class="modal fade" id="markMyAttendanceModal" tabindex="-1" aria-labelledby="markMyAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markMyAttendanceModalLabel">Mark My Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mark-my-attendance-form">
                        <div class="mb-3">
                            <label for="my-date" class="form-label">Date *</label>
                            <input type="date" class="form-control" id="my-date" name="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="my-status" class="form-label">Status *</label>
                            <select class="form-select" id="my-status" name="status" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="my-remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="my-remarks" name="remarks" rows="3" placeholder="Optional remarks"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-my-attendance-btn">
                        <i class="ti ti-check me-1"></i>Save My Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-attendance-form">
                        <input type="hidden" id="edit-attendance-id">
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Status *</label>
                            <select class="form-select" id="edit-status" name="status" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="edit-remarks" name="remarks" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-attendance-btn">
                        <i class="ti ti-check me-1"></i>Update
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/teacher/attendance.js') }}"></script>
@endpush
