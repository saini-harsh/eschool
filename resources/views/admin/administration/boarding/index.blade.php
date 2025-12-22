@extends('layouts.admin')
@section('title', 'Admin | Boarding Management')
@section('content')
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Boarding Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('admin.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Boarding</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.boarding.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="institution_id" class="form-label">Institution</label>
                            <select class="form-select" id="institution_id" name="institution_id">
                                <option value="">All Institutions</option>
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by name, admission number...">
                        </div>
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Boarding Students Table -->
        <div class="card">
            <div class="card-body">
                @if ($boardingStudents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Institution</th>
                                    <th>Admission No.</th>
                                    <th>Roll No.</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Added Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($boardingStudents as $boarding)
                                    <tr>
                                        <td>
                                            @if ($boarding->student->photo)
                                                <img src="{{ asset($boarding->student->photo) }}" alt="Photo"
                                                    class="avatar avatar-sm rounded-circle">
                                            @else
                                                <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                                                    {{ strtoupper(substr($boarding->student->first_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $boarding->student->first_name }}
                                                {{ $boarding->student->middle_name }}
                                                {{ $boarding->student->last_name }}</strong>
                                        </td>
                                        <td>
                                            @if ($boarding->institution)
                                                <span class="badge badge-soft-primary">{{ $boarding->institution->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $boarding->student->admission_number ?? 'N/A' }}</td>
                                        <td>{{ $boarding->student->roll_number ?? 'N/A' }}</td>
                                        <td>
                                            @if ($boarding->student->schoolClass)
                                                <span class="badge badge-soft-primary">{{ $boarding->student->schoolClass->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($boarding->student->section)
                                                <span class="badge badge-soft-info">{{ $boarding->student->section->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $boarding->student->email ?? 'N/A' }}</td>
                                        <td>{{ $boarding->student->phone ?? 'N/A' }}</td>
                                        <td>{{ $boarding->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.students.show', $boarding->student->id) }}"
                                                class="btn btn-sm btn-outline-info" title="View Student">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $boardingStudents->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                            <i class="ti ti-home fs-48"></i>
                        </div>
                        <h5 class="text-muted">No Boarding Students</h5>
                        <p class="text-muted">No students have been added to boarding yet.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection
