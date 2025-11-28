@extends('layouts.institution')
@section('title', 'Institution | Exam Invigilator')
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

    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Exam Invigilator</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Invigilator</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Exam Records</h6>
                <form id="exam-filter-form" class="row g-3 align-items-end" method="GET"
                    action="{{ route('institution.exam-management.invigilator.index') }}">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @if (isset($institutions) && count($institutions) > 0)
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}"
                                        {{ auth()->user()->id == $institution->id ? 'selected' : '' }}>
                                        {{ $institution->name }}</option>
                                @endforeach
                            @else
                                <option value="">No institutions found</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2" id="class-field">
                        <label for="exam-type" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam-type" name="exam_type">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="exam-month" class="form-label">Month</label>
                        <select class="form-select" id="exam-month" name="month">
                            <option value="">Select Month</option>
                            <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>January</option>
                            <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>February</option>
                            <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>March</option>
                            <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>May</option>
                            <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>June</option>
                            <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>July</option>
                            <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>August</option>
                            <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                            <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                        </select>
                    </div>
                    <!-- Class Dropdown (for students) -->
                    {{-- <div class="col-md-2" id="class-field">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div> --}}

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('institution.exam-management.exams') }}" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-x me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($examSchedules) && count($examSchedules) > 0)
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">Exam Rooms & Invigilators</h6>
                    <div class="text-muted">Filtered by: {{ request('exam_type') ? 'Type #' . request('exam_type') : 'All' }}
                        {{ request('month') ? '| Month ' . request('month') : '' }}</div>
                </div>
                <div class="card-body">
                    @foreach ($examSchedules as $schedule)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">
                                <i
                                    class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($schedule['date'])->format('d M, Y') }}
                                <span class="text-muted ms-2">{{ $schedule['exam']->title }}</span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">Room</th>
                                            <th style="width: 20%">Capacity</th>
                                            <th style="width: 40%">Assign Teacher</th>
                                            <th style="width: 20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schedule['rooms'] as $room)
                                            @php
                                                $existing = $schedule['assignments'][$room->id] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $room->room_no }}
                                                    {{ $room->room_name ? ' - ' . $room->room_name : '' }}</td>
                                                <td>{{ $room->capacity ?? 'N/A' }}</td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('institution.exam-management.invigilator.assign') }}"
                                                        class="d-flex align-items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="exam_id"
                                                            value="{{ $schedule['exam']->id }}">
                                                        <input type="hidden" name="date"
                                                            value="{{ $schedule['date'] }}">
                                                        <input type="hidden" name="class_room_id"
                                                            value="{{ $room->id }}">
                                                        <select name="teacher_id" class="form-select" required>
                                                            <option value="">Select Teacher</option>
                                                            @foreach ($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}"
                                                                    {{ $existing && $existing->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ $existing ? 'Update' : 'Assign' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    @if ($existing)
                                                        <span class="badge bg-success">Assigned</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unassigned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(request()->filled('exam_type') || request()->filled('month'))
            <div class="alert alert-warning">
                <i class="ti ti-alert-triangle me-1"></i>No exams found for the selected filters.
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/institution/exams.js') }}"></script>
@endpush
