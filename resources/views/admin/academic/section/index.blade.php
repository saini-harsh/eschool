@extends('layouts.admin')
@section('title', 'Admin | Sections Management')
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
                <h5 class="fw-bold">Sections</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="index.html"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sections</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Section</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="section-form">
                            @csrf
                            <input type="hidden" name="section_id" id="section_id">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Institution <span class="text-danger">*</span></label>
                                        <select class="form-select" name="institution_id" id="institution_id" required>
                                            <option value="">Select Institution</option>
                                            @if (isset($institutions) && !empty($institutions))
                                                @foreach ($institutions as $institution)
                                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" id="section_name" class="form-control"
                                            placeholder="Enter section name" autocomplete="off">
                                    </div>
                                </div> --}}
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Sections <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach (range('A', 'Z') as $letter)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sections[]"
                                                        id="section_{{ $letter }}" value="{{ $letter }}">
                                                    <label class="form-check-label" for="section_{{ $letter }}">{{ $letter }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                                id="section_status" checked>
                                            <label class="form-check-label" for="section_status">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="button" id="add-section">Submit</button>
                            <button class="btn btn-success d-none" type="button" id="update-section">Update</button>
                            <button class="btn btn-secondary d-none" type="button" id="cancel-edit">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                    <div>
                        <select class="form-select" name="filter_institution_id" id="filter_institution_id" required>
                            <option value="">Select Institution</option>
                            @if (isset($institutions) && !empty($institutions))
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-ight">
                            <tr>
                                <th>Institution</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sections-table-body">

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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Delete Section</h6>
                    <p>Are you sure you want to delete this section?</p>
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
    <script src="{{ asset('custom/js/admin/sections.js') }}"></script>
@endpush
