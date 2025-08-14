@extends('layouts.admin')
@section('title', 'Admin | Teachers Management')
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
                <h5 class="fw-bold">Teachers</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="index.html"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Teachers</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.add-teacher') }}" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>New
                Teacher</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="datatable-search">
                <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
            </div>
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center border rounded table-grid me-2">
                    <a href="employees.html" class="btn p-1 btn-primary"><i class="ti ti-list"></i></a>
                    <a href="employees-grid.html" class="btn p-1"><i class="ti ti-layout-grid"></i></a>
                </div>
                <div class="dropdown me-2">
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
                                        <a href="javascript:void(0);"
                                            class="link-danger text-decoration-underline">Clear All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="#">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="true">
                                                Select
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        John Carter
                                                    </label>
                                                </li>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Sophia White
                                                    </label>
                                                </li>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Michael Johnson
                                                    </label>
                                                </li>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Emily Clark
                                                    </label>
                                                </li>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        David Anderson
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Status</label>
                                            <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="true">
                                                Select
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Active
                                                    </label>
                                                </li>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Inactive
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
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
                <div class="dropdown">
                    <a href="javascript:void(0);"
                        class="dropdown-toggle btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown">
                        <i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : Newest
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-1">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                        </li>
                    </ul>
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
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($teachers) && !empty($teachers))
                @foreach ($teachers as $teacher)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="employee-details.html" class="avatar avatar-sm avatar-rounded">
                                <img src="{{ asset($teacher->profile_image) }}"
                                        alt="img">
                                </a>
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="employee-details.html">{{ $teacher->first_name }} {{ $teacher->last_name }}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- <a href="javascript:void(0);" class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('admin/img/managers/manager-01.jpg') }}" alt="img">
                                </a> -->
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="javascript:void(0);">{{ $teacher->email }}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>{{ $teacher->phone }}</td>
                        @php
                        $badgeClasses = ['badge-soft-orange', 'badge-soft-warning', 'badge-soft-info', 'badge-soft-danger', 'badge-soft-secondary'];
                        $randomBadgeClass = $badgeClasses[array_rand($badgeClasses)];
                    @endphp
                        <td>
                        <!-- <a href="{{URL::to('/admin/agents/view/'.base64_encode(convert_uuencode(@$agentdetail->id)))}}"> -->
                            <span class="badge {{ $randomBadgeClass }}">{{ $teacher->institution_code }}</span>
                        <!-- </a>     -->

                        </td>
                        <td>
                            <div>
                                <select class="select">
                                <option value="1" {{ $teacher->status === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $teacher->status === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="{{ route('admin.edit-teacher', $teacher->id) }}" class="btn btn-icon btn-sm btn-outline-white border-0"><i
                                        class="ti ti-edit"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-outline-white border-0"
                                    data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                        class="ti ti-trash"></i></a>
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
