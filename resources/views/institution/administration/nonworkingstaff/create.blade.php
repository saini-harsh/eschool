@extends('layouts.institution')
@section('title', 'Institution | Add Non-Working Staff')
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif 

<!-- Start Content -->
<div class="content">

    <!-- start row -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div>
                <h6 class="mb-3 fs-14">
                    <a href="{{ route('institution.nonworkingstaff.index') }}">
                        <i class="ti ti-arrow-left me-1"></i>Non-Working Staff
                    </a>
                </h6>

                <form action="{{ route('institution.nonworkingstaff.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Basic Details</h6>
                        </div> <!-- end card header -->

                        <div class="card-body">

                            <!-- Profile Image -->
                            <div>
                                <label class="form-label">Profile Image</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                        <i class="ti ti-photo text-primary"></i>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i>Upload Image
                                            <input type="file" name="profile_image" class="form-control image-sign">
                                        </div>
                                        <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- start row -->
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <div class="input-group w-auto input-group-flat">
                                            <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                                data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- New: Date of Joining -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Joining <span class="text-danger">*</span></label>
                                        <div class="input-group w-auto input-group-flat">
                                            <input type="text" name="date_of_joining" class="form-control" data-provider="flatpickr"
                                                data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- New: Designation -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="designation" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="select" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Caste / Tribe</label>
                                        <input type="text" name="caste_tribe" class="form-control">
                                    </div>
                                </div>

                                <!-- Institution is automatically set based on logged-in user -->
                                <input type="hidden" name="institution_id" value="{{ $institution->id }}">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card body -->
                    </div> <!-- end card -->

                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="{{ route('institution.nonworkingstaff.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Non-Working Staff</button>
                    </div>
                </form> <!-- end form -->
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div>
<!-- End Content -->
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/institution/nonworkingstaff.js') }}"></script>
@endpush