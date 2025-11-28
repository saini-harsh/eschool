@extends('layouts.admin')
@section('title', 'Admin | Institutions Management')
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
                <h5 class="fw-bold">Institutions</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.institutions.create') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Institutions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.institutions.create') }}" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>New Institution</a>
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
                                        <a href="{{ route('admin.institutions.index') }}" class="link-danger text-decoration-underline">Clear
                                            All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.institutions.index') }}" method="GET">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="{{ route('admin.institutions.index') }}" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="name" id="filter-name" class="form-select">
                                            <option value="">Select</option>
                                            @if(isset($allInstitutionNames) && $allInstitutionNames->count())
                                                @foreach ($allInstitutionNames as $n)
                                                    <option value="{{ $n }}" {{ request('name') == $n ? 'selected' : '' }}>{{ $n }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Email</label>
                                            <a href="{{ route('admin.institutions.index') }}" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <input type="text" name="email" id="filter-email" class="form-control" placeholder="Institution email" value="{{ request('email') }}">
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
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($institutions) && !empty($institutions))
                        @foreach ($institutions as $institution)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="company-details.html"
                                            class="avatar avatar-sm rounded-circle bg-light border">
                                            <img src="{{ asset($institution->logo) }}"
                                                class="w-auto h-auto" alt="img">
                                        </a>
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0"><a
                                                    href="company-details.html">{{ $institution->name }}</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="https://dleohr.dreamstechnologies.com/cdn-cgi/l/email-protection"
                                        class="__cf_email__"
                                        data-cfemail="43202c2d37222037032d263b22202c31266d202c2e">{{ $institution->email }}</a>
                                </td>
                                <td>{{ $institution->phone }}</td>
                                <td>
                                    <div>
                                        <select class="select">
                                        <option value="1" {{ $institution->status === 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $institution->status === 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="{{ route('admin.institutions.edit', $institution->id) }}"
                                            class="btn btn-icon btn-sm btn-outline-white border-0"><i
                                                class="ti ti-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="confirmDelete(`{{ route('admin.students.delete', $institution->id) }}`)"
                                            class="btn btn-icon btn-sm btn-outline-white border-0" data-bs-toggle="modal"
                                            data-bs-target="#delete_modal"><i class="ti ti-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/institutions.js') }}"></script>
@endpush
