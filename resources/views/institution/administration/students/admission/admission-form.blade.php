@extends('layouts.institution')
@section('title', 'Institution | Add Student')
@section('content')

    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
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
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
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
                        <h4 class="fw-bold mb-0">Admission Form</h4>
                        <div class="d-flex gap-2">
                            <button type="submit" form="studentForm" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> SUBMIT FORM
                            </button>
                        </div>
                    </div>


                    <form id="studentForm" action="{{ route('institution.admission.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                    data-bs-target="#personal" type="button" role="tab" aria-controls="personal"
                                    aria-selected="true">
                                    PERSONAL INFO
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parents"
                                    type="button" role="tab" aria-controls="parents" aria-selected="false">
                                    PARENTS & GUARDIAN INFO
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="document-tab" data-bs-toggle="tab" data-bs-target="#document"
                                    type="button" role="tab" aria-controls="document" aria-selected="false">
                                    DOCUMENT INFO
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                                    type="button" role="tab" aria-controls="payment" aria-selected="false">
                                    PAYMENT
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hostel-tab" data-bs-toggle="tab" data-bs-target="#hostel"
                                    type="button" role="tab" aria-controls="hostel" aria-selected="false">
                                    HOSTEL PAYMENT
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="studentTabContent">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                aria-labelledby="personal-tab">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <!-- Academic Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary"> ACADEMIC INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ACADEMIC YEAR</label>
                                                        <input type="text" class="form-control" value="2026"
                                                            readonly>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ADMISSION DATE</label>
                                                        <div class="input-group w-auto input-group-flat">
                                                            <input type="text" name="admission_date"
                                                                class="form-control" data-provider="flatpickr"
                                                                data-date-format="d M, Y" placeholder="dd/mm/yyyy"
                                                                value="{{ old('admission_date', date('d M, Y')) }}">
                                                            <span class="input-group-text"><i
                                                                    class="ti ti-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-6 mb-3">
                                                        <label class="form-label">GROUP</label>
                                                        <select name="group" class="form-control">
                                                            <option value="">Group</option>
                                                            <option value="Science">Science</option>
                                                            <option value="Arts">Arts</option>
                                                            <option value="Commerce">Commerce</option>
                                                        </select>
                                                    </div> --}}
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ADMISSION NUMBER</label>
                                                        <input type="text" name="admission_number"
                                                            id="admission_number" class="form-control"
                                                            value="{{ old('admission_number') }}" readonly
                                                            placeholder="Auto-generated">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ROLL NUMBER</label>
                                                        <input type="text" name="roll_number" id="roll_number"
                                                            class="form-control" value="{{ old('roll_number') }}" readonly
                                                            placeholder="Auto-generated">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">CLASS</label>
                                                        <select name="class_id" class="form-control" id="class_id"
                                                            onchange="loadSections(this.value)">
                                                            <option value="" disabled selected>Class</option>
                                                            @foreach ($classes as $class)
                                                                <option value="{{ $class->id }}"
                                                                    {{ old('previous_school_class') == $class->id ? 'selected' : '' }}>
                                                                    {{ Str::of($class->name)->replaceMatches('/\b\d+\b/', function ($match) {
                                                                        $n = (int) $match[0];
                                                                        $roman = '';
                                                                        $map = [
                                                                            'M' => 1000,
                                                                            'CM' => 900,
                                                                            'D' => 500,
                                                                            'CD' => 400,
                                                                            'C' => 100,
                                                                            'XC' => 90,
                                                                            'L' => 50,
                                                                            'XL' => 40,
                                                                            'X' => 10,
                                                                            'IX' => 9,
                                                                            'V' => 5,
                                                                            'IV' => 4,
                                                                            'I' => 1,
                                                                        ];
                                                                        foreach ($map as $romanChar => $value) {
                                                                            while ($n >= $value) {
                                                                                $roman .= $romanChar;
                                                                                $n -= $value;
                                                                            }
                                                                        }
                                                                        return $roman;
                                                                    }) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">PEN NUMBER</label>
                                                        <input type="text" name="pen_no" class="form-control"
                                                            value="{{ old('pen_no') }}" placeholder="Enter PEN number">
                                                    </div>
                                                    {{-- <div class="col-md-6 mb-3">
                                                        <label class="form-label">SECTION</label>
                                                        <select name="section_id" class="form-control" id="section_id">
                                                            <option value="">Section</option>
                                                            @foreach ($sections as $section)
                                                                <option value="{{ $section->id }}"
                                                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                                    {{ $section->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}


                                                </div>
                                            </div>
                                        </div>
                                        <!-- Academic Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">PREVIOUS ACADEMIC INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> SCHOOL NAME</label>
                                                        <input type="text" name="previous_school_name"
                                                            id="previous_school_name" class="form-control" value=""
                                                            placeholder="Enter Previous School Name">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> SCHOOL ADDRESS</label>
                                                        <input type="text" name="previous_school_address"
                                                            id="previous_school_address" class="form-control"
                                                            value="" placeholder="Enter Previous School Address">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> CLASS</label>
                                                        <select name="previous_school_class" id="previous_school_class"
                                                            class="form-control">
                                                            <option value="">Select School Class</option>
                                                            @foreach ($classes as $class)
                                                                <option value="{{ $class->id }}"
                                                                    {{ old('previous_school_class') == $class->id ? 'selected' : '' }}>
                                                                    {{ Str::of($class->name)->replaceMatches('/\b\d+\b/', function ($match) {
                                                                        $n = (int) $match[0];
                                                                        $roman = '';
                                                                        $map = [
                                                                            'M' => 1000,
                                                                            'CM' => 900,
                                                                            'D' => 500,
                                                                            'CD' => 400,
                                                                            'C' => 100,
                                                                            'XC' => 90,
                                                                            'L' => 50,
                                                                            'XL' => 40,
                                                                            'X' => 10,
                                                                            'IX' => 9,
                                                                            'V' => 5,
                                                                            'IV' => 4,
                                                                            'I' => 1,
                                                                        ];
                                                                        foreach ($map as $romanChar => $value) {
                                                                            while ($n >= $value) {
                                                                                $roman .= $romanChar;
                                                                                $n -= $value;
                                                                            }
                                                                        }
                                                                        return $roman;
                                                                    }) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> RESULT</label>
                                                        <input type="text" name="previous_school_result"
                                                            id="previous_school_result" class="form-control"
                                                            value="" placeholder="Enter Previous School Result">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">STUDENT INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3 d-none">
                                                        <label class="form-label">EMAIL ADDRESS</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ old('email') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3 d-none">
                                                        <label class="form-label">PHONE NUMBER <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="phone" class="form-control"
                                                            value="{{ old('phone') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">CURRENT ADDRESS </label>
                                                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">PINCODE </label>
                                                        <input type="text" name="pincode" class="form-control"
                                                            value="{{ old('pincode') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DISTRICT </label>
                                                        <select name="district" class="form-control">
                                                            <option value="" disabled selected>Select District
                                                            </option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->value }}"
                                                                    {{ old('district') == $district->value ? 'selected' : '' }}>
                                                                    {{ $district->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PERMANENT ADDRESS</label>
                                                        <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> PINCODE </label>
                                                        <input type="text" name="permanent_pincode"
                                                            class="form-control" value="{{ old('permanent_pincode') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> DISTRICT</label>
                                                        <select name="permanent_district" class="form-control">
                                                            <option value="" disabled selected>Select District
                                                            </option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->value }}"
                                                                    {{ old('permanent_district') == $district->value ? 'selected' : '' }}>
                                                                    {{ $district->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
                                                        <input type="text" name="first_name" class="form-control"
                                                            value="{{ old('first_name') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">LAST NAME</label>
                                                        <input type="text" name="last_name" class="form-control"
                                                            value="{{ old('last_name') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">GENDER</label>
                                                        <select name="gender" class="form-control">
                                                            <option value="" disabled selected>Gender</option>
                                                            <option value="Male"
                                                                {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="Female"
                                                                {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                                            </option>
                                                            <option value="Other"
                                                                {{ old('gender') == 'Other' ? 'selected' : '' }}>Other
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DATE OF BIRTH</label>
                                                        <div class="input-group w-auto input-group-flat">
                                                            <input type="text" name="dob" class="form-control"
                                                                data-provider="flatpickr" data-date-format="d M, Y"
                                                                placeholder="dd/mm/yyyy" value="{{ old('dob') }}">
                                                            <span class="input-group-text"><i
                                                                    class="ti ti-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">RELIGION</label>
                                                        <input type="text" name="religion" class="form-control"
                                                            value="{{ old('religion') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">CASTE</label>
                                                        <select name="caste_tribe" class="form-control">
                                                            <option value="" disabled selected>Category</option>
                                                            <option value="General">General</option>
                                                            <option value="OBC">OBC</option>
                                                            <option value="SC">SC</option>
                                                            <option value="ST">ST</option>
                                                            <option value="OTHERS">OTHERS</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Student Photo</label>
                                                        <div class="upload-area">
                                                            <input type="file" name="photo" id="photo"
                                                                class="d-none" accept="image/*"
                                                                onchange="previewPhoto(this)">
                                                            <div class="upload-content"
                                                                onclick="document.getElementById('photo').click()">
                                                                <div class="upload-icon">
                                                                    <i class="ti ti-camera text-primary"
                                                                        style="font-size: 24px;"></i>
                                                                </div>
                                                                <div class="upload-text">
                                                                    <span class="upload-title">Click to upload photo</span>
                                                                    <small class="upload-subtitle">JPG, PNG (Max
                                                                        5MB)</small>
                                                                </div>
                                                            </div>
                                                            <div class="photo-preview mt-2" id="photoPreviewContainer"
                                                                style="display: none;">
                                                                <img id="photoPreview" src="" alt="Profile Photo"
                                                                    class="rounded"
                                                                    style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                                <div class="photo-actions">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        onclick="document.getElementById('photo').click()">Change</button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="removePhoto()">Remove</button>
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
                                                            <option value="" disabled selected>Blood Group</option>
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
                                                        <label class="form-label">HEIGHT (IN)</label>
                                                        <input type="text" name="height" class="form-control"
                                                            value="{{ old('height') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">WEIGHT (KG)</label>
                                                        <input type="text" name="weight" class="form-control"
                                                            value="{{ old('weight') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">ADMISSION STATUS</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">School <span
                                                                class="text-danger">*</span></label>
                                                        <select name="admission_status" class="form-control">
                                                            <option value="" disabled selected>Select Status</option>
                                                            <option value="admitted"
                                                                {{ old('admission_status') == 'admitted' ? 'selected' : '' }}>
                                                                Admitted
                                                            </option>
                                                            <option value="not admitted"
                                                                {{ old('admission_status') == 'not admitted' ? 'selected' : '' }}>
                                                                Not Admitted
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">HOSTEL <span
                                                                class="text-danger">*</span></label>
                                                        <select name="hostel_status" class="form-control">
                                                            <option value="" disabled selected>Select Status</option>
                                                            <option value="1"
                                                                {{ old('hostel_status') == '1' ? 'selected' : '' }}>
                                                                Yes
                                                            </option>
                                                            <option value="0"
                                                                {{ old('hostel_status') == '0' ? 'selected' : '' }}>
                                                                No
                                                            </option>
                                                        </select>
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
                                    <!-- Left Column - Parents Info -->
                                    <div class="col-md-6">
                                        <!-- Fathers Info -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">PARENT'S INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">FATHER NAME</label>
                                                        <input type="text" name="father_name" class="form-control"
                                                            value="{{ old('father_name') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">MOTHER NAME</label>
                                                        <input type="text" name="mother_name" class="form-control"
                                                            value="{{ old('mother_name') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">OCCUPATION</label>
                                                        <input type="text" name="father_occupation"
                                                            class="form-control" value="{{ old('father_occupation') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">CONTACT NUMBER</label>
                                                        <input type="text" name="father_phone" class="form-control"
                                                            value="{{ old('father_phone') }}">
                                                    </div>
                                                    {{-- <div class="col-md-12 mb-3">
                                                        <label class="form-label">FATHERS PHOTO</label>
                                                        <div class="upload-area-small">
                                                            <input type="file" name="father_photo" id="father_photo"
                                                                class="d-none" accept="image/*">
                                                            <div class="upload-content-small"
                                                                onclick="document.getElementById('father_photo').click()">
                                                                <i class="ti ti-camera text-primary me-2"></i>
                                                                <span>Upload Photo</span>
                                                            </div>
                                                            <div class="file-info mt-1" id="father_photo_info"
                                                                style="display: none;">
                                                                <small class="text-success"
                                                                    id="father_photo_name"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">MOTHERS PHOTO</label>
                                                        <div class="upload-area-small">
                                                            <input type="file" name="mother_photo" id="mother_photo"
                                                                class="d-none" accept="image/*">
                                                            <div class="upload-content-small"
                                                                onclick="document.getElementById('mother_photo').click()">
                                                                <i class="ti ti-camera text-primary me-2"></i>
                                                                <span>Upload Photo</span>
                                                            </div>
                                                            <div class="file-info mt-1" id="mother_photo_info"
                                                                style="display: none;">
                                                                <small class="text-success"
                                                                    id="mother_photo_name"></small>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">PARENT'S DOCUMENTS</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD FRONT</label>
                                                        <input type="file" name="parent_aadhaar_front"
                                                            class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <input type="file" name="parent_aadhaar_back"
                                                            class="form-control" accept="image/*">
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
                                                        <label class="form-label">GUARDIANS NAME</label>
                                                        <input type="text" name="guardian_name" class="form-control"
                                                            value="{{ old('guardian_name') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">RELATION WITH GUARDIAN</label>
                                                        <input type="text" name="guardian_relation_text"
                                                            class="form-control" value="Other">
                                                    </div>
                                                    {{-- </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">GUARDIAN PHOTO</label>
                                                        <div class="upload-area-small">
                                                            <input type="file" name="guardian_photo"
                                                                id="guardian_photo" class="d-none" accept="image/*">
                                                            <div class="upload-content-small"
                                                                onclick="document.getElementById('guardian_photo').click()">
                                                                <i class="ti ti-camera text-primary me-2"></i>
                                                                <span>Upload Photo</span>
                                                            </div>
                                                            <div class="file-info mt-1" id="guardian_photo_info"
                                                                style="display: none;">
                                                                <small class="text-success"
                                                                    id="guardian_photo_name"></small>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">CONTACT NUMBER</label>
                                                        <input type="text" name="guardian_phone" class="form-control"
                                                            value="{{ old('guardian_phone') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">ADDRESS</label>
                                                        <textarea name="guardian_address" class="form-control" rows="3">{{ old('guardian_address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">GUARDIAN'S DOCUMENTS</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD FRONT</label>
                                                        <input type="file" name="guardian_aadhaar_front"
                                                            class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <input type="file" name="guardian_aadhaar_back"
                                                            class="form-control" accept="image/*">
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

                                        <!-- Aadhaar Card Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">STUDENT AADHAAR CARD INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD NUMBER</label>
                                                        <input type="text" name="aadhaar_no" class="form-control"
                                                            value="{{ old('aadhaar_no') }}"
                                                            placeholder="Enter 12-digit Aadhaar number">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">AADHAAR CARD FRONT</label>
                                                        <input type="file" name="aadhaar_front" class="form-control"
                                                            accept="image/*">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <input type="file" name="aadhaar_back" class="form-control"
                                                            accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Right Column - Birth Certificate -->
                                    {{-- <div class="col-md-6">

                                        <!-- Bank Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">BANK INFORMATION</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">BANK NAME</label>
                                                        <input type="text" name="bank_name" class="form-control"
                                                            value="{{ old('bank_name') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">BANK ACCOUNT NUMBER</label>
                                                        <input type="text" name="bank_account_number"
                                                            class="form-control"
                                                            value="{{ old('bank_account_number') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">IFSC CODE</label>
                                                        <input type="text" name="ifsc_code" class="form-control"
                                                            value="{{ old('ifsc_code') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>

                                <!-- Document Attachment -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">OTHER DOCUMENTS</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">DOCUMENT 01 TITLE</label>
                                                <input type="text" name="document_01_title" class="form-control"
                                                    value="{{ old('document_01_title') }}">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_01_file" id="document_01_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="document-upload-btn"
                                                        onclick="document.getElementById('document_01_file').click()">
                                                        <i class="ti ti-file-upload text-primary me-1"></i>
                                                        <span>Upload File</span>
                                                    </div>
                                                    <div class="file-info mt-1" id="document_01_info"
                                                        style="display: none;">
                                                        <small class="text-success" id="document_01_name"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">DOCUMENT 02 TITLE</label>
                                                <input type="text" name="document_02_title" class="form-control"
                                                    value="{{ old('document_02_title') }}">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_02_file" id="document_02_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="document-upload-btn"
                                                        onclick="document.getElementById('document_02_file').click()">
                                                        <i class="ti ti-file-upload text-primary me-1"></i>
                                                        <span>Upload File</span>
                                                    </div>
                                                    <div class="file-info mt-1" id="document_02_info"
                                                        style="display: none;">
                                                        <small class="text-success" id="document_02_name"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">DOCUMENT 03 TITLE</label>
                                                <input type="text" name="document_03_title" class="form-control"
                                                    value="{{ old('document_03_title') }}">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_03_file" id="document_03_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="document-upload-btn"
                                                        onclick="document.getElementById('document_03_file').click()">
                                                        <i class="ti ti-file-upload text-primary me-1"></i>
                                                        <span>Upload File</span>
                                                    </div>
                                                    <div class="file-info mt-1" id="document_03_info"
                                                        style="display: none;">
                                                        <small class="text-success" id="document_03_name"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">DOCUMENT 04 TITLE</label>
                                                <input type="text" name="document_04_title" class="form-control"
                                                    value="{{ old('document_04_title') }}">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_04_file" id="document_04_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="document-upload-btn"
                                                        onclick="document.getElementById('document_04_file').click()">
                                                        <i class="ti ti-file-upload text-primary me-1"></i>
                                                        <span>Upload File</span>
                                                    </div>
                                                    <div class="file-info mt-1" id="document_04_info"
                                                        style="display: none;">
                                                        <small class="text-success" id="document_04_name"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div class="row">
                                    <!-- Left Column - Document Info -->
                                    <div class="col-md-6">
                                        <!-- Payment Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">Admission Fee</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <p>Admission Fee: <span class="text-primary"
                                                            id="admission_fee_amount">₹ </span></p>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">DISCOUNT (if any)</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0"
                                                                name="admission_discount" id="admission_discount"
                                                                class="form-control"
                                                                value="{{ old('admission_discount') }}"
                                                                placeholder="Enter Discount Amount or Percentage">
                                                            <select name="admission_discount_type"
                                                                id="admission_discount_type" class="form-select"
                                                                style="max-width: 100px;">
                                                                <option value="fixed"
                                                                    {{ old('admission_discount_type', 'fixed') == 'fixed' ? 'selected' : '' }}>
                                                                    ₹</option>
                                                                <option value="percent"
                                                                    {{ old('admission_discount_type') == 'percent' ? 'selected' : '' }}>
                                                                    %</option>
                                                            </select>
                                                        </div>
                                                        <small class="form-text text-muted">Choose ₹ for amount or % for
                                                            percentage discount.</small>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT AMOUNT</label>
                                                        <input type="number" name="admission_payment_amount"
                                                            id="admission_payment_amount" class="form-control"
                                                            value="{{ old('admission_payment_amount') }}"
                                                            placeholder="Enter Payment Amount">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="admission_payment_method" class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                {{ old('admission_payment_method') == 'online' ? 'selected' : '' }}>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                {{ old('admission_payment_method') == 'cash' ? 'selected' : '' }}>
                                                                Cash</option>
                                                        </select>
                                                    </div>
                                                    <p>Due: <span class="text-danger" id="admission_due_amount">₹
                                                            0</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">Tuition Fee</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <p>Monthly Tuition Fee: <span class="text-primary"
                                                            id="tuition_fee_per_month">₹ </span></p>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">SELECT MONTHS</label>
                                                        <div class="row g-2" id="tuition_months_container">
                                                            @php
                                                                $months = [
                                                                    'January',
                                                                    'February',
                                                                    'March',
                                                                    'April',
                                                                    'May',
                                                                    'June',
                                                                    'July',
                                                                    'August',
                                                                    'September',
                                                                    'October',
                                                                    'November',
                                                                    'December',
                                                                ];
                                                            @endphp
                                                            @php
                                                                $oldMonths = old('tuition_months', []);
                                                            @endphp
                                                            @foreach ($months as $index => $month)
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check">
                                                                        <input
                                                                            class="form-check-input tuition-month-checkbox"
                                                                            type="checkbox" name="tuition_months[]"
                                                                            value="{{ $index + 1 }}"
                                                                            id="month_{{ $index + 1 }}"
                                                                            {{ in_array($index + 1, $oldMonths) ? 'checked' : '' }}
                                                                            onchange="calculateTuitionFee()">
                                                                        <label class="form-check-label"
                                                                            for="month_{{ $index + 1 }}">
                                                                            {{ $month }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <input type="hidden" name="tuition_selected_months"
                                                            id="tuition_selected_months"
                                                            value="{{ old('tuition_selected_months', '') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <p class="mb-2">Total Tuition Fee: <span
                                                                class="text-primary fw-bold" id="tuition_fee_total">₹
                                                                0</span></p>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT AMOUNT</label>
                                                        <input type="number" name="tuition_payment_amount"
                                                            id="tuition_payment_amount" class="form-control"
                                                            value="{{ old('tuition_payment_amount') }}"
                                                            placeholder="Enter Payment Amount"
                                                            onchange="calculateTuitionDue()"
                                                            oninput="calculateTuitionDue()">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="tuition_payment_method" class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                {{ old('tuition_payment_method') == 'online' ? 'selected' : '' }}>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                {{ old('tuition_payment_method') == 'cash' ? 'selected' : '' }}>
                                                                Cash</option>
                                                        </select>
                                                    </div>
                                                    <p>Due: <span class="text-danger fw-bold" id="tuition_due_amount">₹
                                                            0</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="hostel" role="tabpanel" aria-labelledby="hostel-tab">
                                <div class="row">
                                    <!-- Left Column - Document Info -->
                                    <div class="col-md-6">
                                        <!-- Payment Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">Admission Fee</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <p>Admission Fee: <span class="text-primary"
                                                            id="hostel_admission_fee_amount">₹ </span></p>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT AMOUNT</label>
                                                        <input type="number" name="hostel_admission_payment_amount"
                                                            id="hostel_admission_payment_amount" class="form-control"
                                                            value="{{ old('hostel_admission_payment_amount') }}"
                                                            placeholder="Enter Payment Amount">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="hostel_admission_payment_method"
                                                            class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                {{ old('hostel_admission_payment_method') == 'online' ? 'selected' : '' }}>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                {{ old('hostel_admission_payment_method') == 'cash' ? 'selected' : '' }}>
                                                                Cash</option>
                                                        </select>
                                                    </div>
                                                    <p>Due: <span class="text-danger fw-bold"
                                                            id="hostel_admission_due_amount">₹
                                                            0</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">Tuition Fee</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <p>Monthly Tuition Fee: <span class="text-primary"
                                                            id="hostel_fee_per_month">₹ </span></p>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">SELECT MONTHS</label>
                                                        <div class="row g-2" id="hostel_tuition_months_container">
                                                            @php
                                                                $months = [
                                                                    'January',
                                                                    'February',
                                                                    'March',
                                                                    'April',
                                                                    'May',
                                                                    'June',
                                                                    'July',
                                                                    'August',
                                                                    'September',
                                                                    'October',
                                                                    'November',
                                                                    'December',
                                                                ];
                                                            @endphp
                                                            @php
                                                                $oldMonths = old('hostel_tuition_months', []);
                                                            @endphp
                                                            @foreach ($months as $index => $month)
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check">
                                                                        <input
                                                                            class="form-check-input hostel-tuition-month-checkbox"
                                                                            type="checkbox" name="hostel_tuition_months[]"
                                                                            value="{{ $index + 1 }}"
                                                                            id="hostel_month_{{ $index + 1 }}"
                                                                            {{ in_array($index + 1, $oldMonths) ? 'checked' : '' }}
                                                                            onchange="calculateHostelTuitionFee()">
                                                                        <label class="form-check-label"
                                                                            for="hostel_month_{{ $index + 1 }}">
                                                                            {{ $month }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <input type="hidden" name="hostel_tuition_selected_months"
                                                            id="hostel_tuition_selected_months"
                                                            value="{{ old('hostel_tuition_selected_months', '') }}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <p class="mb-2">Total Tuition Fee: <span
                                                                class="text-primary fw-bold"
                                                                id="hostel_tuition_fee_total">₹
                                                                0</span></p>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT AMOUNT</label>
                                                        <input type="number" name="hostel_tuition_payment_amount"
                                                            id="hostel_tuition_payment_amount" class="form-control"
                                                            value="{{ old('hostel_tuition_payment_amount') }}"
                                                            placeholder="Enter Payment Amount"
                                                            onchange="calculateHostelTuitionDue()"
                                                            oninput="calculateHostelTuitionDue()">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="hostel_tuition_payment_method" class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                {{ old('hostel_tuition_payment_method') == 'online' ? 'selected' : '' }}>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                {{ old('hostel_tuition_payment_method') == 'cash' ? 'selected' : '' }}>
                                                                Cash</option>
                                                        </select>
                                                    </div>
                                                    <p>Due: <span class="text-danger fw-bold"
                                                            id="hostel_tuition_due_amount">₹
                                                            0</span></p>
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

        /* Tuition Fee Month Checkboxes */
        .tuition-month-checkbox {
            cursor: pointer;
        }

        .tuition-month-checkbox:checked+.form-check-label {
            color: #0d6efd;
            font-weight: 600;
        }

        #tuition_months_container {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            background-color: #f8f9fa;
            max-height: 300px;
            overflow-y: auto;
        }

        #tuition_months_container .form-check {
            margin-bottom: 8px;
        }

        #tuition_months_container .form-check-label {
            cursor: pointer;
            font-size: 14px;
            user-select: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('custom/js/institution/students.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });
        });

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

        // Fee Structure data from PHP
        window.feeStructures = @json($feeStructure);

        // Function to update admission fee based on selected class
        function updateAdmissionFee() {
            const classId = document.querySelector('select[name="class_id"]').value;
            const admissionFeeElement = document.getElementById('admission_fee_amount');
            const dueAmountElement = document.getElementById('admission_due_amount');
            const paymentAmountInput = document.getElementById('admission_payment_amount');
            const hostelAdmissionFeeElement = document.getElementById('hostel_admission_fee_amount');
            const hostelAdmissionDueAmountElement = document.getElementById('hostel_admission_due_amount');
            const hostelAdmissionPaymentAmountInput = document.getElementById('hostel_admission_payment_amount');
            if (!classId) {
                admissionFeeElement.textContent = '₹ 0';
                dueAmountElement.textContent = '₹ 0';
                hostelAdmissionFeeElement.textContent = '₹ 0';
                hostelAdmissionDueAmountElement.textContent = '₹ 0';
                return;
            }

            // Find admission fee for the selected class
            // First, try to find a fee with "admission" in the name
            // If not found, look for one-time fees for the class
            const classFees = window.feeStructures.filter(fee => {
                const matchesClass = fee.class_id == classId || fee.class_id === parseInt(classId);
                return matchesClass;
            });

            let admissionFee = classFees.find(fee => {
                return fee.name && fee.name.toLowerCase().includes('admission');
            });

            // If no fee with "admission" in name, look for one-time fees
            if (!admissionFee) {
                admissionFee = classFees.find(fee => fee.fee_type === 'onetime');
            }

            // Find hostel admission fee for the selected class
            let hostelAdmissionFee = classFees.find(fee => {
                return fee.name && fee.name.toLowerCase().includes('hostel admission');
            });

            if (admissionFee) {
                const feeAmount = parseFloat(admissionFee.amount) || 0;
                admissionFeeElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');

                // Recalculate due amount if payment amount is already entered
                // calculateDueAmount(feeAmount);
            } else {
                admissionFeeElement.textContent = '₹ 0';
                dueAmountElement.textContent = '₹ 0';

            }

            if (hostelAdmissionFee) {
                const feeAmount = parseFloat(hostelAdmissionFee.amount) || 0;
                hostelAdmissionFeeElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');
                hostelAdmissionDueAmountElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');
                hostelAdmissionPaymentAmountInput.value = feeAmount;
                hostelAdmissionDueAmountElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');
            } else {
                hostelAdmissionFeeElement.textContent = '₹ 0';
                hostelAdmissionDueAmountElement.textContent = '₹ 0';
                hostelAdmissionPaymentAmountInput.value = '0';
                hostelAdmissionDueAmountElement.textContent = '₹ 0';
            }
        }

        // Function to calculate due amount
        $("#admission_payment_amount").on("blur", function() {
            calculateDueAmount();
        });

        // Similar calculation for hostel admission payment (hostel section)
        $("#hostel_admission_payment_amount").on("blur", function() {
            calculateHostelAdmissionDueAmount();
        });

        function calculateHostelAdmissionDueAmount(hostelAdmissionFeeAmount = null) {
            const paymentAmountInput = document.getElementById('hostel_admission_payment_amount');
            const dueAmountElement = document.getElementById('hostel_admission_due_amount');

            // Get hostel admission fee amount if not provided
            if (hostelAdmissionFeeAmount === null) {
                const hostelAdmissionFeeText = document.getElementById('hostel_admission_fee_amount').textContent;
                hostelAdmissionFeeAmount = parseFloat(hostelAdmissionFeeText.replace(/[₹,\s]/g, '')) || 0;
            }

            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            const dueAmount = Math.max(0, hostelAdmissionFeeAmount - paymentAmount);
            dueAmountElement.textContent = '₹ ' + dueAmount.toLocaleString('en-IN');
        }

        function calculateDueAmount(admissionFeeAmount = null) {
            const paymentAmountInput = document.getElementById('admission_payment_amount');
            const dueAmountElement = document.getElementById('admission_due_amount');

            // Get admission fee amount if not provided
            if (admissionFeeAmount === null) {
                const admissionFeeText = document.getElementById('admission_fee_amount').textContent;
                admissionFeeAmount = parseFloat(admissionFeeText.replace(/[₹,\s]/g, '')) || 0;
            }

            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            const dueAmount = Math.max(0, admissionFeeAmount - paymentAmount);
            console.log(dueAmount);
            dueAmountElement.textContent = '₹ ' + dueAmount.toLocaleString('en-IN');
        }

        // Function to update tuition fee based on selected class
        function updateTuitionFee() {
            const classId = document.querySelector('select[name="class_id"]').value;
            const tuitionFeePerMonthElement = document.getElementById('tuition_fee_per_month');
            const HostelFeePerMonthElement = document.getElementById('hostel_fee_per_month');

            if (!classId) {
                tuitionFeePerMonthElement.textContent = '₹ 0';
                document.getElementById('tuition_fee_total').textContent = '₹ 0';
                return;
            }

            // Find tuition fee for the selected class (monthly fee type)
            const classFees = window.feeStructures.filter(fee => {
                const matchesClass = fee.class_id == classId || fee.class_id === parseInt(classId);
                return matchesClass;
            });
            // Logic for hostel monthly fee (hostel tuition fee per month)
            // Find hostel monthly fee for the selected class
            let hostelMonthlyFee = classFees.find(fee => {
                return fee.fee_type === 'monthly' &&
                    (fee.name && (
                        fee.name.toLowerCase().includes('hostel') ||
                        fee.name.toLowerCase().includes('hostel tuition')
                    ));
            });

            // If not found, fallback to any monthly hostel-related fee
            if (!hostelMonthlyFee) {
                hostelMonthlyFee = classFees.find(fee =>
                    fee.fee_type === 'monthly' &&
                    (fee.name && fee.name.toLowerCase().includes('hostel'))
                );
            }


            // If found, you can set it somewhere for later use, e.g.:
            if (hostelMonthlyFee) {
                // For example, store amount in a data attribute for access elsewhere if needed
                HostelFeePerMonthElement.setAttribute('data-monthly-fee', hostelMonthlyFee.amount);
                HostelFeePerMonthElement.textContent = '₹ ' + hostelMonthlyFee.amount.toLocaleString('en-IN');
            } else {
                HostelFeePerMonthElement.textContent = '₹ 0';
                HostelFeePerMonthElement.setAttribute('data-monthly-fee', '0');
            }

            // Look for tuition fee (monthly fee type)
            let tuitionFee = classFees.find(fee => {
                return fee.fee_type === 'monthly' &&
                    (fee.name && fee.name.toLowerCase().includes('tuition'));
            });

            // If no fee with "tuition" in name, look for any monthly fee
            if (!tuitionFee) {
                tuitionFee = classFees.find(fee => fee.fee_type === 'monthly');
            }

            if (tuitionFee) {
                const feeAmount = parseFloat(tuitionFee.amount) || 0;
                tuitionFeePerMonthElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');
                // Store the monthly fee amount in a data attribute for later use
                tuitionFeePerMonthElement.setAttribute('data-monthly-fee', feeAmount);
                // Recalculate total if months are already selected
                calculateTuitionFee();
            } else {
                tuitionFeePerMonthElement.textContent = '₹ 0';
                tuitionFeePerMonthElement.setAttribute('data-monthly-fee', '0');
                document.getElementById('tuition_fee_total').textContent = '₹ 0';
            }
        }
        // Function to update hostel tuition fee based on selected class
        function updateHostelTuitionFee() {
            const classId = document.querySelector('select[name="class_id"]').value;
            const hostelTuitionFeePerMonthElement = document.getElementById('hostel_fee_per_month');
            if (!classId) {
                hostelTuitionFeePerMonthElement.textContent = '₹ 0';
                document.getElementById('hostel_tuition_fee_total').textContent = '₹ 0';
                return;
            }

            // Find hostel tuition fee for the selected class
            const classFees = window.feeStructures.filter(fee => {
                const matchesClass = fee.class_id == classId || fee.class_id === parseInt(classId);
                return matchesClass;
            });

            let hostelTuitionFee = classFees.find(fee => {
                return fee.fee_type === 'monthly' &&
                    (fee.name && fee.name.toLowerCase().includes('hostel tuition'));
            });

            if (!hostelTuitionFee) {
                hostelTuitionFee = classFees.find(fee => fee.fee_type === 'monthly');
            }

            if (hostelTuitionFee) {
                const feeAmount = parseFloat(hostelTuitionFee.amount) || 0;
                hostelTuitionFeePerMonthElement.textContent = '₹ ' + feeAmount.toLocaleString('en-IN');
                // Store the monthly fee amount in a data attribute for later use
                hostelTuitionFeePerMonthElement.setAttribute('data-monthly-fee', feeAmount);
                // Recalculate total if months are already selected
                calculateHostelTuitionFee();
            } else {
                hostelTuitionFeePerMonthElement.textContent = '₹ 0';
                hostelTuitionFeePerMonthElement.setAttribute('data-monthly-fee', '0');
                document.getElementById('hostel_tuition_fee_total').textContent = '₹ 0';
            }
        }

        // Function to calculate total hostel tuition fee based on selected months
        function calculateHostelTuitionFee() {
            console.log('calculateHostelTuitionFee');
            const monthlyFeeElement = document.getElementById('hostel_fee_per_month');
            const monthlyFee = parseFloat(monthlyFeeElement.getAttribute('data-monthly-fee')) || 0;

            // Get all checked month checkboxes
            const checkedMonths = document.querySelectorAll('.hostel-tuition-month-checkbox:checked');
            const selectedMonths = Array.from(checkedMonths).map(cb => cb.value);

            // Update hidden input with selected months
            document.getElementById('hostel_tuition_selected_months').value = selectedMonths.join(',');

            // Calculate total fee
            const totalFee = monthlyFee * selectedMonths.length;
            document.getElementById('hostel_tuition_fee_total').textContent = '₹ ' + totalFee.toLocaleString('en-IN');

            // Recalculate due amount
            calculateHostelTuitionDue();
        }
        // Function to calculate total tuition fee based on selected months
        function calculateTuitionFee() {
            const monthlyFeeElement = document.getElementById('tuition_fee_per_month');
            const monthlyFee = parseFloat(monthlyFeeElement.getAttribute('data-monthly-fee')) || 0;

            // Get all checked month checkboxes
            const checkedMonths = document.querySelectorAll('.tuition-month-checkbox:checked');
            const selectedMonths = Array.from(checkedMonths).map(cb => cb.value);

            // Update hidden input with selected months
            document.getElementById('tuition_selected_months').value = selectedMonths.join(',');

            // Calculate total fee
            const totalFee = monthlyFee * selectedMonths.length;
            document.getElementById('tuition_fee_total').textContent = '₹ ' + totalFee.toLocaleString('en-IN');

            // Recalculate due amount
            calculateTuitionDue();
        }

        // Function to calculate tuition due amount
        function calculateTuitionDue() {
            const totalFeeText = document.getElementById('tuition_fee_total').textContent;
            const totalFee = parseFloat(totalFeeText.replace(/[₹,\s]/g, '')) || 0;
            const paymentAmountInput = document.getElementById('tuition_payment_amount');
            const dueAmountElement = document.getElementById('tuition_due_amount');

            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            const dueAmount = Math.max(0, totalFee - paymentAmount);

            console.log(dueAmount);
            dueAmountElement.textContent = '₹ ' + dueAmount.toLocaleString('en-IN');
        }
        // Function to calculate hostel tuition due amount
        function calculateHostelTuitionDue() {
            const totalFeeText = document.getElementById('hostel_tuition_fee_total').textContent;
            const totalFee = parseFloat(totalFeeText.replace(/[₹,\s]/g, '')) || 0;
            const paymentAmountInput = document.getElementById('hostel_tuition_payment_amount');
            const dueAmountElement = document.getElementById('hostel_tuition_due_amount');
            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            const dueAmount = Math.max(0, totalFee - paymentAmount);
            console.log(dueAmount);
            dueAmountElement.textContent = '₹ ' + dueAmount.toLocaleString('en-IN');
        }

        // Auto-generate admission number and roll number
        function generateAdmissionAndRollNumbers() {
            const institutionId = window.institutionId;
            const classId = document.querySelector('select[name="class_id"]').value;
            // const sectionId = document.querySelector('select[name="section_id"]').value;

            if (institutionId && classId) {
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
                            institution_id: institutionId,
                            class_id: classId
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
            const paymentAmountInput = document.getElementById('admission_payment_amount');

            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    generateAdmissionAndRollNumbers();
                    updateAdmissionFee();
                    updateTuitionFee();
                });
            }

            // Listen to payment amount changes
            if (paymentAmountInput) {
                paymentAmountInput.addEventListener('input', calculateDueAmount);
                paymentAmountInput.addEventListener('change', calculateDueAmount);
            }

            // Update admission fee on page load if class is already selected
            if (classSelect && classSelect.value) {
                updateAdmissionFee();
                updateTuitionFee();
                // If payment amount already has a value, calculate due amount
                if (paymentAmountInput && paymentAmountInput.value) {
                    // calculateDueAmount();
                }
            } else {
                // Initialize tuition fee per month with default value
                const tuitionFeePerMonthElement = document.getElementById('tuition_fee_per_month');
                if (tuitionFeePerMonthElement) {
                    tuitionFeePerMonthElement.setAttribute('data-monthly-fee', '0');
                }
            }

            // Recalculate tuition fee if months are already selected (e.g., from old input)
            const checkedMonths = document.querySelectorAll('.tuition-month-checkbox:checked');
            if (checkedMonths.length > 0) {
                calculateTuitionFee();
            }
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
