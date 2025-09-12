@extends('layouts.teacher')
@section('title', 'Teacher | My Classes')
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
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">My Classes</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('teacher.dashboard') }}">
                            <i class="ti ti-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Classes</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Welcome back, {{ $teacher->first_name . ' ' . $teacher->last_name }}</span>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="datatable-search">
                    <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted">Total Classes: {{ $classes->count() }}</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Sections</th>
                            <th>Number of Students</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0">{{ $class['name'] }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0">
                                                {{ $class['sections']->pluck('name')->implode(', ') ?: 'No sections assigned' }}
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0">{{ $class['student_count'] }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($class['status'] == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-school text-muted" style="font-size: 3rem;"></i>
                                        <h6 class="mt-2 text-muted">No classes assigned</h6>
                                        <p class="text-muted mb-0">You don't have any classes assigned to you yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection