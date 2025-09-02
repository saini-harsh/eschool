@extends('layouts.admin')
@section('title', 'Admin | Edit Student')
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

                <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card rounded-0">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Edit Student Details</h6>
                        </div>
                        <div class="card-body">

                            {{-- Photo --}}
                            <div class="mb-3">
                                <label class="form-label">Profile Photo</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0 p-2">
                                        <img src="{{ asset($student->photo ?? 'admin/img/employees/employee-01.jpg') }}" class="img-fluid" alt="Student Photo">
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i> Change Photo
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
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $student->middle_name) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                               data-date-format="d M, Y"
                                               value="{{ old('dob', \Carbon\Carbon::parse($student->dob)->format('d M, Y')) }}">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $student->address) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $student->pincode) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Caste / Tribe</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="{{ old('caste_tribe', $student->caste_tribe) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" name="district" class="form-control" value="{{ old('district', $student->district) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                    <select name="institution_id" id="institution_id" class="form-select">
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}" {{ old('institution_id', $student->institution_id) == $institution->id ? 'selected' : '' }}>
                                                {{ $institution->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class_id" id="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Section</label>
                                    <select name="section_id" id="section_id" class="form-select">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution Code</label>
                                    <input type="text" name="institution_code" class="form-control" value="{{ $student->institution_code }}" readonly>
                                    <small class="text-muted">Auto-generated based on selected institution</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assign Teacher</label>
                                    <select name="teacher_id" id="teacher_id" class="form-select">
                                        <option value="">-- None --</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep unchanged">
                                </div>
                                
                                {{-- Hidden inputs to store current values for JavaScript --}}
                                <input type="hidden" id="current_class_id" value="{{ old('class_id', $student->class_id) }}">
                                <input type="hidden" id="current_section_id" value="{{ old('section_id', $student->section_id) }}">
                                <input type="hidden" id="current_teacher_id" value="{{ old('teacher_id', $student->teacher_id) }}">
                            </div> {{-- end row --}}
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script src="{{ asset('custom/js/assign-teacher.js') }}"></script>
<script>
    // Pre-populate form with existing student data
    $(document).ready(function() {
        // Wait for students.js to initialize, then pre-populate
        setTimeout(function() {
            var currentClassId = $('#current_class_id').val();
            var currentSectionId = $('#current_section_id').val();
            var currentTeacherId = $('#current_teacher_id').val();
            
            console.log('Current values:', {
                classId: currentClassId,
                sectionId: currentSectionId,
                teacherId: currentTeacherId
            });
            
            // Set the values in the hidden inputs for the prePopulateEditForm function
            $('#class_id').val(currentClassId);
            $('#section_id').val(currentSectionId);
            $('#teacher_id').val(currentTeacherId);
            
            // Trigger the pre-population
            if (currentClassId || currentSectionId || currentTeacherId) {
                prePopulateEditForm();
            }
        }, 500);
    });
</script>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/students.js') }}"></script>
@endpush