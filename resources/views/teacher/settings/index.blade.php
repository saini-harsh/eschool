@extends('layouts.teacher')

@section('title', 'Teacher Settings')

@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Teacher Settings</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="{{ route('teacher.dashboard') }}">
                                <i class="ti ti-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="d-flex align-items-center flex-wrap nav-tab-dark row-gap-2 mb-3" role="tablist">
            <a href="#nav_tab_1" class="btn btn-sm btn-light border active fs-14 me-2" data-bs-toggle="tab" role="tab">Profile Settings</a>
            <a href="#nav_tab_2" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Change Password</a>
        </div>

        <div class="tab-content">
            <!-- Profile Settings Tab -->
            <div class="tab-pane show active" id="nav_tab_1" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Teacher Information</h6>
                            </div>
                            <form id="profile-form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-xxl border border-dashed position-relative me-3 flex-shrink-0 p-2">
                                            <div class="d-felx align-items-center">
                                                <img src="{{ $teacher->profile_image ? asset($teacher->profile_image) : asset('/adminpanel/img/users/avatar-2.jpg') }}" 
                                                     class="img-fluid" alt="Teacher Profile" id="profile-image-preview" style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            <div class="position-absolute top-0 end-0 m-1">
                                                <a href="javascript:void(0);" class="btn btn-soft-danger rounded-pill avatar-badge border-0 fs-12" 
                                                   onclick="deleteProfileImage()">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-inline-flex flex-column align-items-start">
                                            <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                <i class="ti ti-photo me-1"></i>Change Photo
                                                <input type="file" class="form-control image-sign" id="profile-image-input" accept="image/*">
                                            </div>
                                            <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row row-gap-3">
                                        <div class="col-lg-4">
                                            <div class="mb-0">
                                                <label class="form-label">First Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="first_name" value="{{ $teacher->first_name ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-0">
                                                <label class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" name="middle_name" value="{{ $teacher->middle_name ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-0">
                                                <label class="form-label">Last Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="last_name" value="{{ $teacher->last_name ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Email Address<span class="text-danger ms-1">*</span></label>
                                                <input type="email" class="form-control" name="email" value="{{ $teacher->email ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Phone Number<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="phone" value="{{ $teacher->phone ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Date of Birth<span class="text-danger ms-1">*</span></label>
                                                <input type="date" class="form-control" name="dob" value="{{ $teacher->dob ? $teacher->dob->format('Y-m-d') : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Gender<span class="text-danger ms-1">*</span></label>
                                                <select class="form-control" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ $teacher->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ $teacher->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ $teacher->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Caste/Tribe</label>
                                                <input type="text" class="form-control" name="caste_tribe" value="{{ $teacher->caste_tribe ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Employee ID</label>
                                                <input type="text" class="form-control" value="{{ $teacher->employee_id ?? 'Not Assigned' }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h6 class="mb-3">Address Information</h6>

                                    <div class="row row-gap-3">
                                        <div class="col-lg-12">
                                            <div class="mb-0">
                                                <label class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                                <textarea class="form-control" name="address" rows="3" required>{{ $teacher->address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Pin Code<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="{{ $teacher->pincode ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Institution Code</label>
                                                <input type="text" class="form-control" value="{{ $teacher->institution_code ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-white me-2" onclick="resetProfileForm()">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane" id="nav_tab_2" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Change Password</h6>
                            </div>
                            <form id="password-form">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Current Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="current_password" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">New Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="new_password" required minlength="6">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Confirm New Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="new_password_confirmation" required minlength="6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-white me-2" onclick="resetPasswordForm()">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
<script>
// Define URLs for the JavaScript file
const profileUpdateUrl = '{{ route("teacher.settings.profile") }}';
const passwordUpdateUrl = '{{ route("teacher.settings.change-password") }}';
const deleteImageUrl = '{{ route("teacher.settings.delete-profile-image") }}';
const defaultImageUrl = '{{ asset("/adminpanel/img/users/avatar-2.jpg") }}';
</script>
<script src="{{ asset('custom/js/teacher/settings.js') }}"></script>
@endpush
