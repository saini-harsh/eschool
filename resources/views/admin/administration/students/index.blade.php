@extends('layouts.admin')
@section('title', 'Admin | Students Management')
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
                <h5 class="fw-bold">Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.students.index') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>New Student</a>
            </div>
        </div>
        <!-- End Page Header -->

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
                                        <a href="{{ route('admin.students.index') }}"
                                            class="link-danger text-decoration-underline">Clear All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.students.index') }}" method="GET">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="{{ route('admin.students.index') }}" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="name" id="filter-name" class="form-select">
                                            <option value="">Select</option>
                                            @if(isset($allStudentNames) && $allStudentNames->count())
                                                @foreach ($allStudentNames as $n)
                                                    <option value="{{ $n }}" {{ request('name') == $n ? 'selected' : '' }}>{{ $n }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Institution</label>
                                            <a href="{{ route('admin.students.index') }}" class="link-primary mb-1">Reset</a>
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
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Teacher</label>
                                            <a href="{{ route('admin.students.index') }}" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="teacher_id" id="filter-teacher" class="form-select">
                                            <option value="">Select</option>
                                            @if(isset($teachers) && $teachers->count())
                                                @foreach ($teachers as $t)
                                                    <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->first_name }} {{ $t->last_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Email</label>
                                            <a href="{{ route('admin.students.index') }}" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <input type="text" name="email" id="filter-email" class="form-control" placeholder="Student email" value="{{ request('email') }}">
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
                <thead class="thead-ight">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Insitution</th>
                        <th>Teacher</th>
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($students) && !empty($students))
                @foreach ($students as $student)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset($student->photo) }}" alt="img">
                                </a>
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="{{ route('admin.students.show', $student->id) }}">{{ $student->first_name }} {{ $student->last_name }}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="javascript:void(0);">{{ $student->email }}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->phone }}</td>
                        
                        <td>
                        <!-- <a href="{{URL::to('/admin/agents/view/'.base64_encode(convert_uuencode(@$agentdetail->id)))}}"> -->
                            <span class="badge badge-soft-secondary">
                                @if($student->institution)
                                    {{ $student->institution->name }}
                                @else
                                    {{ $student->institution_code }}
                                @endif
                            </span>
                        <!-- </a>     -->

                        </td>
                        <td>
                        <!-- <a href="{{URL::to('/admin/agents/view/'.base64_encode(convert_uuencode(@$agentdetail->id)))}}"> -->
                            <span class="badge badge-soft-orange">
                                @if($student->teacher)
                                    {{ $student->teacher->first_name }} {{ $student->teacher->last_name }}
                                @else
                                    Not Assigned
                                @endif
                            </span>
                        <!-- </a>     -->

                        </td>
                        <td>
                            <div>
                                <select class="form-select form-select-sm status-select" data-student-id="{{ $student->id }}">
                                    <option value="1" {{ $student->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $student->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-icon btn-sm btn-outline-white border-0" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-icon btn-sm btn-outline-white border-0" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-student" 
                                   data-delete-url="{{ route('admin.students.delete', $student->id) }}"
                                   data-student-name="{{ $student->first_name }} {{ $student->last_name }}"
                                   title="Delete">
                                    <i class="ti ti-trash"></i>
                                </a>
                                <a href="{{ route('admin.students.download-pdf', $student->id) }}" class="btn btn-icon btn-sm btn-outline-success border-0" title="Download PDF">
                                    <i class="ti ti-file-type-pdf"></i>
                                </a>
                                <a href="{{ route('admin.students.print-id-card', $student->id) }}" target="_blank" class="btn btn-icon btn-sm btn-outline-primary border-0" title="Print ID Card">
                                    <i class="ti ti-printer"></i>
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



<script>
    // Auto-hide existing toast notifications
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000); // Hide after 3 seconds
</script>
<!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/students.js') }}"></script>
@endpush
