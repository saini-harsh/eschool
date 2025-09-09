@extends('layouts.admin')
@section('title', 'Admin Dashboard | Attendance')

@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Attendance</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href=""><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Institution Dropdown -->
                    <div class="col-md-3">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="col-md-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
                    </div>

                    <div id="student-data-fields" class="col-md-6" style="display:none;">
                        <div class="col-md-5" style="margin-right: 20px;">
                            <label for="role" class="form-label">Class</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">Select Class</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                                <option value="nonworkingstaff">Non-working Staff</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="role" class="form-label">Section</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">Select Section</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                                <option value="nonworkingstaff">Non-working Staff</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i>Submit
                        </button>
                        <button type="reset" class="btn btn-secondary ms-2">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Attendance Records</h6>
                 <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                    <div class="datatable-search">
                        <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                    </div>
                    <div class="d-flex align-items-center">
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
                                            <h6 class="fw-bold mb-0">Filter</h6>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);"
                                                    class="link-danger text-decoration-underline">Clear
                                                    All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Name</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        @if(isset($institutions) && !empty($institutions))
                                                            @foreach ($institutions as $institution)
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        {{ $institution->name }}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Status</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <li>
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Active
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Inactive
                                                            </label>
                                                        </li>
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
                        <div>
                            <div class="dropdown">
                                <a href="javascript:void(0);"
                                    class="dropdown-toggle btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : Newest
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-1">
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Class</th>
                                <th>Institution</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            @if (isset($attendanceRecords) && count($attendanceRecords) > 0)
                                @foreach ($attendanceRecords as $record)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm rounded-circle bg-light border me-2">
                                                    <i class="ti ti-user text-muted"></i>
                                                </div>
                                                <div>
                                                    @if ($record->role === 'student' && $record->student)
                                                        <h6 class="mb-0 fs-14">{{ $record->student->first_name .' '.$record->student->last_name }}</h6>
                                                        <small class="text-muted">{{ $record->student->email }}</small>
                                                    @elseif ($record->role === 'teacher' && $record->teacher)
                                                        <h6 class="mb-0 fs-14">{{ $record->teacher->first_name .' '.$record->teacher->last_name }}</h6>
                                                        <small class="text-muted">{{ $record->teacher->email }}</small>
                                                    @elseif ($record->role === 'nonworkingstaff' && $record->staff)
                                                        <h6 class="mb-0 fs-14">{{ $record->staff->first_name .' '.$record->staff->last_name }}</h6>
                                                        <small class="text-muted">{{ $record->staff->email }}</small>
                                                    @else
                                                        <h6 class="mb-0 fs-14">N/A</h6>
                                                        <small class="text-muted">N/A</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($record->role) }}</span>
                                        </td>
                                        <td>{{ $record->institution->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td>
                                            @if ($record->status == 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($record->status == 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($record->status == 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div id="no-records-message" class="text-center py-5" style="display: none;">
                    <div class="mb-3">
                        <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">No attendance records found</h6>
                    <p class="text-muted mb-0">Try adjusting your filter criteria or check back later.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/attendance.js') }}"></script>
@endpush