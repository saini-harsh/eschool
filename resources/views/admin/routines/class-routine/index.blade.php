@extends('layouts.admin')
@section('title', 'Admin | Class Routine Management')
@section('content')
@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Class Routine Report</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    <li class="breadcrumb-item active" aria-current="page">Class Routine Report</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.routines.create') }}" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i> Add Routine
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Select Criteria Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="fw-bold mb-0">Select Criteria</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">INSTITUTION <span class="text-danger">*</span></label>
                    <select id="institution_filter" class="form-select">
                        <option value="">Select Institution</option>
                        @if(isset($institutions) && !empty($institutions))
                            @foreach($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">CLASS <span class="text-danger">*</span></label>
                    <select id="class_filter" class="form-select" disabled>
                        <option value="">Select Class</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">SECTION <span class="text-danger">*</span></label>
                    <select id="section_filter" class="form-select" disabled>
                        <option value="">Select Section</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="button" id="search_routine" class="btn btn-primary" disabled>
                        <i class="ti ti-search me-1"></i> SEARCH
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Routine Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Class Routine</h6>
            <button type="button" id="print_routine" class="btn btn-primary btn-sm">
                <i class="ti ti-printer me-1"></i> PRINT
            </button>
        </div>
        <div class="card-body">
            <div id="routine_content">
                <div class="text-center text-muted py-5">
                    <i class="ti ti-calendar-event fs-48 mb-3"></i>
                    <p>Select class and section to view routine</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

<!-- Routine Table Template -->
<template id="routine-template">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th class="text-center">Day</th>
                    <th id="time-header-1" class="text-center">Time Slot 1</th>
                    <th id="time-header-2" class="text-center">Time Slot 2</th>
                    <th id="time-header-3" class="text-center">Time Slot 3</th>
                    <th id="time-header-4" class="text-center">Time Slot 4</th>
                    <th id="time-header-5" class="text-center">Time Slot 5</th>
                    <th id="time-header-6" class="text-center">Time Slot 6</th>
                    <th id="time-header-7" class="text-center">Time Slot 7</th>
                    <th id="time-header-8" class="text-center">Time Slot 8</th>
                </tr>
            </thead>
            <tbody id="routine-tbody">
                <!-- Routine rows will be populated here -->
            </tbody>
        </table>
    </div>
</template>

<!-- Routine Cell Template -->
<template id="routine-cell-template">
    <div class="routine-cell">
        <div class="fw-bold text-primary">__SUBJECT_NAME__</div>
        <div class="text-muted small">__TEACHER_NAME__</div>
        <div class="text-muted small">__CLASS_ROOM__</div>
    </div>
</template>
@endsection

@push('scripts')
<script src="{{ asset('custom/js/admin/routines.js') }}"></script>
@endpush
