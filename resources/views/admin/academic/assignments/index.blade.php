@extends('layouts.admin')

@section('title', 'Assignment Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Assignment Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Assignment Management</h5>
                        <button class="btn btn-primary" id="add-assignment-btn">
                            <i class="ti ti-plus me-1"></i>Add Assignment
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Assignment Form -->
                    <div class="row mb-4" id="assignment-form-container" style="display: none;">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0" id="form-title">Add New Assignment</h6>
                                </div>
                                <div class="card-body">
                                    <form id="assignment-form">
                                        @csrf
                                        <input type="hidden" name="assignment_id" id="assignment-id">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter assignment title" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                                    <select name="institution_id" id="institution_id" class="form-select" required>
                                                        <option value="">Select Institution</option>
                                                        @foreach($institutions as $institution)
                                                            <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter assignment description (optional)"></textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Class <span class="text-danger">*</span></label>
                                                    <select name="class_id" id="class_id" class="form-select" required>
                                                        <option value="">Select Class</option>
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Section <span class="text-danger">*</span></label>
                                                    <select name="section_id" id="section_id" class="form-select" required>
                                                        <option value="">Select Section</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                                    <select name="subject_id" id="subject_id" class="form-select" required>
                                                        <option value="">Select Subject</option>
                                                        @foreach($subjects as $subject)
                                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Teacher <span class="text-danger">*</span></label>
                                                    <select name="teacher_id" id="teacher_id" class="form-select" required>
                                                        <option value="">Select Teacher</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                                    <input type="text" name="due_date" id="due_date" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Assignment File</label>
                                                    <input type="file" name="assignment_file" id="assignment_file" class="form-control" accept=".pdf,.doc,.docx">
                                                    <small class="text-muted">Supported formats: PDF, DOC, DOCX (Max: 10MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="status" value="1" id="assignment-status" checked>
                                                <label class="form-check-label" for="assignment-status">Active</label>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-fill" id="submit-btn">
                                                <i class="ti ti-plus me-1"></i>Add Assignment
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="cancel-btn" style="display: none;">
                                                <i class="ti ti-x me-1"></i>Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignments Table -->
                    <div class="table-responsive">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                    <div class="datatable-search">
                        <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
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
                                                <a href="{{ route('admin.assignments.index') }}"
                                                    class="link-danger text-decoration-underline">Clear All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="" id="filter-form" method="GET">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Institution</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="institution_ids">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Institution
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        @if(isset($institutions) && !empty($institutions))
                                                            @foreach ($institutions as $institution)
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                               name="institution_ids[]" value="{{ $institution->id }}" 
                                                                               {{ in_array($institution->id, request('institution_ids', [])) ? 'checked' : '' }}>
                                                                        {{ $institution->name }}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        @endif
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
                    </div>
                </div>
                        <table class="table table-nowrap datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Institution</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Due Date</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($assignments) && !empty($assignments))
                                    @foreach ($assignments as $assignment)
                                        <tr data-assignment-id="{{ $assignment->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->title }}</h6>
                                                        @if($assignment->description)
                                                            <small class="text-muted">{{ Str::limit($assignment->description, 30) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->institution->name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->schoolClass->name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->section->name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->subject->name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ $assignment->teacher->first_name ?? 'N/A' }} {{ $assignment->teacher->last_name ?? '' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 mb-0">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($assignment->assignment_file)
                                                    <a href="{{ asset($assignment->assignment_file) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View File">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.assignments.download-assignment', $assignment->id) }}" 
                                                       class="btn btn-sm btn-outline-success" title="Download File">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">No file</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input status-toggle" 
                                                           data-assignment-id="{{ $assignment->id }}" 
                                                           {{ $assignment->status ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="javascript:void(0);" data-assignment-id="{{ $assignment->id }}"
                                                        class="btn btn-icon btn-sm btn-outline-white border-0 view-submissions" 
                                                        title="View Submissions ({{ $assignment->studentAssignments->count() }})">
                                                        <i class="ti ti-users"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" data-assignment-id="{{ $assignment->id }}"
                                                        class="btn btn-icon btn-sm btn-outline-white border-0 edit-assignment">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" data-assignment-id="{{ $assignment->id }}"
                                                        data-assignment-title="{{ $assignment->title }}"
                                                        class="btn btn-icon btn-sm btn-outline-white border-0 delete-assignment">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submissions Modal -->
<div class="modal fade" id="submissions_modal" tabindex="-1" aria-labelledby="submissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submissionsModalLabel">Student Submissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="submissions-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="grade_modal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">Grade Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="grade-form">
                    @csrf
                    <input type="hidden" id="grade-student-assignment-id">
                    <input type="hidden" id="grade-assignment-id">

                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" id="grade-student-name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marks <span class="text-danger">*</span></label>
                        <input type="number" id="grade-marks" class="form-control" min="0" max="100" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Feedback</label>
                        <textarea id="grade-feedback" class="form-control" rows="3" placeholder="Add feedback for the student"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-grade">Submit Grade</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('custom/js/admin/assignments.js') }}"></script>
@endpush
