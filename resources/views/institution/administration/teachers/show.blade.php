@extends('layouts.institution')

@section('title', 'Teacher Details')

@push('styles')
<style>
    .avatar-xxxl {
        width: 120px;
        height: 120px;
    }
    .nav-tab-dark .btn.active {
        background-color: #6366f1;
        color: white;
        border-color: #6366f1;
    }
    .nav-tab-dark .btn {
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }
    .nav-tab-dark .btn:hover {
        background-color: #e9ecef;
        color: #495057;
    }
</style>
@endpush

@section('content')

    <div class="content">
        <!-- start row -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div>
                    <h6 class="mb-3 fs-14"><a href="{{ route('institution.teachers.index') }}"><i class="ti ti-arrow-left me-1"></i>Teachers</a></h6>
                </div>
                
                <!-- Tab Navigation -->
                <div class="d-flex align-items-center flex-wrap row-gap-2 mb-3 pb-1 nav-tab-dark" role="tablist">
                    <a href="#nav_tab_1" class="btn btn-sm btn-light border fs-14 active me-2" data-bs-toggle="tab" role="tab">Personal Info</a>
                    <a href="#nav_tab_2" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Professional Info</a>
                    <a href="#nav_tab_3" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Documents</a>
                    <a href="#nav_tab_4" class="btn btn-sm btn-light border fs-14" data-bs-toggle="tab" role="tab">Settings</a>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane show active" id="nav_tab_1" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-6 d-flex">
                                <div class="card rounded-0 shadow flex-fill mb-xl-0">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            @if($teacher->profile_image)
                                                <span class="avatar avatar-xxxl avatar-rounded mb-3">
                                                    <img src="{{ asset($teacher->profile_image) }}" alt="Teacher Photo">
                                                </span>
                                            @else
                                                <span class="avatar avatar-xxxl avatar-rounded mb-3 bg-light border">
                                                    <i class="ti ti-user fs-48 text-muted"></i>
                                                </span>
                                            @endif
                                            <h6 class="fs-16 mb-1">{{ $teacher->first_name }} {{ $teacher->middle_name }} {{ $teacher->last_name }}</h6>
                                            <p class="mb-0">{{ $teacher->email }}</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <div class="flex-fill">
                                                <div class="bg-light border rounded p-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Date of Birth</h6>
                                                    <p class="mb-0">{{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->format('d M Y') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="bg-light border rounded p-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Gender</h6>
                                                    <p class="mb-0">{{ $teacher->gender ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 d-flex">
                                <div class="card rounded-0 shadow flex-fill mb-0">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 fw-bold">Contact & Address</h6>
                                        <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn p-1 border-0 btn-outline-white">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2 mb-3">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark">{{ $teacher->phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2 mb-3">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark">{{ $teacher->email ?? 'N/A' }}</p>
                                            </div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2 mb-3">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-map-pin"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark">{{ $teacher->address ?? 'N/A' }}</p>
                                            </div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-hash"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark">{{ $teacher->pincode ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Additional Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="bg-light border rounded p-2 mb-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Caste/Tribe</h6>
                                                    <p class="mb-0">{{ $teacher->caste_tribe ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Info Tab -->
                    <div class="tab-pane" id="nav_tab_2" role="tabpanel">
                        <div>
                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Employee ID</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-primary d-inline-flex">
                                                    {{ $teacher->employee_id ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Institution</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-primary d-inline-flex">
                                                    {{ $teacher->institution->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Assigned Admin</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                @if($teacher->admin)
                                                    <span class="avatar avatar-sm avatar-rounded flex-shrink-0 me-2">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                    <p class="mb-0">{{ $teacher->admin->name ?? 'N/A' }}</p>
                                                @else
                                                    <span class="text-muted">No admin assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Status</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                @if($teacher->status)
                                                    <span class="badge badge-soft-success">Active</span>
                                                @else
                                                    <span class="badge badge-soft-danger">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane" id="nav_tab_3" role="tabpanel">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0">Documents</h6>
                                <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn btn-primary fs-12 py-1">
                                    <i class="ti ti-circle-plus me-1"></i>Edit Documents
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-nowrap border">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($teacher->profile_image)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 fs-14">Profile Image</h6>
                                                </td>
                                                <td>
                                                    <i class="ti ti-photo fs-20 text-info"></i>
                                                </td>
                                                <td>
                                                    <a href="{{ asset($teacher->profile_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-download me-1"></i>Download
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ asset($teacher->profile_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!$teacher->profile_image)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="ti ti-file-off fs-48 mb-3 d-block"></i>
                                                    <p class="mb-0">No documents uploaded</p>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane" id="nav_tab_4" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-7">
                                <div class="card rounded-0 mb-xl-0">
                                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                        <h6 class="fw-bold mb-0">Account Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Current Status</h6>
                                                <p class="mb-0 fs-13">
                                                    @if($teacher->status)
                                                        <span class="badge badge-soft-success">Active</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Account Created</h6>
                                                <p class="mb-0 fs-13">{{ $teacher->created_at ? \Carbon\Carbon::parse($teacher->created_at)->format('d M Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Last Updated</h6>
                                                <p class="mb-0 fs-13">{{ $teacher->updated_at ? \Carbon\Carbon::parse($teacher->updated_at)->format('d M Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5">
                                <div class="card rounded-0 mb-0">
                                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                        <h6 class="fw-bold mb-0">Quick Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('institution.teachers.edit', $teacher->id) }}" class="btn btn-primary">
                                                <i class="ti ti-edit me-1"></i>Edit Teacher
                                            </a>
                                            <a href="{{ route('institution.teachers.index') }}" class="btn btn-outline-secondary">
                                                <i class="ti ti-arrow-left me-1"></i>Back to List
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection

@push('scripts')
<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding tab pane
                const targetPane = document.querySelector(this.getAttribute('href'));
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });
</script>
@endpush
