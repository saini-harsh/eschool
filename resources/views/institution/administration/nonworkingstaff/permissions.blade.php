@extends('layouts.institution')
@section('title', 'Institution | Manage Permissions - ' . $staff->first_name . ' ' . $staff->last_name)
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
                <h5 class="fw-bold">Manage Permissions - {{ $staff->first_name }} {{ $staff->last_name }}</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('institution.dashboard') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.nonworkingstaff.index') }}">Non-Working Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Permissions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('institution.nonworkingstaff.index') }}" class="btn btn-outline-primary"><i class="ti ti-arrow-left me-1"></i>Back to List</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Set Permissions for Non-Working Staff</h5>
                        <p class="text-muted mb-0">Select which modules and features this staff member can access. If no permissions are selected, they will have access to all features.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('institution.nonworkingstaff.permissions.update', $staff->id) }}" method="POST">
                            @csrf
                            
                            <div class="alert alert-info mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Note:</strong> These permissions control what the staff member can access in their dashboard.
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Staff Permissions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach(\App\Helpers\PermissionHelper::getStaffPermissions() as $key => $label)
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $key }}" 
                                                       id="permission_{{ $key }}"
                                                       {{ $staff->permissions === null || in_array($key, $staff->permissions ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" id="selectAll">Select All</button>
                                    <button type="button" class="btn btn-outline-secondary" id="deselectAll">Deselect All</button>
                                </div>
                                <div>
                                    <a href="{{ route('institution.nonworkingstaff.index') }}" class="btn btn-outline-primary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection

@push('scripts')
<script>
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Deselect all checkboxes
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Auto-hide toast after 3 seconds
    setTimeout(function() {
        var toastEl = document.querySelector('.toast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl);
            toast.hide();
        }
    }, 3000);
</script>
@endpush

