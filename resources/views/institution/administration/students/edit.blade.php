@extends('layouts.institution')
@section('title', 'Institution | Edit Student')
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
                    <h4 class="fw-bold mb-0">Edit Student</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> IMPORT STUDENT
                        </button>
                        <button type="submit" form="studentForm" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> UPDATE STUDENT
                        </button>
                    </div>
                </div>

                <form id="studentForm" action="{{ route('institution.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
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
                                                               value="{{ old('admission_date', $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : '') }}">
                                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">GROUP</label>
                                                    <select name="group" class="form-control">
                                                        <option value="">Group</option>
                                                        <option value="Science" {{ old('group', $student->group) == 'Science' ? 'selected' : '' }}>Science</option>
                                                        <option value="Arts" {{ old('group', $student->group) == 'Arts' ? 'selected' : '' }}>Arts</option>
                                                        <option value="Commerce" {{ old('group', $student->group) == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ADMISSION NUMBER</label>
                                                    <input type="text" name="admission_number" class="form-control" value="{{ old('admission_number', $student->admission_number) }}" readonly>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ROLL NUMBER</label>
                                                    <input type="text" name="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}" readonly>
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
                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PHONE NUMBER</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
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
                                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', $student->address) }}</textarea>
                            </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PINCODE <span class="text-danger">*</span></label>
                                                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $student->pincode) }}" required>
                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">DISTRICT <span class="text-danger">*</span></label>
                                                    <input type="text" name="district" class="form-control" value="{{ old('district', $student->district) }}" required>
                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PERMANENT ADDRESS</label>
                                                    <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address', $student->permanent_address) }}</textarea>
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
                                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">LAST NAME</label>
                                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">GENDER</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="">Gender</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">DATE OF BIRTH</label>
                                                    <div class="input-group w-auto input-group-flat">
                                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                                               data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                                               value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M, Y') : '') }}">
                                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">RELIGION</label>
                                                    <select name="religion" class="form-control">
                                                        <option value="">Religion</option>
                                                        <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                        <option value="Hinduism" {{ old('religion', $student->religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                                        <option value="Christianity" {{ old('religion', $student->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                                        <option value="Buddhism" {{ old('religion', $student->religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                                                        <option value="Other" {{ old('religion', $student->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CASTE</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="{{ old('caste_tribe', $student->caste_tribe) }}">
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
                                                        <div class="photo-preview mt-2" id="photoPreviewContainer" style="{{ $student->photo ? 'display: block;' : 'display: none;' }}">
                                                            <img id="photoPreview" src="{{ $student->photo ? asset($student->photo) : '' }}" alt="Profile Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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
                                                        <option value="A+" {{ old('blood_group', $student->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                                        <option value="A-" {{ old('blood_group', $student->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                                        <option value="B+" {{ old('blood_group', $student->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                                        <option value="B-" {{ old('blood_group', $student->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                                        <option value="AB+" {{ old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                        <option value="AB-" {{ old('blood_group', $student->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                        <option value="O+" {{ old('blood_group', $student->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                                        <option value="O-" {{ old('blood_group', $student->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CATEGORY</label>
                                                    <select name="category" class="form-control">
                                                        <option value="">Category</option>
                                                        <option value="General" {{ old('category', $student->category) == 'General' ? 'selected' : '' }}>General</option>
                                                        <option value="OBC" {{ old('category', $student->category) == 'OBC' ? 'selected' : '' }}>OBC</option>
                                                        <option value="SC" {{ old('category', $student->category) == 'SC' ? 'selected' : '' }}>SC</option>
                                                        <option value="ST" {{ old('category', $student->category) == 'ST' ? 'selected' : '' }}>ST</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">HEIGHT (CM)</label>
                                                    <input type="text" name="height" class="form-control" value="{{ old('height', $student->height) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">WEIGHT (KG)</label>
                                                    <input type="text" name="weight" class="form-control" value="{{ old('weight', $student->weight) }}">
                                                </div>
                                                
                                            </div>
                                        </div>
                                </div>

                                    <!-- Institution & Teacher -->
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
                                                        <option value="">Teacher</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $student->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CLASS</label>
                                                    <select name="class_id" class="form-control" id="class_id" onchange="loadSections(this.value)">
                                                        <option value="">Class</option>
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
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
                                                            <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                                                {{ $section->name }}
                                                            </option>
                                                        @endforeach
                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">PASSWORD</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep unchanged">
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
                                <!-- Left Column -->
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
                                                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">FATHER OCCUPATION</label>
                                                    <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation', $student->father_occupation) }}">
                                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">FATHER PHONE</label>
                                                    <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone', $student->father_phone) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">FATHERS PHOTO</label>
                                                    <div class="upload-area">
                                                        <input type="file" name="father_photo" id="father_photo" class="d-none" accept="image/*" onchange="previewFatherPhoto(this)">
                                                        <div class="upload-content" onclick="document.getElementById('father_photo').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-camera text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload photo</span>
                                                                <small class="upload-subtitle">JPG, PNG (Max 5MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="fatherPhotoPreviewContainer" style="{{ $student->father_photo ? 'display: block;' : 'display: none;' }}">
                                                            <img id="fatherPhotoPreview" src="{{ $student->father_photo ? asset($student->father_photo) : '' }}" alt="Father Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('father_photo').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFatherPhoto()">Remove</button>
                                                            </div>
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
                                                    <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">MOTHER OCCUPATION</label>
                                                    <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $student->mother_occupation) }}">
                                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">MOTHER PHONE</label>
                                                    <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone', $student->mother_phone) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">MOTHERS PHOTO</label>
                                                    <div class="upload-area">
                                                        <input type="file" name="mother_photo" id="mother_photo" class="d-none" accept="image/*" onchange="previewMotherPhoto(this)">
                                                        <div class="upload-content" onclick="document.getElementById('mother_photo').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-camera text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload photo</span>
                                                                <small class="upload-subtitle">JPG, PNG (Max 5MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="motherPhotoPreviewContainer" style="{{ $student->mother_photo ? 'display: block;' : 'display: none;' }}">
                                                            <img id="motherPhotoPreview" src="{{ $student->mother_photo ? asset($student->mother_photo) : '' }}" alt="Mother Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('mother_photo').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMotherPhoto()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Guardian Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">GUARDIAN INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN NAME</label>
                                                    <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN RELATION</label>
                                                    <select name="guardian_relation" class="form-control" onchange="toggleGuardianRelationText(this.value)">
                                                        <option value="">Relation</option>
                                                        <option value="Father" {{ old('guardian_relation', $student->guardian_relation) == 'Father' ? 'selected' : '' }}>Father</option>
                                                        <option value="Mother" {{ old('guardian_relation', $student->guardian_relation) == 'Mother' ? 'selected' : '' }}>Mother</option>
                                                        <option value="Grandfather" {{ old('guardian_relation', $student->guardian_relation) == 'Grandfather' ? 'selected' : '' }}>Grandfather</option>
                                                        <option value="Grandmother" {{ old('guardian_relation', $student->guardian_relation) == 'Grandmother' ? 'selected' : '' }}>Grandmother</option>
                                                        <option value="Uncle" {{ old('guardian_relation', $student->guardian_relation) == 'Uncle' ? 'selected' : '' }}>Uncle</option>
                                                        <option value="Aunt" {{ old('guardian_relation', $student->guardian_relation) == 'Aunt' ? 'selected' : '' }}>Aunt</option>
                                                        <option value="Other" {{ old('guardian_relation', $student->guardian_relation) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3" id="guardian_relation_text_div" style="display: {{ old('guardian_relation', $student->guardian_relation) == 'Other' ? 'block' : 'none' }};">
                                                    <label class="form-label">RELATION (OTHER)</label>
                                                    <input type="text" name="guardian_relation_text" class="form-control" value="{{ old('guardian_relation_text', $student->guardian_relation_text) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN EMAIL</label>
                                                    <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email', $student->guardian_email) }}">
                                                </div>
                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN PHONE</label>
                                                    <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN OCCUPATION</label>
                                                    <input type="text" name="guardian_occupation" class="form-control" value="{{ old('guardian_occupation', $student->guardian_occupation) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN ADDRESS</label>
                                                    <textarea name="guardian_address" class="form-control" rows="3">{{ old('guardian_address', $student->guardian_address) }}</textarea>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">GUARDIAN PHOTO</label>
                                                    <div class="upload-area">
                                                        <input type="file" name="guardian_photo" id="guardian_photo" class="d-none" accept="image/*" onchange="previewGuardianPhoto(this)">
                                                        <div class="upload-content" onclick="document.getElementById('guardian_photo').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-camera text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload photo</span>
                                                                <small class="upload-subtitle">JPG, PNG (Max 5MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="guardianPhotoPreviewContainer" style="{{ $student->guardian_photo ? 'display: block;' : 'display: none;' }}">
                                                            <img id="guardianPhotoPreview" src="{{ $student->guardian_photo ? asset($student->guardian_photo) : '' }}" alt="Guardian Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('guardian_photo').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGuardianPhoto()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Document Info -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">DOCUMENT INFO</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">STUDENT ID</label>
                                                    <input type="text" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" readonly>
                                                </div>
                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">BIRTH CERTIFICATE NUMBER</label>
                                                    <input type="text" name="birth_certificate_number" class="form-control" value="{{ old('birth_certificate_number', $student->birth_certificate_number) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">ADDITIONAL NOTES</label>
                                                    <textarea name="additional_notes" class="form-control" rows="4">{{ old('additional_notes', $student->additional_notes) }}</textarea>
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
                                                    <input type="text" name="aadhaar_no" class="form-control" value="{{ old('aadhaar_no', $student->aadhaar_no) }}" placeholder="Enter 12-digit Aadhaar number">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">AADHAAR CARD FRONT</label>
                                                    <input type="file" name="aadhaar_front" class="form-control" accept="image/*">
                                                    @if($student->aadhaar_front)
                                                        <small class="text-muted">Current file: {{ basename($student->aadhaar_front) }}</small>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">AADHAAR CARD BACK</label>
                                                    <input type="file" name="aadhaar_back" class="form-control" accept="image/*">
                                                    @if($student->aadhaar_back)
                                                        <small class="text-muted">Current file: {{ basename($student->aadhaar_back) }}</small>
                                                    @endif
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
                                                    <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no', $student->pan_no) }}" placeholder="Enter PAN number">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PAN CARD FRONT</label>
                                                    <input type="file" name="pan_front" class="form-control" accept="image/*">
                                                    @if($student->pan_front)
                                                        <small class="text-muted">Current file: {{ basename($student->pan_front) }}</small>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">PAN CARD BACK</label>
                                                    <input type="file" name="pan_back" class="form-control" accept="image/*">
                                                    @if($student->pan_back)
                                                        <small class="text-muted">Current file: {{ basename($student->pan_back) }}</small>
                                                    @endif
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
                                                    <input type="text" name="pen_no" class="form-control" value="{{ old('pen_no', $student->pen_no) }}" placeholder="Enter PEN number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                
                                <!-- Right Column -->
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
                                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $student->bank_name) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">BANK ACCOUNT NUMBER</label>
                                                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $student->bank_account_number) }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">IFSC CODE</label>
                                                    <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $student->ifsc_code) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <!-- Document Attachment -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="fw-bold mb-0 text-primary">DOCUMENT ATTACHMENT</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">DOCUMENT 01 TITLE</label>
                                                    <input type="text" name="document_01_title" class="form-control" value="{{ old('document_01_title', $student->document_01_title) }}">
                                                    <div class="upload-area mt-2">
                                                        <input type="file" name="document_01_file" id="document_01_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="previewDocument01(this)">
                                                        <div class="upload-content" onclick="document.getElementById('document_01_file').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-file-upload text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload document</span>
                                                                <small class="upload-subtitle">PDF, DOC, JPG, PNG (Max 10MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="document01PreviewContainer" style="{{ $student->document_01_file ? 'display: block;' : 'display: none;' }}">
                                                            <img id="document01Preview" src="{{ $student->document_01_file ? asset($student->document_01_file) : '' }}" alt="Document 01" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_01_file').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument01()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">DOCUMENT 02 TITLE</label>
                                                    <input type="text" name="document_02_title" class="form-control" value="{{ old('document_02_title', $student->document_02_title) }}">
                                                    <div class="upload-area mt-2">
                                                        <input type="file" name="document_02_file" id="document_02_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="previewDocument02(this)">
                                                        <div class="upload-content" onclick="document.getElementById('document_02_file').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-file-upload text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload document</span>
                                                                <small class="upload-subtitle">PDF, DOC, JPG, PNG (Max 10MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="document02PreviewContainer" style="{{ $student->document_02_file ? 'display: block;' : 'display: none;' }}">
                                                            <img id="document02Preview" src="{{ $student->document_02_file ? asset($student->document_02_file) : '' }}" alt="Document 02" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_02_file').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument02()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">DOCUMENT 03 TITLE</label>
                                                    <input type="text" name="document_03_title" class="form-control" value="{{ old('document_03_title', $student->document_03_title) }}">
                                                    <div class="upload-area mt-2">
                                                        <input type="file" name="document_03_file" id="document_03_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="previewDocument03(this)">
                                                        <div class="upload-content" onclick="document.getElementById('document_03_file').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-file-upload text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload document</span>
                                                                <small class="upload-subtitle">PDF, DOC, JPG, PNG (Max 10MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="document03PreviewContainer" style="{{ $student->document_03_file ? 'display: block;' : 'display: none;' }}">
                                                            <img id="document03Preview" src="{{ $student->document_03_file ? asset($student->document_03_file) : '' }}" alt="Document 03" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_03_file').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument03()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">DOCUMENT 04 TITLE</label>
                                                    <input type="text" name="document_04_title" class="form-control" value="{{ old('document_04_title', $student->document_04_title) }}">
                                                    <div class="upload-area mt-2">
                                                        <input type="file" name="document_04_file" id="document_04_file" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="previewDocument04(this)">
                                                        <div class="upload-content" onclick="document.getElementById('document_04_file').click()">
                                                            <div class="upload-icon">
                                                                <i class="ti ti-file-upload text-primary" style="font-size: 24px;"></i>
                                                            </div>
                                                            <div class="upload-text">
                                                                <span class="upload-title">Click to upload document</span>
                                                                <small class="upload-subtitle">PDF, DOC, JPG, PNG (Max 10MB)</small>
                                                            </div>
                                                        </div>
                                                        <div class="photo-preview mt-2" id="document04PreviewContainer" style="{{ $student->document_04_file ? 'display: block;' : 'display: none;' }}">
                                                            <img id="document04Preview" src="{{ $student->document_04_file ? asset($student->document_04_file) : '' }}" alt="Document 04" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                            <div class="photo-actions">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_04_file').click()">Change</button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument04()">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
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

.upload-text {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.upload-title {
    font-size: 16px;
    font-weight: 500;
    color: #495057;
}

.upload-subtitle {
    font-size: 12px;
    color: #6c757d;
}

.photo-preview {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 10px;
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
    margin-top: 8px;
}

.file-info small {
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('custom/js/institution/students.js') }}"></script>
<script>
    // Photo preview function
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
    
    // Remove photo function
    function removePhoto() {
        document.getElementById('photo').value = '';
        document.getElementById('photoPreview').src = '';
        document.getElementById('photoPreviewContainer').style.display = 'none';
    }
    
    // Father photo functions
    function previewFatherPhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('fatherPhotoPreview').src = e.target.result;
                document.getElementById('fatherPhotoPreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeFatherPhoto() {
        document.getElementById('father_photo').value = '';
        document.getElementById('fatherPhotoPreview').src = '';
        document.getElementById('fatherPhotoPreviewContainer').style.display = 'none';
    }
    
    // Mother photo functions
    function previewMotherPhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('motherPhotoPreview').src = e.target.result;
                document.getElementById('motherPhotoPreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeMotherPhoto() {
        document.getElementById('mother_photo').value = '';
        document.getElementById('motherPhotoPreview').src = '';
        document.getElementById('motherPhotoPreviewContainer').style.display = 'none';
    }
    
    // Guardian photo functions
    function previewGuardianPhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('guardianPhotoPreview').src = e.target.result;
                document.getElementById('guardianPhotoPreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeGuardianPhoto() {
        document.getElementById('guardian_photo').value = '';
        document.getElementById('guardianPhotoPreview').src = '';
        document.getElementById('guardianPhotoPreviewContainer').style.display = 'none';
    }
    
    // Document functions
    function previewDocument01(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('document01Preview').src = e.target.result;
                document.getElementById('document01PreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeDocument01() {
        document.getElementById('document_01_file').value = '';
        document.getElementById('document01Preview').src = '';
        document.getElementById('document01PreviewContainer').style.display = 'none';
    }
    
    function previewDocument02(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('document02Preview').src = e.target.result;
                document.getElementById('document02PreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeDocument02() {
        document.getElementById('document_02_file').value = '';
        document.getElementById('document02Preview').src = '';
        document.getElementById('document02PreviewContainer').style.display = 'none';
    }
    
    function previewDocument03(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('document03Preview').src = e.target.result;
                document.getElementById('document03PreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeDocument03() {
        document.getElementById('document_03_file').value = '';
        document.getElementById('document03Preview').src = '';
        document.getElementById('document03PreviewContainer').style.display = 'none';
    }
    
    function previewDocument04(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('document04Preview').src = e.target.result;
                document.getElementById('document04PreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeDocument04() {
        document.getElementById('document_04_file').value = '';
        document.getElementById('document04Preview').src = '';
        document.getElementById('document04PreviewContainer').style.display = 'none';
    }
    
    // Toggle guardian relation text field
    function toggleGuardianRelationText(value) {
        const textDiv = document.getElementById('guardian_relation_text_div');
        if (value === 'Other') {
            textDiv.style.display = 'block';
        } else {
            textDiv.style.display = 'none';
        }
    }
    
    // Set institution ID for JavaScript
    window.institutionId = {{ $institution->id }};
</script>
@endpush