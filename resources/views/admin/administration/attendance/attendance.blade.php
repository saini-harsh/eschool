@extends('layouts.admin')
@section('title', 'Admin Dashboard | Attendance Management')

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
                    <i class="ti ti-plus me-1"></i>Mark Attendance
                </button>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @if(isset($institutions) && count($institutions) > 0)
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            @else
                                <option value="">No institutions found</option>
                            @endif
                        </select>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field" style="display:none;">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field" style="display:none;">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="col-md-2">
                        <label for="date" class="form-label">Date</label>
                        <input type="text" class="form-control" id="date" name="date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ date('d M, Y') }}">
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
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Institution</th>
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
                                <td colspan="9" class="text-center py-5">
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
    </div>
    <!-- End Content -->

    <!-- Mark Attendance Modal -->
    <div class="modal fade" id="markAttendanceModal" tabindex="-1" aria-labelledby="markAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAttendanceModalLabel">Mark Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mark-attendance-form">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="modal-institution" class="form-label">Institution *</label>
                                <select class="form-select" id="modal-institution" name="institution_id" required>
                                    <option value="">Select Institution</option>
                                    @if(isset($institutions) && count($institutions) > 0)
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No institutions found</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="modal-role" class="form-label">Role *</label>
                                <select class="form-select" id="modal-role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="nonworkingstaff">Non-working Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="modal-class-field" style="display:none;">
                                <label for="modal-class" class="form-label">Class</label>
                                <select class="form-select" id="modal-class" name="class_id">
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="modal-section-field" style="display:none;">
                                <label for="modal-section" class="form-label">Section</label>
                                <select class="form-select" id="modal-section" name="section_id">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="modal-date" class="form-label">Date *</label>
                                <input type="text" class="form-control" id="modal-date" name="date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ date('d M, Y') }}" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-primary mt-4" id="load-users-btn">
                                    <i class="ti ti-users me-1"></i>Load Users
                                </button>
                            </div>
                        </div>

                        <div id="users-list" style="display:none;">
                            <h6 class="mb-3">Mark Attendance for Users</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Roll/ID</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
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
    <script src="{{ asset('custom/js/admin/attendance.js') }}"></script>
@endpush