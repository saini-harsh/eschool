@extends('layouts.admin')
@section('title', 'Admin | Edit Non-Working Staff')
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
<div class="content">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div>
                <h6 class="mb-3 fs-14">
                    <a href="{{ route('admin.nonworkingstaff.index') }}">
                        <i class="ti ti-arrow-left me-1"></i>Back to Non-Working Staff
                    </a>
                </h6>

                <form action="{{ route('admin.nonworkingstaff.update', $nonworkingstaff->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card rounded-0">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Edit Non-Working Staff Details</h6>
                        </div>
                        
                        <div class="card-body">
                            <!-- Image Upload -->
                            <div>
                                <label class="form-label">Image</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed position-relative me-3 flex-shrink-0 p-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($nonworkingstaff->profile_image ?? 'admin/img/employees/employee-01.jpg') }}" class="img-fluid" alt="User Img">
                                        </div>
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <a href="javascript:void(0);" class="btn btn-soft-danger rounded-pill avatar-badge border-0 fs-12" 
                                               onclick="document.getElementById('profile_image').value = null; this.closest('.avatar').querySelector('img').src = '{{ asset('/admin/img/employees/employee-01.jpg') }}';">
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


                            <!-- Form Fields -->
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $nonworkingstaff->first_name) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $nonworkingstaff->middle_name) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $nonworkingstaff->last_name) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $nonworkingstaff->email) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $nonworkingstaff->phone) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                            data-date-format="Y-m-d"
                                            value="{{ \Carbon\Carbon::parse($nonworkingstaff->dob)->format('Y-m-d') }}">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                </div></div>
                                
                               

                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Joining <span class="text-danger">*</span></label>
                                        <div class="input-group w-auto input-group-flat">
                                            <input type="text" name="date_of_joining" class="form-control"
                                                data-provider="flatpickr" data-date-format="Y-m-d"
                                                value="{{ old('date_of_joining', \Carbon\Carbon::parse($nonworkingstaff->date_of_joining)->format('Y-m-d')) }}">
                                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="designation" class="form-control"
                                            value="{{ old('designation', $nonworkingstaff->designation) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $nonworkingstaff->address) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $nonworkingstaff->pincode) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="select">
                                        <option value="">Select</option>
                                        <option value="Male" {{ $nonworkingstaff->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $nonworkingstaff->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $nonworkingstaff->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Caste / Tribe</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="{{ old('caste_tribe', $nonworkingstaff->caste_tribe) }}">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                    <select name="institution_id" class="select">
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}"
                                                {{ $nonworkingstaff->institution_id == $institution->id ? 'selected' : '' }}>
                                                {{ $institution->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep unchanged">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="select">
                                        <option value="1" {{ $nonworkingstaff->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $nonworkingstaff->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div></div>

                            </div> <!-- end row -->

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="{{ route('admin.nonworkingstaff.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Non-Working Staff</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/nonworkingstaff.js') }}"></script>
@endpush