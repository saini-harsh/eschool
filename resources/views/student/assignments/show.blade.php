@extends('layouts.student')
@section('title', 'Student | Assignment Details')
@section('content')

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Assignment Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('student.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.assignments.index') }}">Assignments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Assignments
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <!-- Assignment Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">{{ $assignment->title }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Subject:</small>
                            <p class="fw-semibold mb-0">{{ $assignment->subject->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Teacher:</small>
                            <p class="fw-semibold mb-0">{{ $assignment->teacher->first_name ?? 'N/A' }} {{ $assignment->teacher->last_name ?? '' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Class & Section:</small>
                            <p class="fw-semibold mb-0">{{ $assignment->schoolClass->name ?? 'N/A' }} - {{ $assignment->section->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Due Date:</small>
                            <p class="fw-semibold mb-0 {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
                                @if(\Carbon\Carbon::parse($assignment->due_date)->isPast())
                                    <small class="text-danger">(Overdue)</small>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($assignment->description)
                        <div class="mb-3">
                            <small class="text-muted">Description:</small>
                            <div class="mt-1 p-3 bg-light rounded">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>
                    @endif

                    @if($assignment->assignment_file)
                        <div class="mb-3">
                            <small class="text-muted">Assignment File:</small>
                            <div class="mt-1">
                                <a href="{{ route('student.assignments.download-assignment', $assignment->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="ti ti-download me-1"></i>Download Assignment File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submission Form -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Submit Assignment</h6>
                </div>
                <div class="card-body">
                    @if($studentSubmission)
                        <!-- Already Submitted -->
                        <div class="alert alert-success">
                            <h6 class="fw-bold mb-2">Assignment Submitted</h6>
                            <p class="mb-2">
                                <small class="text-muted">Submitted on:</small><br>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($studentSubmission->submission_date)->format('M d, Y H:i') }}</span>
                            </p>
                            <p class="mb-2">
                                <small class="text-muted">Status:</small><br>
                                <span class="badge bg-{{ $studentSubmission->status == 'submitted' ? 'success' : 'warning' }}">
                                    {{ ucfirst($studentSubmission->status) }}
                                </span>
                            </p>
                            @if($studentSubmission->submitted_file)
                                <p class="mb-2">
                                    <small class="text-muted">Submitted File:</small><br>
                                    <a href="{{ route('student.assignments.download-submission', $studentSubmission->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-download me-1"></i>Download My Submission
                                    </a>
                                </p>
                            @endif
                            @if($studentSubmission->marks)
                                <p class="mb-2">
                                    <small class="text-muted">Marks:</small><br>
                                    <span class="fw-bold text-success fs-5">{{ $studentSubmission->marks }}/100</span>
                                </p>
                            @endif
                            @if($studentSubmission->feedback)
                                <p class="mb-0">
                                    <small class="text-muted">Feedback:</small><br>
                                    <span class="small">{{ $studentSubmission->feedback }}</span>
                                </p>
                            @endif
                        </div>
                    @else
                        <!-- Submission Form -->
                        <form id="assignment-submission-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Upload Assignment File <span class="text-danger">*</span></label>
                                <input type="file" name="submitted_file" id="submitted_file" class="form-control" accept=".pdf,.doc,.docx" required>
                                <div class="form-text">Upload a PDF or DOC file (Maximum size: 10MB)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Remarks (Optional)</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Add any remarks or notes about your submission"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" id="submit-assignment">
                                <i class="ti ti-upload me-1"></i>Submit Assignment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('assignment-submission-form');
        const submitBtn = document.getElementById('submit-assignment');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const fileInput = document.getElementById('submitted_file');
                
                if (!fileInput.files[0]) {
                    toastr.error('Please select a file to upload');
                    return;
                }
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ti ti-loader me-1"></i>Submitting...';
                
                fetch('{{ route("student.assignments.submit", $assignment->id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(data.message);
                        if (data.errors) {
                            Object.values(data.errors).forEach(error => {
                                toastr.error(error[0]);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred while submitting the assignment');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-upload me-1"></i>Submit Assignment';
                });
            });
        }
    });
</script>
@endpush
