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
                                    <select class="select">
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
