@extends('layouts.institution')
@section('title', 'Institution | Add Student')
@section('content')

@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif 

<div class="content">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div>
                <h6 class="mb-3 fs-14">
                    <a href="{{ route('institution.students.index') }}">
                        <i class="ti ti-arrow-left me-1"></i> Students
                    </a>
                </h6>

                <!-- Header with Title and Action Buttons -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-bold mb-0">Add Student</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> IMPORT STUDENT
                        </button>
                        <button type="submit" form="studentForm" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> SAVE STUDENT
                        </button>
                    </div>
                </div>

                <form id="studentForm" action="{{ route('institution.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                                PERSONAL INFO
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parents" type="button" role="tab" aria-controls="parents" aria-selected="false">
                                PARENTS & GUARDIAN INFO
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="document-tab" data-bs-toggle="tab" data-bs-target="#document" type="button" role="tab" aria-controls="document" aria-selected="false">
                                DOCUMENT INFO
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="studentTabContent">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Academic Information -->
                                    <div class="card mb-4">
                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">ACADEMIC INFORMATION</h6>
                        </div>
                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ACADEMIC YEAR</label>
                                                    <input type="text" class="form-control" value="2025|2025" readonly>
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ADMISSION DATE</label>
                                                    <div class="input-group w-auto input-group-flat">
                                                        <input type="text" name="admission_date" class="form-control" data-provider="flatpickr"
                                                               data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                                               value="{{ old('admission_date', date('d M, Y')) }}">
                                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">GROUP</label>
                                                    <select name="group" class="form-control">
                                                        <option value="">Group</option>
                                                        <option value="Science">Science</option>
                                                        <option value="Arts">Arts</option>
                                                        <option value="Commerce">Commerce</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ADMISSION NUMBER</label>
                                                    <input type="text" name="admission_number" id="admission_number" class="form-control" value="{{ old('admission_number') }}" readonly placeholder="Auto-generated">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ROLL NUMBER</label>
                                                    <input type="text" name="roll_number" id="roll_number" class="form-control" value="{{ old('roll_number') }}" readonly placeholder="Auto-generated">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">CONTACT INFORMATION</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">EMAIL ADDRESS</label>
                                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PHONE NUMBER</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                                </div>
                                    </div>
                                </div>
                            </div>

                                    <!-- Student Address Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">STUDENT ADDRESS INFO</h6>
                                        </div>
                                        <div class="card-body">
                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">CURRENT ADDRESS <span class="text-danger">*</span></label>
                                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PINCODE <span class="text-danger">*</span></label>
                                                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">DISTRICT <span class="text-danger">*</span></label>
                                                    <input type="text" name="district" class="form-control" value="{{ old('district') }}" required>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PERMANENT ADDRESS</label>
                                                    <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                                </div>
                                </div>
                                </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Personal Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">PERSONAL INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">FIRST NAME</label>
                                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">LAST NAME</label>
                                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">GENDER</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="">Gender</option>
                                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">DATE OF BIRTH</label>
                                                    <div class="input-group w-auto input-group-flat">
                                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                                               data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                                               value="{{ old('dob') }}">
                                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                                    </div>
                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">RELIGION</label>
                                                    <select name="religion" class="form-control">
                                                        <option value="">Religion</option>
                                                        <option value="Islam">Islam</option>
                                                        <option value="Hinduism">Hinduism</option>
                                                        <option value="Christianity">Christianity</option>
                                                        <option value="Buddhism">Buddhism</option>
                                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CASTE</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="{{ old('caste_tribe') }}">
                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Student Photo</label>
                                                    <div class="upload-area">
                                                        <input type="file" name="photo" id="photo" class="d-none" accept="image/*" onchange="previewPhoto(this)">
                                                        <div class="upload-content" onclick="document.getElementById('photo').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-camera text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload photo</span>
                                                                <small class="upload-subtitle">JPG, PNG (Max 5MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="photoPreviewContainer" style="display: none;">
                                                            <img id="photoPreview" src="" alt="Profile Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photo').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePhoto()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                    <!-- Medical Record -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">MEDICAL RECORD</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">BLOOD GROUP</label>
                                                    <select name="blood_group" class="form-control">
                                                        <option value="">Blood Group</option>
                                                        <option value="A+">A+</option>
                                                        <option value="A-">A-</option>
                                                        <option value="B+">B+</option>
                                                        <option value="B-">B-</option>
                                                        <option value="AB+">AB+</option>
                                                        <option value="AB-">AB-</option>
                                                        <option value="O+">O+</option>
                                                        <option value="O-">O-</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CATEGORY</label>
                                                    <select name="category" class="form-control">
                                                        <option value="">Category</option>
                                                        <option value="General">General</option>
                                                        <option value="OBC">OBC</option>
                                                        <option value="SC">SC</option>
                                                        <option value="ST">ST</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">HEIGHT (IN)</label>
                                                    <input type="text" name="height" class="form-control" value="{{ old('height') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">WEIGHT (KG)</label>
                                                                    <input type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Institution and Teacher Assignment -->
                                                    <div class="card mb-4">
                                                        <div class="card-header">
                                                            <h6 class="fw-bold mb-0 text-primary">INSTITUTION & TEACHER</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                <!-- Institution is automatically set based on logged-in user -->
                                <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ASSIGN TEACHER</label>
                                                    <select name="teacher_id" class="form-control" id="teacher_id">
                                                        <option value="">Select Teacher</option>
                                                     </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CLASS</label>
                                                    <select name="class_id" class="form-control" id="class_id" onchange="loadSections(this.value)">
                                                        <option value="">Class</option>
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                                {{ $class->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">SECTION</label>
                                                    <select name="section_id" class="form-control" id="section_id">
                                                        <option value="">Section</option>
                                                        @foreach($sections as $section)
                                                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                                {{ $section->name }}
                                                            </option>
                                                        @endforeach
                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PASSWORD</label>
                                                    <input type="password" name="password" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parents & Guardian Info Tab -->
                        <div class="tab-pane fade" id="parents" role="tabpanel" aria-labelledby="parents-tab">
                            <div class="row">
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary">+ ADD PARENTS</button>
                            </div>
                                <!-- Left Column - Parents Info -->
                                <div class="col-md-6">
                                    <!-- Fathers Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">FATHERS INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">FATHER NAME</label>
                                                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">OCCUPATION</label>
                                                    <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">FATHER PHONE</label>
                                                    <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">FATHERS PHOTO</label>
                                                    <div class="upload-area-small">
                                                        <input type="file" name="father_photo" id="father_photo" class="d-none" accept="image/*">
                                                        <div class="upload-content-small" onclick="document.getElementById('father_photo').click()">
                                                            <i class="ti ti-camera text-primary me-2"></i>
                                                            <span>Upload Photo</span>
                                                        </div>
                                                        <div class="file-info mt-1" id="father_photo_info" style="display: none;">
                                                            <small class="text-success" id="father_photo_name"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mothers Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">MOTHERS INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">MOTHER NAME</label>
                                                    <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">OCCUPATION</label>
                                                    <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">MOTHER PHONE</label>
                                                    <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">MOTHERS PHOTO</label>
                                                    <div class="upload-area-small">
                                                        <input type="file" name="mother_photo" id="mother_photo" class="d-none" accept="image/*">
                                                        <div class="upload-content-small" onclick="document.getElementById('mother_photo').click()">
                                                            <i class="ti ti-camera text-primary me-2"></i>
                                                            <span>Upload Photo</span>
                                                        </div>
                                                        <div class="file-info mt-1" id="mother_photo_info" style="display: none;">
                                                            <small class="text-success" id="mother_photo_name"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Guardian Info -->
                                <div class="col-md-6">

                                    <!-- Guardian Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">GUARDIAN INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">RELATION WITH GUARDIAN</label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="guardian_relation" id="father" value="Father">
                                                            <label class="form-check-label" for="father">Father</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="guardian_relation" id="mother" value="Mother">
                                                            <label class="form-check-label" for="mother">Mother</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="guardian_relation" id="others" value="Others" checked>
                                                            <label class="form-check-label" for="others">Others</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIANS NAME</label>
                                                    <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">RELATION WITH GUARDIAN</label>
                                                    <input type="text" name="guardian_relation_text" class="form-control" value="Other">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIANS EMAIL <span class="text-danger">*</span></label>
                                                    <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN PHOTO</label>
                                                    <div class="upload-area-small">
                                                        <input type="file" name="guardian_photo" id="guardian_photo" class="d-none" accept="image/*">
                                                        <div class="upload-content-small" onclick="document.getElementById('guardian_photo').click()">
                                                            <i class="ti ti-camera text-primary me-2"></i>
                                                            <span>Upload Photo</span>
                                                        </div>
                                                        <div class="file-info mt-1" id="guardian_photo_info" style="display: none;">
                                                            <small class="text-success" id="guardian_photo_name"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIANS PHONE</label>
                                                    <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN OCCUPATION</label>
                                                    <input type="text" name="guardian_occupation" class="form-control" value="{{ old('guardian_occupation') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN ADDRESS</label>
                                                    <textarea name="guardian_address" class="form-control" rows="3">{{ old('guardian_address') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>

                        <!-- Document Info Tab -->
                        <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                            <div class="row">
                                <!-- Left Column - Document Info -->
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">DOCUMENT INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">STUDENT ID</label>
                                                    <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" readonly placeholder="Auto-generated">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Birth Certificate Number</label>
                                                    <input type="text" name="birth_certificate_number" class="form-control" value="{{ old('birth_certificate_number') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">ADDITIONAL NOTES</label>
                                                    <textarea name="additional_notes" class="form-control" rows="4">{{ old('additional_notes') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Aadhaar Card Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">AADHAAR CARD INFORMATION</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">AADHAAR CARD NUMBER</label>
                                                    <input type="text" name="aadhaar_no" class="form-control" value="{{ old('aadhaar_no') }}" placeholder="Enter 12-digit Aadhaar number">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">AADHAAR CARD FRONT</label>
                                                    <input type="file" name="aadhaar_front" class="form-control" accept="image/*">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">AADHAAR CARD BACK</label>
                                                    <input type="file" name="aadhaar_back" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PAN Card Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">PAN CARD INFORMATION</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PAN CARD NUMBER</label>
                                                    <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') }}" placeholder="Enter PAN number">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PAN CARD FRONT</label>
                                                    <input type="file" name="pan_front" class="form-control" accept="image/*">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PAN CARD BACK</label>
                                                    <input type="file" name="pan_back" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PEN Number -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">PEN NUMBER</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PEN NUMBER</label>
                                                    <input type="text" name="pen_no" class="form-control" value="{{ old('pen_no') }}" placeholder="Enter PEN number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Birth Certificate -->
                                <div class="col-md-6">

                                    <!-- Bank Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">BANK INFORMATION</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">BANK NAME</label>
                                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">BANK ACCOUNT NUMBER</label>
                                                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number') }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">IFSC CODE</label>
                                                    <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                            <!-- Document Attachment -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="fw-bold mb-0 text-primary">DOCUMENT ATTACHMENT</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">DOCUMENT 01 TITLE</label>
                                            <input type="text" name="document_01_title" class="form-control" value="{{ old('document_01_title') }}">
                                            <div class="document-upload mt-2">
                                                <input type="file" name="document_01_file" id="document_01_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="document-upload-btn" onclick="document.getElementById('document_01_file').click()">
                                                    <i class="ti ti-file-upload text-primary me-1"></i>
                                                    <span>Upload File</span>
                                                </div>
                                                <div class="file-info mt-1" id="document_01_info" style="display: none;">
                                                    <small class="text-success" id="document_01_name"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">DOCUMENT 02 TITLE</label>
                                            <input type="text" name="document_02_title" class="form-control" value="{{ old('document_02_title') }}">
                                            <div class="document-upload mt-2">
                                                <input type="file" name="document_02_file" id="document_02_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="document-upload-btn" onclick="document.getElementById('document_02_file').click()">
                                                    <i class="ti ti-file-upload text-primary me-1"></i>
                                                    <span>Upload File</span>
                                                </div>
                                                <div class="file-info mt-1" id="document_02_info" style="display: none;">
                                                    <small class="text-success" id="document_02_name"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">DOCUMENT 03 TITLE</label>
                                            <input type="text" name="document_03_title" class="form-control" value="{{ old('document_03_title') }}">
                                            <div class="document-upload mt-2">
                                                <input type="file" name="document_03_file" id="document_03_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="document-upload-btn" onclick="document.getElementById('document_03_file').click()">
                                                    <i class="ti ti-file-upload text-primary me-1"></i>
                                                    <span>Upload File</span>
                                                </div>
                                                <div class="file-info mt-1" id="document_03_info" style="display: none;">
                                                    <small class="text-success" id="document_03_name"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">DOCUMENT 04 TITLE</label>
                                            <input type="text" name="document_04_title" class="form-control" value="{{ old('document_04_title') }}">
                                            <div class="document-upload mt-2">
                                                <input type="file" name="document_04_file" id="document_04_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <div class="document-upload-btn" onclick="document.getElementById('document_04_file').click()">
                                                    <i class="ti ti-file-upload text-primary me-1"></i>
                                                    <span>Upload File</span>
                                                </div>
                                                <div class="file-info mt-1" id="document_04_info" style="display: none;">
                                                    <small class="text-success" id="document_04_name"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Upload Area Styles */
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.upload-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #e7f1ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.upload-title {
    font-weight: 600;
    color: #495057;
    font-size: 14px;
}

.upload-subtitle {
    color: #6c757d;
    font-size: 12px;
}

.photo-preview {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.photo-actions {
    display: flex;
    gap: 8px;
}

/* Small Upload Area for Parent/Guardian Photos */
.upload-area-small {
    border: 1px dashed #dee2e6;
    border-radius: 6px;
    padding: 12px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area-small:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.upload-content-small {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    color: #495057;
    font-weight: 500;
}

/* Document Upload Styles */
.document-upload {
    border: 1px dashed #dee2e6;
    border-radius: 6px;
    padding: 10px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.document-upload:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.document-upload-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    color: #495057;
    font-weight: 500;
}

.file-info {
    text-align: center;
}

.file-info small {
    font-size: 11px;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
    <script src="{{ asset('custom/js/institution/students.js') }}"></script>
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                    document.getElementById('photoPreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePhoto() {
            document.getElementById('photo').value = '';
            document.getElementById('photoPreview').src = '';
            document.getElementById('photoPreviewContainer').style.display = 'none';
        }

        // Set institution ID for JavaScript
        window.institutionId = {{ $institution->id }};

        // Auto-generate admission number and roll number
        function generateAdmissionAndRollNumbers() {
            const institutionId = window.institutionId;
            const classId = document.querySelector('select[name="class_id"]').value;
            const sectionId = document.querySelector('select[name="section_id"]').value;
            
            if (institutionId && classId && sectionId) {
                // Generate admission number
                fetch('/institution/students/generate-admission-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        institution_id: institutionId,
                        class_id: classId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('admission_number').value = data.admission_number;
                    }
                })
                .catch(error => console.error('Error generating admission number:', error));
                
                // Generate roll number
                fetch('/institution/students/generate-roll-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        section_id: sectionId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('roll_number').value = data.roll_number;
                    }
                })
                .catch(error => console.error('Error generating roll number:', error));
            }
        }
        
        // Add event listeners to class and section dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.querySelector('select[name="class_id"]');
            const sectionSelect = document.querySelector('select[name="section_id"]');
            
            if (classSelect) {
                classSelect.addEventListener('change', generateAdmissionAndRollNumbers);
            }
            if (sectionSelect) {
                sectionSelect.addEventListener('change', generateAdmissionAndRollNumbers);
            }
        });

        // Photo file selection handlers
        document.getElementById('father_photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('father_photo_name').textContent = fileName;
            document.getElementById('father_photo_info').style.display = fileName ? 'block' : 'none';
        });

        document.getElementById('mother_photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('mother_photo_name').textContent = fileName;
            document.getElementById('mother_photo_info').style.display = fileName ? 'block' : 'none';
        });

        document.getElementById('guardian_photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('guardian_photo_name').textContent = fileName;
            document.getElementById('guardian_photo_info').style.display = fileName ? 'block' : 'none';
        });

        // Document file selection handlers
        document.getElementById('document_01_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('document_01_name').textContent = fileName;
            document.getElementById('document_01_info').style.display = fileName ? 'block' : 'none';
        });

        document.getElementById('document_02_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('document_02_name').textContent = fileName;
            document.getElementById('document_02_info').style.display = fileName ? 'block' : 'none';
        });

        document.getElementById('document_03_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('document_03_name').textContent = fileName;
            document.getElementById('document_03_info').style.display = fileName ? 'block' : 'none';
        });

        document.getElementById('document_04_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('document_04_name').textContent = fileName;
            document.getElementById('document_04_info').style.display = fileName ? 'block' : 'none';
        });

        // Auto-hide toast notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toast) {
                const bsToast = new bootstrap.Toast(toast, {
                    autohide: true,
                    delay: 5000
                });
                bsToast.show();
            });
        });
    </script>
@endpush