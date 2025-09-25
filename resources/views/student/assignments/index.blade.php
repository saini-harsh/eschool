@extends('layouts.student')
@section('title', 'Student | Assignments')
@section('content')

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">My Assignments</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('student.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Assignments</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Class: {{ $student->schoolClass->name ?? 'N/A' }} - Section: {{ $student->section->name ?? 'N/A' }}</span>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        @if($assignments->count() > 0)
            @foreach($assignments as $assignment)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">{{ $assignment->title }}</h6>
                            <span class="badge bg-{{ $assignment->studentAssignments->first() ? 
                                ($assignment->studentAssignments->first()->status == 'submitted' ? 'success' : 
                                ($assignment->studentAssignments->first()->status == 'late' ? 'warning' : 'info')) : 'secondary' }}">
                                {{ $assignment->studentAssignments->first() ? 
                                    ucfirst($assignment->studentAssignments->first()->status) : 'Pending' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Subject:</small>
                                <span class="fw-semibold">{{ $assignment->subject->name ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Teacher:</small>
                                <span class="fw-semibold">{{ $assignment->teacher->first_name ?? 'N/A' }} {{ $assignment->teacher->last_name ?? '' }}</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Due Date:</small>
                                <span class="fw-semibold {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
                                </span>
                            </div>
                            @if($assignment->description)
                                <div class="mb-3">
                                    <small class="text-muted">Description:</small>
                                    <p class="mb-0 text-muted small">{{ Str::limit($assignment->description, 100) }}</p>
                                </div>
                            @endif
                            
                            @if($assignment->studentAssignments->first())
                                <div class="mb-3">
                                    <small class="text-muted">Submitted:</small>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($assignment->studentAssignments->first()->submission_date)->format('M d, Y H:i') }}</span>
                                </div>
                                @if($assignment->studentAssignments->first()->marks)
                                    <div class="mb-3">
                                        <small class="text-muted">Marks:</small>
                                        <span class="fw-semibold text-success">{{ $assignment->studentAssignments->first()->marks }}/100</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('student.assignments.show', $assignment->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="ti ti-eye me-1"></i>View Details
                                </a>
                                @if($assignment->assignment_file)
                                    <a href="{{ route('student.assignments.download-assignment', $assignment->id) }}" 
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="ti ti-download me-1"></i>Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-file-off fs-48 text-muted"></i>
                        </div>
                        <h5 class="fw-bold">No Assignments Found</h5>
                        <p class="text-muted">You don't have any assignments assigned to your class and section yet.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- End Content -->

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any assignment-specific JavaScript here
        console.log('Student Assignments page loaded');
    });
</script>
@endpush
