@extends('layouts.admin')
@section('title', 'Admin | School Classes')
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
                <h5 class="fw-bold">Class</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="index.html"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Class</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Class</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="class-form">
                            @csrf
                            <input type="hidden" name="class_id" id="class_id">
                            <div class="mb-3">
                                <label class="form-label">Institution <span class="text-danger">*</span></label>
                                <select name="institution_id" class="select" id="institution_id" required>
                                    @if (isset($institutions) && !empty($institutions))
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Classes <span class="text-danger">*</span></label>
                                <div class="row">
                                    @php
                                        $grades = [
                                            'Nursery',
                                            'LKG',
                                            'UKG',
                                            '1',
                                            '2',
                                            '3',
                                            '4',
                                            '5',
                                            '6',
                                            '7',
                                            '8',
                                            '9',
                                            '10',
                                            '11',
                                            '12',
                                        ];
                                    @endphp
                                    @foreach ($grades as $grade)
                                        <div class="col-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="classes[]" value="{{ $grade }}"
                                                    class="form-check-input class-checkbox" id="class_{{ $grade }}">
                                                <label class="form-check-label"
                                                    for="class_{{ $grade }}">{{ $grade }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="status" value="1" class="form-check-input"
                                        id="class_status" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-class">Submit</button>
                            <button type="button" class="btn btn-success d-none" id="update-class">Update</button>
                            <button type="button" class="btn btn-secondary d-none" id="cancel-edit">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-9">
                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                    <div class="datatable-search">
                        <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter me-1"></i>Filter
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                                <div class="card mb-0">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="fw-bold mb-0">Filter</h6>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.classes.index') }}"
                                                    class="link-danger text-decoration-underline">Clear
                                                    All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.classes.index') }}" method="GET">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Institution</label>
                                                    <a href="{{ route('admin.classes.index') }}" class="link-primary mb-1">Reset</a>
                                                </div>
                                                <select name="institution_id" id="filter-institution" class="form-select">
                                                    <option value="">Select</option>
                                                    @if(isset($institutions) && $institutions->count())
                                                        @foreach ($institutions as $inst)
                                                            <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex align-items-center justify-content-end">
                                            <button type="button" class="btn btn-outline-white me-2"
                                                id="close-filter">Close</button>
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sections</th>
                                <th>Student Count</th> {{-- ✅ New column --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $class)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">{{ $class->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                @php
                                                    $sectionIds = is_array($class->section_ids)
                                                        ? $class->section_ids
                                                        : json_decode($class->section_ids, true);

                                                    $sectionNames = \App\Models\Section::whereIn(
                                                        'id',
                                                        $sectionIds ?: [],
                                                    )
                                                        ->pluck('name')
                                                        ->toArray();
                                                @endphp
                                                <h6 class="fs-14 mb-0">{{ implode(', ', $sectionNames) ?: '-' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">{{ $class->student_count ?? 0 }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select class="form-select class-status-select"
                                                data-class-id="{{ $class->id }}">
                                                <option value="1" {{ $class->status == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ $class->status == 0 ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>

                                        <a href="javascript:void(0);"
                                            class="btn btn-icon btn-sm btn-outline-white border-0 edit-class"
                                            data-class-id="{{ $class->id }}" data-class-name="{{ $class->name }}"
                                            data-institution-id="{{ $class->institution_id }}"
                                            data-section-ids="{{ json_encode($sectionIds) }}"
                                            data-status="{{ $class->status }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="btn btn-icon btn-sm btn-outline-white border-0 delete-class"
                                            data-class-id="{{ $class->id }}" data-class-name="{{ $class->name }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Delete Class</h6>
                    <p>Are you sure you want to delete this class?</p>
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
    <script src="{{ asset('custom/js/admin/schoolclass.js') }}"></script>
@endpush
