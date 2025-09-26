@extends('layouts.teacher')
@section('title', 'Teacher | Assignment Management')
@section('content')

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Assignment Management</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('teacher.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Routines</li>
                    <li class="breadcrumb-item active" aria-current="page">Assignments</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <!-- Left Side - Form -->
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Add New Assignment</h6>
                </div>
                <div class="card-body">
                    <form id="assignment-form">
                        <input type="hidden" name="assignment_id" id="assignment-id">
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter assignment title" autocomplete="off" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter assignment description (optional)"></textarea>
                        </div>
                        
                        <!-- Institution and Teacher are auto-set from logged-in teacher -->
                        <input type="hidden" name="institution_id" id="institution_id" value="{{ $currentTeacher->institution_id }}">
                        <input type="hidden" name="teacher_id" id="teacher_id" value="{{ $currentTeacher->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                @if (isset($classes) && !empty($classes))
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <select name="section_id" id="section_id" class="form-select" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="text" name="due_date" id="due_date" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Assignment File (PDF/DOC) <span class="text-danger" id="file-required">*</span></label>
                            <input type="file" name="assignment_file" id="assignment_file" class="form-control" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">Upload a PDF or DOC file (Maximum size: 10MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="assignment-status" checked>
                                <label class="form-check-label" for="assignment-status">Active</label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" type="button" id="add-assignment">Submit</button>
                        <button class="btn btn-primary d-none" type="button" id="update-assignment">Update</button>
                        <button class="btn btn-secondary d-none" type="button" id="cancel-edit">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Side - List -->
        <div class="col-9">
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
                                            <a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                                <form action="">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Class</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                    <span class="text-truncate">All Classes</span>
                                                </a>
                                                <ul class="dropdown-menu w-100">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">All Classes</a></li>
                                                    @if(isset($classes) && !empty($classes))
                                                        @foreach($classes as $class)
                                                            <li><a href="javascript:void(0);" class="dropdown-item">{{ $class->name }}</a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Subject</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                    <span class="text-truncate">All Subjects</span>
                                                </a>
                                                <ul class="dropdown-menu w-100">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">All Subjects</a></li>
                                                    @if(isset($subjects) && !empty($subjects))
                                                        @foreach($subjects as $subject)
                                                            <li><a href="javascript:void(0);" class="dropdown-item">{{ $subject->name }}</a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Status</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                    <span class="text-truncate">All Status</span>
                                                </a>
                                                <ul class="dropdown-menu w-100">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">All Status</a></li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Active</a></li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Inactive</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary w-100">Apply Filter</button>
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
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Subject</th>
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
                                                <h6 class="fs-14 mb-0">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($assignment->assignment_file)
                                            <a href="{{ asset('storage/' . $assignment->assignment_file) }}" 
                                               target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View File">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.assignments.download', $assignment->id) }}" 
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
<!-- End Content -->

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

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Delete Assignment</h6>
                <p>Are you sure you want to delete this assignment?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('custom/js/teacher/assignments.js') }}"></script>
@endpush
