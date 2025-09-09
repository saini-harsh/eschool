@extends('layouts.admin')
@section('title', 'Admin | Edit Institution')
@section('content')
    <!-- Start Content -->
    <div class="content">

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div>
                    <h6 class="mb-3 fs-14">
                        <a href="{{ route('admin.institutions.index') }}">
                            <i class="ti ti-arrow-left me-1"></i>Institutions
                        </a>
                    </h6>

                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Basic Details</h6>
                            </div>
                            <div class="card-body">

                                 <!-- Image Upload -->
                            <div>
                                <label class="form-label">Image</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed position-relative me-3 flex-shrink-0 p-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($institution->logo) }}" class="img-fluid" alt="User Img">
                                        </div>
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <a href="javascript:void(0);" class="btn btn-soft-danger rounded-pill avatar-badge border-0 fs-12" onclick="document.getElementById('profile_image').value = null; this.closest('.avatar').querySelector('img').src = '{{ asset('/admin/img/employees/employee-01.jpg') }}';">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i>Change Image
                                            <input type="file" name="profile_image" id="profile_image" class="form-control image-sign" accept="image/png, image/jpeg">
                                        </div>
                                        <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                    </div>
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Institution Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" 
                                                   value="{{ old('name', $institution->name) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" 
                                                   value="{{ old('email', $institution->email) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" maxlength="20" 
                                                   value="{{ old('phone', $institution->phone) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Established Date <span class="text-danger">*</span></label>
                                            <div class="input-group w-auto input-group-flat">
                                                <input type="text" name="established_date" class="form-control"
                                                       data-provider="flatpickr"
                                                       data-date-format="d M, Y"
                                                       value="{{ old('established_date', \Carbon\Carbon::parse($institution->established_date)->format('d M, Y')) }}"
                                                       required>
                                                <span class="input-group-text">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Board <span class="text-danger">*</span></label>
                                            <input type="text" name="board" class="form-control" 
                                                   value="{{ old('board', $institution->board) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div>
                                            <label class="form-label">Website</label>
                                            <input type="text" name="website" class="form-control" 
                                                   value="{{ old('website', $institution->website) }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Address Details</h6>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control" 
                                                   value="{{ old('address', $institution->address) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="pincode" maxlength="10" class="form-control" 
                                                   value="{{ old('pincode', $institution->pincode) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control" 
                                                   value="{{ old('state', $institution->state) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">District</label>
                                            <input type="text" name="district" class="form-control" 
                                                   value="{{ old('district', $institution->district) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Login Credentials</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control">
                                    <small class="text-muted">Leave blank if you don’t want to change password</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end">
                            <a href="{{ route('admin.institutions.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Institution</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/institutions.js') }}"></script>
@endpush