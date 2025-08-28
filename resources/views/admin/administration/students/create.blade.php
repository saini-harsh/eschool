@extends('layouts.admin')
@section('title', 'Admin | Add Student')
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
                    <a href="{{ route('admin.students.index') }}">
                        <i class="ti ti-arrow-left me-1"></i> Students
                    </a>
                </h6>

                <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 

                    <div class="card rounded-0">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Student Details</h6>
                        </div>
                        <div class="card-body">

                            {{-- Photo --}}
                            <div class="mb-3">
                                <label class="form-label">Profile Photo</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                        <i class="ti ti-photo text-primary"></i>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i> Upload Photo
                                            <input type="file" name="photo" class="form-control image-sign" accept="image/*">
                                        </div>
                                        <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Fields --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                               data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender')=='Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Caste / Tribe</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="{{ old('caste_tribe') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" name="district" class="form-control" value="{{ old('district') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                    <select name="institution_id" class="form-select">
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}" {{ old('institution_id')==$institution->id ? 'selected' : '' }}>
                                                {{ $institution->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Section</label>
                                    <select name="section_id" class="form-select">
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assign Teacher</label>
                                    <select name="teacher_id" class="form-select">
                                        <option value="">-- None --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id')==$teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                 <!-- Already existing invite checkbox -->
                                 <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label fw-normal" for="customCheck1">Send them an
                                            invite email so they can log in immediately</label>
                                    </div>
                                </div>

                            </div> {{-- end row --}}
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
