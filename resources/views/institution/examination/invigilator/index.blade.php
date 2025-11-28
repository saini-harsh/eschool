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
                    action="{{ route('institution.exam-management.exams') }}">
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
                        <label for="class" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam-type" name="exam_type">

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

    </div>
@endsection
