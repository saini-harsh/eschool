@extends('layouts.student')
@section('title', 'Student Dashboard | Attendance Management')

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
                            <a href="{{ route('student.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Attendance Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Total Days</h6>
                                <h3 class="mb-0 text-white">{{ $totalDays }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-calendar text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Present</h6>
                                <h3 class="mb-0 text-white">{{ $presentDays }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-check text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Absent</h6>
                                <h3 class="mb-0 text-white">{{ $absentDays }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-x text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Attendance %</h6>
                                <h3 class="mb-0 text-white">{{ $attendancePercentage }}%</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-percentage text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Start Date -->
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="start_date" name="start_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="end_date" name="end_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Records Table Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">My Attendance Records</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Marked By</th>
                                <th>Confirmed</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            @if($attendanceRecords->count() > 0)
                                @foreach($attendanceRecords as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td>
                                            @if($record->status == 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($record->status == 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($record->status == 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @elseif($record->status == 'excused')
                                                <span class="badge bg-info">Excused</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $record->remarks ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($record->marked_by_role ?? 'N/A') }}</td>
                                        <td>
                                            @if($record->is_confirmed)
                                                <span class="badge bg-success">Confirmed</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="text-muted mb-2">No attendance records found</h6>
                                        <p class="text-muted mb-0">Your attendance records will appear here.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
<script src="{{ asset('custom/js/student/attendance.js') }}"></script>
@endpush
