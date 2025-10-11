@extends('layouts.institution')
@section('title', 'Institution | Exam Management | Room Details')

@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Room Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="{{ route('institution.dashboard') }}">
                                <i class="ti ti-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('institution.exam-management.rooms.index') }}">Exam Rooms</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Room Details</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('institution.exam-management.rooms.edit', $classRoom->id) }}" class="btn btn-warning">
                    <i class="ti ti-edit me-1"></i>Edit Room
                </a>
                <a href="{{ route('institution.exam-management.rooms.design-layout', $classRoom->id) }}"
                    class="btn btn-success">
                    <i class="ti ti-layout-grid me-1"></i>Design Layout
                </a>
                <a href="{{ route('institution.exam-management.rooms.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Rooms
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Room Details -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Room Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Room Number:</label>
                                    <p class="mb-0">{{ $classRoom->room_no }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Room Name:</label>
                                    <p class="mb-0">{{ $classRoom->room_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Capacity:</label>
                                    <p class="mb-0">{{ $classRoom->capacity }} students</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Students per Bench:</label>
                                    <p class="mb-0">{{ $classRoom->students_per_bench ?? 1 }} student(s)</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <p class="mb-0">
                                        <span class="badge {{ $classRoom->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $classRoom->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Created:</label>
                                    <p class="mb-0">
                                        {{ $classRoom->created_at ? $classRoom->created_at->format('M d, Y H:i') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($classRoom->seatmap)
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Seat Layout:</label>
                                        <div class="alert alert-info">
                                            <i class="ti ti-info-circle me-1"></i>
                                            This room has a custom seat layout configured.
                                            <a href="{{ route('institution.exam-management.rooms.design-layout', $classRoom->id) }}"
                                                class="text-decoration-none">
                                                View or edit the layout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
