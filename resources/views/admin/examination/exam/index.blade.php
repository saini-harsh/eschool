@extends('layouts.admin')
@section('title', 'Admin | Exam Management | Exams')
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
                <h5 class="fw-bold">Exams</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exams</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

         <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Exam Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @if(isset($institutions) && count($institutions) > 0)
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            @else
                                <option value="">No institutions found</option>
                            @endif
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field" >
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field" >
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-ight">
                            <tr>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Institution</th>
                                <th>Exam Type</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Morning Time</th>
                                <th>Evening Time</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (isset($lists) && !empty($lists))
                        @foreach ($lists as $exam)
                            <tr>
                                <td>{{ $exam->title }}</td>
                                <td>{{ $exam->code }}</td>
                                <td>
                                    @if($exam->institution)
                                        {{ $exam->institution->name }}
                                    @else
                                        {{ $exam->institution_id }}
                                    @endif
                                </td>
                                <td>
                                    @if($exam->examType)
                                        {{ $exam->examType->name }}
                                    @else
                                        {{ $exam->exam_type_id }}
                                    @endif
                                </td>
                                <td>
                                    @if($exam->class)
                                        {{ $exam->class->name }}
                                    @else
                                        {{ $exam->class_id }}
                                    @endif
                                </td>
                                <td>
                                    @if($exam->section)
                                        {{ $exam->section->name }}
                                    @else
                                        {{ $exam->section_id }}
                                    @endif
                                </td>
                                <td>{{ $exam->start_date }}</td>
                                <td>{{ $exam->end_date }}</td>
                                <td>{{ $exam->morning_time }}</td>
                                <td>{{ $exam->evening_time }}</td>
                                <td>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="" class="btn btn-icon btn-sm btn-outline-white border-0" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="" class="btn btn-icon btn-sm btn-outline-white border-0" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-exam"
                                        data-delete-url=""
                                        data-exam-title="{{ $exam->title }}"
                                        title="Delete">
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

@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/exams.js') }}"></script>
@endpush

