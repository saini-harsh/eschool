@extends('layouts.institution')
@section('title', 'institution | Exam Management | Exam Setup')
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
                <h5 class="fw-bold">Exam Setup</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Setup</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Exam</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('institution.exam-management.exam-setup.store') }}" method="post"
                            id="exam-type-form">
                            @csrf
                            <input type="hidden" name="id" id="exam-type-id">
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                    <input type="text" name="institution_id" id="institution_id" class="form-control"
                                        value="{{ auth('institution')->user()->name }}" readonly>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Exam Type <span class="text-danger">*</span></label>
                                    <select name="exam_type" id="exam_type" class="form-select" required>
                                        <option value="">Select Exam Type</option>
                                        @if (isset($data['exam_types']) && !empty($data['exam_types']))
                                            @foreach ($data['exam_types'] as $examType)
                                                <option value="{{ $examType->id }}">{{ $examType->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Enter Exam type title" autocomplete="off" required>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label">Exam Type Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Enter Exam type code" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class_id" id="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                        @if (isset($data['classes']) && !empty($data['classes']))
                                            @foreach ($data['classes'] as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label">Section</label>
                                    <select name="section_id" id="section_id" class="form-select">
                                        <option value="">Select Section</option>

                                    </select>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Month <span class="text-danger">*</span></label>
                                    <select name="exam_month" id="exam_month" class="form-select" required>
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <hr>

                            <h6 class="fw-bold">Schedule Subjects</h6>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered" id="schedule-table">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Morning</th>
                                                <th>Evening</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be injected here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Content -->

    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Lesson Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Delete Lesson Plan</h6>
                    <p>Are you sure you want to delete this lesson plan?</p>
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
    <script src="{{ asset('custom/js/institution/exam-setup.js') }}"></script>
@endpush
