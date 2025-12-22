@extends('layouts.institution')
@section('title', 'Institution | Boarding Management')
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

    @if (session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
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
                <h5 class="fw-bold">Boarding Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Boarding</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('institution.boarding.create') }}" class="btn btn-primary"><i
                        class="ti ti-circle-plus me-1"></i>Add Student to Boarding</a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('institution.boarding.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by name, admission number...">
                        </div>
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select class="form-select" id="section_id" name="section_id">
                                <option value="">All Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i
                                    class="ti ti-search me-1"></i>Filter</button>
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
                                        <td>{{ $boarding->student->admission_number ?? 'N/A' }}</td>
                                        <td>{{ $boarding->student->roll_number ?? 'N/A' }}</td>
                                        <td>
                                            @if ($boarding->student->schoolClass)
                                                <span
                                                    class="badge badge-soft-primary">{{ $boarding->student->schoolClass->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($boarding->student->section)
                                                <span
                                                    class="badge badge-soft-info">{{ $boarding->student->section->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $boarding->student->email ?? 'N/A' }}</td>
                                        <td>{{ $boarding->student->phone ?? 'N/A' }}</td>
                                        <td>{{ $boarding->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('institution.students.show', $boarding->student->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="View Student">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('institution.boarding.edit', $boarding->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('institution.boarding.delete', $boarding->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to remove this student from boarding?');">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Remove from Boarding">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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
                        <a href="{{ route('institution.boarding.create') }}" class="btn btn-primary">
                            <i class="ti ti-circle-plus me-1"></i>Add Student to Boarding
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script>
        // Auto-hide toast after 3 seconds
        setTimeout(function() {
            var toastEl = document.querySelector('.toast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.hide();
            }
        }, 3000);

        // Load sections when class changes
        document.getElementById('class_id')?.addEventListener('change', function() {
            const classId = this.value;
            const sectionSelect = document.getElementById('section_id');

            if (classId) {
                fetch(`{{ url('institution/sections/list') }}?class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        sectionSelect.innerHTML = '<option value="">All Sections</option>';
                        if (data.sections) {
                            data.sections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                sectionSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                sectionSelect.innerHTML = '<option value="">All Sections</option>';
            }
        });
    </script>
@endpush
