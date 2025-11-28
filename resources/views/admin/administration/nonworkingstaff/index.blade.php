@extends('layouts.admin')
@section('title', 'Admin | Non-Working Staff Management')

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
            <h5 class="fw-bold">Non-Working Staff</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('admin.nonworkingstaff.index') }}"><i class="ti ti-home me-1"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Non-Working Staff</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.nonworkingstaff.create') }}" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i> New Staff
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="datatable-search">
            <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
        </div>
        <div class="d-flex align-items-center">
            <div class="dropdown me-2">
                <a href="javascript:void(0);" class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="ti ti-filter me-1"></i>Filter
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                    <div class="card mb-0">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0">Filter</h6>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin.nonworkingstaff.index') }}" class="link-danger text-decoration-underline">Clear
                                        All</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.nonworkingstaff.index') }}" method="GET">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Name</label>
                                        <a href="{{ route('admin.nonworkingstaff.index') }}" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <select name="name" id="filter-name" class="form-select">
                                        <option value="">Select</option>
                                        @if(isset($allStaffNames) && $allStaffNames->count())
                                            @foreach ($allStaffNames as $n)
                                                <option value="{{ $n }}" {{ request('name') == $n ? 'selected' : '' }}>{{ $n }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Institution</label>
                                        <a href="{{ route('admin.nonworkingstaff.index') }}" class="link-primary mb-1">Reset</a>
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
                                        <label class="form-label">Designation</label>
                                        <a href="{{ route('admin.nonworkingstaff.index') }}" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <select name="designation" id="filter-designation" class="form-select">
                                        <option value="">Select</option>
                                        @if(isset($designations) && $designations->count())
                                            @foreach ($designations as $d)
                                                <option value="{{ $d }}" {{ request('designation') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Email</label>
                                        <a href="{{ route('admin.nonworkingstaff.index') }}" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <input type="text" name="email" id="filter-email" class="form-control" placeholder="Email" value="{{ request('email') }}">
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-outline-white me-2" id="close-filter">Close</button>
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
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Institution</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($staff) && $staff->count() > 0)
                    @foreach($staff as $member)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-sm avatar-rounded">
                                        <img src="{{ asset($member->profile_image ?? 'admin/img/default.png') }}" alt="img">
                                    </a>
                                    <div class="ms-2">
                                        <h6 class="fs-14 mb-0">
                                            {{ $member->first_name }} {{ $member->last_name }}
                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>
                                <span class="badge bg-info">{{ $member->institution_code }}</span>
                            </td>
                            <td>{{ $member->designation }}</td>
                            <td>
                                <div>
                                    <select class="form-select form-select-sm status-select" data-staff-id="{{ $member->id }}">
                                        <option value="1" {{ $member->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $member->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center">
                                    <a href="{{ route('admin.nonworkingstaff.edit', $member->id) }}" 
                                        class="btn btn-icon btn-sm btn-outline-white border-0">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" 
                                        onclick="confirmDelete(`{{ route('admin.nonworkingstaff.delete', $member->id) }}`)" 
                                        class="btn btn-icon btn-sm btn-outline-white border-0" 
                                        data-bs-toggle="modal" data-bs-target="#delete_modal">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="text-center">No Non-Working Staff Found</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000);
</script>
<!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/nonworkingstaff.js') }}"></script>
@endpush
