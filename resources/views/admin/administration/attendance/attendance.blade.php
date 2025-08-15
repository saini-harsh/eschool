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
                    <div class="col-md-4">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
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
 <?php

$attendanceRecords = [
    [
        "name" => "John Doe",
        "email" => "john@gmail.com",
        "role" => "Student",
        "institution_name" => "ABC University",
        "date" => "2023-10-01",
        "status" => "present",
    ]
];
?>
                @if (isset($attendanceRecords) && count($attendanceRecords) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Institution</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($attendanceRecords as $record)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm rounded-circle bg-light border me-2">
                                                    <i class="ti ti-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-14">{{ $record['name'] }}</h6>
                                                    <small class="text-muted">{{ $record['email'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($record['role']) }}</span>
                                        </td>
                                        <td>{{ $record['institution_name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record['date'])->format('M d, Y') }}</td>
                                        <td>
                                            @if ($record['status'] == 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($record['status'] == 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($record['status'] == 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($record['status']) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- No Records Message -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="text-muted mb-2">No attendance records found</h6>
                        <p class="text-muted mb-0">Try adjusting your filter criteria or check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script>
document.getElementById('attendance-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const role = document.getElementById('role').value;
    const institution = document.getElementById('institution').value;

    // Only fetch for teacher
    if (role === 'teacher') {
        fetch('/admin/attendance/filter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ role, institution })
        })
        .then(response => response.json())
        .then(data => {
            // Clear existing table rows
            const tbody = document.querySelector('.table tbody');
            tbody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(record => {
                    tbody.innerHTML += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle bg-light border me-2">
                                        <i class="ti ti-user text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fs-14">${record.name}</h6>
                                        <small class="text-muted">${record.email}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">${record.role.charAt(0).toUpperCase() + record.role.slice(1)}</span>
                            </td>
                            <td>${record.institution_name}</td>
                            <td>${record.date}</td>
                            <td>
                                ${record.status === 'present' ? '<span class="badge bg-success">Present</span>' :
                                  record.status === 'absent' ? '<span class="badge bg-danger">Absent</span>' :
                                  record.status === 'late' ? '<span class="badge bg-warning">Late</span>' :
                                  `<span class="badge bg-secondary">${record.status.charAt(0).toUpperCase() + record.status.slice(1)}</span>`
                                }
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">No attendance records found</td></tr>`;
            }
        });
    }
});
</script>
@endpush
