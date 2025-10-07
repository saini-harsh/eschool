@extends('layouts.institution')
@section('title', 'institution | Exam Management | Exam Type')
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
                <h5 class="fw-bold">Exam Type</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Type</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <!-- Left Side - Create Form -->
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Exam Type</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="exam-type-form">
                            @csrf
                            <input type="hidden" name="id" id="exam-type-id">
                            <div class="mb-3">
                                <label class="form-label">Institution <span class="text-danger">*</span></label>

                                <input type="text" name="institution_id" id="institution_id" class="form-control"
                                    value="{{ auth('institution')->user()->name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter Exam type title" autocomplete="off" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Exam Type Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control"
                                    placeholder="Enter Exam type code" autocomplete="off" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="2"
                                    placeholder="Enter Exam type description (optional)"></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        id="exam-type-status" checked>
                                    <label class="form-check-label" for="exam-type-status">Active</label>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="button" id="add-exam-type">Submit</button>
                            <button class="btn btn-primary d-none" type="button" id="update-exam-type">Update</button>
                            <button class="btn btn-secondary d-none" type="button" id="cancel-edit">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side - List -->
            <div class="col-9">
                <?php
                $someVariable = 'Some Value';
                ?>
                @include('components.dashboard.filters', [
                    'filterId' => 'exam-type-filter',
                    'customData' => $someVariable,
                ])

                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Institution</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($lists) && !empty($lists))
                                @foreach ($lists as $list)
                                    <tr data-exam-type-id="{{ $list->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="fs-14 mb-0">{{ $list->code }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="fs-14 mb-0">{{ $list->title }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $list->institution ? $list->institution->name : 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($list->description)
                                                {{ Str::limit($list->description, 50) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <select class="form-select status-select exam-type-status-select"
                                                    data-exam-type-id="{{ $list->id }}"
                                                    data-original-value="{{ $list->status }}">
                                                    <option value="1" {{ $list->status == 1 ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0" {{ $list->status == 0 ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-inline-flex align-items-center">
                                                <a href="javascript:void(0);" data-exam-type-id="{{ $list->id }}"
                                                    class="btn btn-icon btn-sm btn-outline-white border-0 edit-exam-type">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" data-exam-type-id="{{ $list->id }}"
                                                    data-exam-type-title="{{ $list->title }}"
                                                    class="btn btn-icon btn-sm btn-outline-white border-0 delete-exam-type">
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
    <script src="{{ asset('custom/js/institution/exam-types.js') }}"></script>
@endpush
