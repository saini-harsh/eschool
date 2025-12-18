<?php $__env->startSection('title', 'Institution | Add Student'); ?>
<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-alert-circle me-2"></i>
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="content">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div>
                    <h6 class="mb-3 fs-14">
                        <a href="<?php echo e(route('institution.students.index')); ?>">
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


                    <form id="studentForm" action="<?php echo e(route('institution.admission.store')); ?>" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

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
                                <button class="nav-link" id="sibling-tab" data-bs-toggle="tab" data-bs-target="#sibling"
                                    type="button" role="tab" aria-controls="sibling" aria-selected="false">
                                    SIBLING INFO
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
                                    BOARDING PAYMENT
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
                                                                value="<?php echo e(old('admission_date', date('d M, Y'))); ?>">
                                                            <span class="input-group-text"><i
                                                                    class="ti ti-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ADMISSION NUMBER</label>
                                                        <input type="text" name="admission_number"
                                                            id="admission_number" class="form-control"
                                                            value="<?php echo e(old('admission_number')); ?>" readonly
                                                            placeholder="Auto-generated">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ROLL NUMBER</label>
                                                        <input type="text" name="roll_number" id="roll_number"
                                                            class="form-control" value="<?php echo e(old('roll_number')); ?>" readonly
                                                            placeholder="Auto-generated">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">CLASS</label>
                                                        <select name="class_id" class="form-control" id="class_id"
                                                            onchange="loadSections(this.value)">
                                                            <option value="" disabled selected>Class</option>
                                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($class->id); ?>"
                                                                    <?php echo e(old('previous_school_class') == $class->id ? 'selected' : ''); ?>>
                                                                    <?php echo e(Str::of($class->name)->replaceMatches('/\b\d+\b/', function ($match) {
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
                                                                    })); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">PEN NUMBER</label>
                                                        <input type="text" name="pen_no" class="form-control"
                                                            value="<?php echo e(old('pen_no')); ?>" placeholder="Enter PEN number">
                                                    </div>
                                                    


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
                                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($class->id); ?>"
                                                                    <?php echo e(old('previous_school_class') == $class->id ? 'selected' : ''); ?>>
                                                                    <?php echo e(Str::of($class->name)->replaceMatches('/\b\d+\b/', function ($match) {
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
                                                                    })); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                            value="<?php echo e(old('email')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3 d-none">
                                                        <label class="form-label">PHONE NUMBER <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="phone" class="form-control"
                                                            value="<?php echo e(old('phone')); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">CURRENT ADDRESS </label>
                                                        <textarea name="address" class="form-control" rows="3"><?php echo e(old('address')); ?></textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">PINCODE </label>
                                                        <input type="text" name="pincode" class="form-control"
                                                            value="<?php echo e(old('pincode')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DISTRICT </label>
                                                        <select name="district" class="form-control">
                                                            <option value="" disabled selected>Select District
                                                            </option>
                                                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($district->value); ?>"
                                                                    <?php echo e(old('district') == $district->value ? 'selected' : ''); ?>>
                                                                    <?php echo e($district->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PERMANENT ADDRESS</label>
                                                        <textarea name="permanent_address" class="form-control" rows="3"><?php echo e(old('permanent_address')); ?></textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> PINCODE </label>
                                                        <input type="text" name="permanent_pincode"
                                                            class="form-control" value="<?php echo e(old('permanent_pincode')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"> DISTRICT</label>
                                                        <select name="permanent_district" class="form-control">
                                                            <option value="" disabled selected>Select District
                                                            </option>
                                                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($district->value); ?>"
                                                                    <?php echo e(old('permanent_district') == $district->value ? 'selected' : ''); ?>>
                                                                    <?php echo e($district->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                            value="<?php echo e(old('first_name')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">LAST NAME</label>
                                                        <input type="text" name="last_name" class="form-control"
                                                            value="<?php echo e(old('last_name')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">GENDER</label>
                                                        <select name="gender" class="form-control">
                                                            <option value="" disabled selected>Gender</option>
                                                            <option value="Male"
                                                                <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male
                                                            </option>
                                                            <option value="Female"
                                                                <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female
                                                            </option>
                                                            <option value="Other"
                                                                <?php echo e(old('gender') == 'Other' ? 'selected' : ''); ?>>Other
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DATE OF BIRTH</label>
                                                        <div class="input-group w-auto input-group-flat">
                                                            <input type="text" name="dob" id="dob_input"
                                                                class="form-control" data-provider="flatpickr"
                                                                data-date-format="d M, Y" placeholder="dd/mm/yyyy"
                                                                value="<?php echo e(old('dob')); ?>">
                                                            <span class="input-group-text"><i
                                                                    class="ti ti-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">AGE (YEARS)</label>
                                                        <input type="text" name="age_years" id="age_years"
                                                            class="form-control bg-light" readonly placeholder="Years"
                                                            value="<?php echo e(old('age_years')); ?>">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">AGE (MONTHS)</label>
                                                        <input type="text" name="age_months" id="age_months"
                                                            class="form-control bg-light" readonly placeholder="Months"
                                                            value="<?php echo e(old('age_months')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DOB STATUS</label>
                                                        <select name="dob_status" class="form-control">
                                                            <option value="Not Verified"
                                                                <?php echo e(old('dob_status') == 'Not Verified' ? 'selected' : ''); ?>>
                                                                Not
                                                                Verified</option>
                                                            <option value="Verified"
                                                                <?php echo e(old('dob_status') == 'Verified' ? 'selected' : ''); ?>>
                                                                Verified
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">RELIGION</label>
                                                        <input type="text" name="religion" class="form-control"
                                                            value="<?php echo e(old('religion')); ?>">
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
                                                            <div class="camera-capture-btn mt-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary w-100"
                                                                    onclick="openCamera('photo')">
                                                                    <i class="ti ti-camera me-1"></i> Take Photo
                                                                </button>
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
                                                            value="<?php echo e(old('height')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">WEIGHT (KG)</label>
                                                        <input type="text" name="weight" class="form-control"
                                                            value="<?php echo e(old('weight')); ?>">
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
                                                                <?php echo e(old('admission_status') == 'admitted' ? 'selected' : ''); ?>>
                                                                Admitted
                                                            </option>
                                                            <option value="not admitted"
                                                                <?php echo e(old('admission_status') == 'not admitted' ? 'selected' : ''); ?>>
                                                                Not Admitted
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">BOARDING <span
                                                                class="text-danger">*</span></label>
                                                        <select name="hostel_status" class="form-control">
                                                            <option value="" disabled selected>Select Status</option>
                                                            <option value="1"
                                                                <?php echo e(old('hostel_status') == '1' ? 'selected' : ''); ?>>
                                                                Yes
                                                            </option>
                                                            <option value="0"
                                                                <?php echo e(old('hostel_status') == '0' ? 'selected' : ''); ?>>
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

                            <!-- Sibling Info Tab -->
                            <div class="tab-pane fade" id="sibling" role="tabpanel" aria-labelledby="sibling-tab">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 text-primary">SIBLING INFORMATION</h6>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="has_sibling"
                                                id="has_sibling" value="1"
                                                <?php echo e(old('has_sibling') ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="has_sibling">Does student have a sibling
                                                in this school?</label>
                                        </div>
                                    </div>
                                    <div class="card-body" id="sibling_section"
                                        style="<?php echo e(old('has_sibling') ? '' : 'display: none;'); ?>">
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <label class="form-label">SEARCH SIBLING (Name or Admission Number)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                                    <input type="text" id="sibling_search" class="form-control"
                                                        placeholder="Type to search for a sibling...">
                                                </div>
                                                <div id="sibling_results" class="list-group mt-2"
                                                    style="position: absolute; z-index: 1000; width: 95%; max-height: 200px; overflow-y: auto; display: none;">
                                                    <!-- Search results will appear here -->
                                                </div>
                                            </div>

                                            <div id="selected_siblings_container" class="col-md-12">
                                                <!-- Multiple siblings will be listed here -->
                                            </div>

                                            <div id="sibling_actions" class="col-md-12" style="display: none;">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="copy_parent_info">
                                                    <label class="form-check-label fw-bold" for="copy_parent_info">
                                                        Copy Parent and Address information from first Sibling?
                                                    </label>
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
                                                            value="<?php echo e(old('father_name')); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">MOTHER NAME</label>
                                                        <input type="text" name="mother_name" class="form-control"
                                                            value="<?php echo e(old('mother_name')); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">OCCUPATION</label>
                                                        <input type="text" name="father_occupation"
                                                            class="form-control" value="<?php echo e(old('father_occupation')); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">CONTACT NUMBER</label>
                                                        <input type="text" name="father_phone" class="form-control"
                                                            value="<?php echo e(old('father_phone')); ?>">
                                                    </div>
                                                    
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
                                                        <div class="input-group">
                                                            <input type="file" name="parent_aadhaar_front"
                                                                id="parent_aadhaar_front" class="form-control"
                                                                accept="image/*"
                                                                onchange="previewAadhaar(this, 'parent_aadhaar_front')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('parent_aadhaar_front')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="parent_aadhaar_front_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="parent_aadhaar_front_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('parent_aadhaar_front', 'parent_aadhaar_front')">Remove</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <div class="input-group">
                                                            <input type="file" name="parent_aadhaar_back"
                                                                id="parent_aadhaar_back" class="form-control"
                                                                accept="image/*"
                                                                onchange="previewAadhaar(this, 'parent_aadhaar_back')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('parent_aadhaar_back')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="parent_aadhaar_back_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="parent_aadhaar_back_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('parent_aadhaar_back', 'parent_aadhaar_back')">Remove</button>
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
                                                        <label class="form-label">GUARDIANS NAME</label>
                                                        <input type="text" name="guardian_name" class="form-control"
                                                            value="<?php echo e(old('guardian_name')); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">RELATION WITH GUARDIAN</label>
                                                        <input type="text" name="guardian_relation_text"
                                                            class="form-control" value="Other">
                                                    </div>
                                                    
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">CONTACT NUMBER</label>
                                                        <input type="text" name="guardian_phone" class="form-control"
                                                            value="<?php echo e(old('guardian_phone')); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">ADDRESS</label>
                                                        <textarea name="guardian_address" class="form-control" rows="3"><?php echo e(old('guardian_address')); ?></textarea>
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
                                                        <div class="input-group">
                                                            <input type="file" name="guardian_aadhaar_front"
                                                                id="guardian_aadhaar_front" class="form-control"
                                                                accept="image/*"
                                                                onchange="previewAadhaar(this, 'guardian_aadhaar_front')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('guardian_aadhaar_front')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="guardian_aadhaar_front_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="guardian_aadhaar_front_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('guardian_aadhaar_front', 'guardian_aadhaar_front')">Remove</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <div class="input-group">
                                                            <input type="file" name="guardian_aadhaar_back"
                                                                id="guardian_aadhaar_back" class="form-control"
                                                                accept="image/*"
                                                                onchange="previewAadhaar(this, 'guardian_aadhaar_back')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('guardian_aadhaar_back')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="guardian_aadhaar_back_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="guardian_aadhaar_back_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('guardian_aadhaar_back', 'guardian_aadhaar_back')">Remove</button>
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
                                                            value="<?php echo e(old('aadhaar_no')); ?>"
                                                            placeholder="Enter 12-digit Aadhaar number">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">AADHAAR CARD FRONT</label>
                                                        <div class="input-group">
                                                            <input type="file" name="aadhaar_front" id="aadhaar_front"
                                                                class="form-control" accept="image/*"
                                                                onchange="previewAadhaar(this, 'aadhaar_front')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('aadhaar_front')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="aadhaar_front_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="aadhaar_front_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('aadhaar_front', 'aadhaar_front')">Remove</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">AADHAAR CARD BACK</label>
                                                        <div class="input-group">
                                                            <input type="file" name="aadhaar_back" id="aadhaar_back"
                                                                class="form-control" accept="image/*"
                                                                onchange="previewAadhaar(this, 'aadhaar_back')">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="openCamera('aadhaar_back')">
                                                                <i class="ti ti-camera"></i>
                                                            </button>
                                                        </div>
                                                        <div id="aadhaar_back_container" class="mt-2"
                                                            style="display: none;">
                                                            <img id="aadhaar_back_img" src=""
                                                                class="img-thumbnail"
                                                                style="max-height: 150px; cursor: pointer;"
                                                                onclick="window.open(this.src)">
                                                            <button type="button" class="btn btn-sm btn-danger mt-1"
                                                                onclick="removeAadhaarPreview('aadhaar_back', 'aadhaar_back')">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Right Column - Birth Certificate -->
                                    
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
                                                    value="<?php echo e(old('document_01_title')); ?>">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_01_file" id="document_01_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="d-flex gap-2">
                                                        <div class="document-upload-btn flex-grow-1"
                                                            onclick="document.getElementById('document_01_file').click()">
                                                            <i class="ti ti-file-upload text-primary me-1"></i>
                                                            <span>Upload File</span>
                                                        </div>
                                                        <div class="document-upload-btn px-3"
                                                            onclick="openCamera('document_01_file')">
                                                            <i class="ti ti-camera text-primary"></i>
                                                        </div>
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
                                                    value="<?php echo e(old('document_02_title')); ?>">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_02_file" id="document_02_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="d-flex gap-2">
                                                        <div class="document-upload-btn flex-grow-1"
                                                            onclick="document.getElementById('document_02_file').click()">
                                                            <i class="ti ti-file-upload text-primary me-1"></i>
                                                            <span>Upload File</span>
                                                        </div>
                                                        <div class="document-upload-btn px-3"
                                                            onclick="openCamera('document_02_file')">
                                                            <i class="ti ti-camera text-primary"></i>
                                                        </div>
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
                                                    value="<?php echo e(old('document_03_title')); ?>">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_03_file" id="document_03_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="d-flex gap-2">
                                                        <div class="document-upload-btn flex-grow-1"
                                                            onclick="document.getElementById('document_03_file').click()">
                                                            <i class="ti ti-file-upload text-primary me-1"></i>
                                                            <span>Upload File</span>
                                                        </div>
                                                        <div class="document-upload-btn px-3"
                                                            onclick="openCamera('document_03_file')">
                                                            <i class="ti ti-camera text-primary"></i>
                                                        </div>
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
                                                    value="<?php echo e(old('document_04_title')); ?>">
                                                <div class="document-upload mt-2">
                                                    <input type="file" name="document_04_file" id="document_04_file"
                                                        class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                    <div class="d-flex gap-2">
                                                        <div class="document-upload-btn flex-grow-1"
                                                            onclick="document.getElementById('document_04_file').click()">
                                                            <i class="ti ti-file-upload text-primary me-1"></i>
                                                            <span>Upload File</span>
                                                        </div>
                                                        <div class="document-upload-btn px-3"
                                                            onclick="openCamera('document_04_file')">
                                                            <i class="ti ti-camera text-primary"></i>
                                                        </div>
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
                                        <!-- Discount & Fee Information -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="fw-bold mb-0 text-primary">Discount Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">DISCOUNT CATEGORY</label>
                                                        <select name="discount_category" id="discount_category"
                                                            class="form-control" onchange="applyDiscount()">
                                                            <option value="">No Discount</option>
                                                            <option value="orphanage"
                                                                <?php echo e(old('discount_category') == 'orphanage' ? 'selected' : ''); ?>>
                                                                Orphanage</option>
                                                            <option value="teacher_child"
                                                                <?php echo e(old('discount_category') == 'teacher_child' ? 'selected' : ''); ?>>
                                                                Teacher's Child</option>
                                                            <option value="personal_selection"
                                                                <?php echo e(old('discount_category') == 'personal_selection' ? 'selected' : ''); ?>>
                                                                Personal Selection</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DISCOUNT AMOUNT (₹)</label>
                                                        <input type="number" name="discount_amount" id="discount_amount"
                                                            class="form-control" value="<?php echo e(old('discount_amount', 0)); ?>"
                                                            placeholder="Enter Discount Amount"
                                                            oninput="applyDiscount('amount')">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DISCOUNT PERCENT (%)</label>
                                                        <input type="number" name="discount_percentage"
                                                            id="discount_percentage" class="form-control"
                                                            value="<?php echo e(old('discount_percentage', 0)); ?>"
                                                            placeholder="Enter Discount Percent"
                                                            oninput="applyDiscount('percent')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                                                        <label class="form-label">PAYMENT AMOUNT</label>
                                                        <input type="number" name="admission_payment_amount"
                                                            id="admission_payment_amount" class="form-control"
                                                            value="<?php echo e(old('admission_payment_amount')); ?>"
                                                            placeholder="Enter Payment Amount">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="admission_payment_method" class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                <?php echo e(old('admission_payment_method') == 'online' ? 'selected' : ''); ?>>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                <?php echo e(old('admission_payment_method') == 'cash' ? 'selected' : ''); ?>>
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
                                                            <?php
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
                                                            ?>
                                                            <?php
                                                                $oldMonths = old('tuition_months', []);
                                                            ?>
                                                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check">
                                                                        <input
                                                                            class="form-check-input tuition-month-checkbox"
                                                                            type="checkbox" name="tuition_months[]"
                                                                            value="<?php echo e($index + 1); ?>"
                                                                            id="month_<?php echo e($index + 1); ?>"
                                                                            <?php echo e(in_array($index + 1, $oldMonths) ? 'checked' : ''); ?>

                                                                            onchange="calculateTuitionFee()">
                                                                        <label class="form-check-label"
                                                                            for="month_<?php echo e($index + 1); ?>">
                                                                            <?php echo e($month); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                        <input type="hidden" name="tuition_selected_months"
                                                            id="tuition_selected_months"
                                                            value="<?php echo e(old('tuition_selected_months', '')); ?>">
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
                                                            value="<?php echo e(old('tuition_payment_amount')); ?>"
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
                                                                <?php echo e(old('tuition_payment_method') == 'online' ? 'selected' : ''); ?>>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                <?php echo e(old('tuition_payment_method') == 'cash' ? 'selected' : ''); ?>>
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
                                                            value="<?php echo e(old('hostel_admission_payment_amount')); ?>"
                                                            placeholder="Enter Payment Amount">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">PAYMENT METHOD</label>
                                                        <select name="hostel_admission_payment_method"
                                                            class="form-control">
                                                            <option value="" disabled selected>Select Payment Method
                                                            </option>
                                                            <option value="online"
                                                                <?php echo e(old('hostel_admission_payment_method') == 'online' ? 'selected' : ''); ?>>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                <?php echo e(old('hostel_admission_payment_method') == 'cash' ? 'selected' : ''); ?>>
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
                                                            <?php
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
                                                            ?>
                                                            <?php
                                                                $oldMonths = old('hostel_tuition_months', []);
                                                            ?>
                                                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check">
                                                                        <input
                                                                            class="form-check-input hostel-tuition-month-checkbox"
                                                                            type="checkbox" name="hostel_tuition_months[]"
                                                                            value="<?php echo e($index + 1); ?>"
                                                                            id="hostel_month_<?php echo e($index + 1); ?>"
                                                                            <?php echo e(in_array($index + 1, $oldMonths) ? 'checked' : ''); ?>

                                                                            onchange="calculateHostelTuitionFee()">
                                                                        <label class="form-check-label"
                                                                            for="hostel_month_<?php echo e($index + 1); ?>">
                                                                            <?php echo e($month); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                        <input type="hidden" name="hostel_tuition_selected_months"
                                                            id="hostel_tuition_selected_months"
                                                            value="<?php echo e(old('hostel_tuition_selected_months', '')); ?>">
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
                                                            value="<?php echo e(old('hostel_tuition_payment_amount')); ?>"
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
                                                                <?php echo e(old('hostel_tuition_payment_method') == 'online' ? 'selected' : ''); ?>>
                                                                Online Payment</option>
                                                            <option value="cash"
                                                                <?php echo e(old('hostel_tuition_payment_method') == 'cash' ? 'selected' : ''); ?>>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/students.js')); ?>"></script>
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

        function previewAadhaar(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId + '_img').src = e.target.result;
                    document.getElementById(previewId + '_container').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeAadhaarPreview(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId + '_img').src = '';
            document.getElementById(previewId + '_container').style.display = 'none';
        }

        function removePhoto() {
            document.getElementById('photo').value = '';
            document.getElementById('photoPreview').src = '';
            document.getElementById('photoPreviewContainer').style.display = 'none';
        }

        // Set institution ID for JavaScript
        window.institutionId = <?php echo e($institution->id); ?>;

        // Fee Structure data from PHP
        window.feeStructures = <?php echo json_encode($feeStructure, 15, 512) ?>;

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

                // Apply discount if any
                applyDiscount();
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

        // Function to apply discount to admission fee
        function applyDiscount(type) {
            const admissionFeeElement = document.getElementById('admission_fee_amount');
            const admissionFeeText = admissionFeeElement.textContent;
            const originalFee = parseFloat(admissionFeeText.replace(/[₹,\s]/g, '')) || 0;

            const discountAmountInput = document.getElementById('discount_amount');
            const discountPercentageInput = document.getElementById('discount_percentage');

            let discountAmount = parseFloat(discountAmountInput.value) || 0;
            let discountPercentage = parseFloat(discountPercentageInput.value) || 0;

            if (type === 'amount' && originalFee > 0) {
                discountPercentage = (discountAmount / originalFee) * 100;
                discountPercentageInput.value = discountPercentage.toFixed(2);
            } else if (type === 'percent' && originalFee > 0) {
                discountAmount = (discountPercentage / 100) * originalFee;
                discountAmountInput.value = discountAmount.toFixed(2);
            }

            const paymentAmountInput = document.getElementById('admission_payment_amount');
            const netAmount = Math.max(0, originalFee - discountAmount);

            // Auto-fill payment amount with net amount if it was empty or same as original
            if (!paymentAmountInput.value || parseFloat(paymentAmountInput.value) == originalFee) {
                paymentAmountInput.value = netAmount.toFixed(2);
            }

            calculateDueAmount();
        }

        function calculateDueAmount(admissionFeeAmount = null) {
            const paymentAmountInput = document.getElementById('admission_payment_amount');
            const dueAmountElement = document.getElementById('admission_due_amount');
            const discountAmountInput = document.getElementById('discount_amount');
            const discountAmount = parseFloat(discountAmountInput.value) || 0;

            // Get admission fee amount if not provided
            if (admissionFeeAmount === null) {
                const admissionFeeText = document.getElementById('admission_fee_amount').textContent;
                admissionFeeAmount = parseFloat(admissionFeeText.replace(/[₹,\s]/g, '')) || 0;
            }

            const netFeeAmount = Math.max(0, admissionFeeAmount - discountAmount);
            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            const dueAmount = Math.max(0, netFeeAmount - paymentAmount);

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

        // Age Calculation Logic
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob_input');
            const ageYearsInput = document.getElementById('age_years');
            const ageMonthsInput = document.getElementById('age_months');
            const targetDate = new Date('2026-01-31');

            function updateAge(selectedDate) {
                if (!selectedDate) {
                    ageYearsInput.value = '';
                    ageMonthsInput.value = '';
                    return;
                }

                let years = targetDate.getFullYear() - selectedDate.getFullYear();
                let months = targetDate.getMonth() - selectedDate.getMonth();

                if (targetDate.getDate() < selectedDate.getDate()) {
                    months--;
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                if (years < 0) {
                    ageYearsInput.value = '0';
                    ageMonthsInput.value = '0';
                } else {
                    ageYearsInput.value = years;
                    ageMonthsInput.value = months;
                }
            }

            // Hook into flatpickr
            const checkFlatpickr = setInterval(() => {
                if (dobInput._flatpickr) {
                    clearInterval(checkFlatpickr);

                    // Add listener
                    dobInput._flatpickr.set('onChange', function(selectedDates) {
                        if (selectedDates.length > 0) {
                            updateAge(selectedDates[0]);
                        } else {
                            updateAge(null);
                        }
                    });

                    // Initial calculation
                    if (dobInput._flatpickr.selectedDates.length > 0) {
                        updateAge(dobInput._flatpickr.selectedDates[0]);
                    }
                }
            }, 100);

            // Cleanup interval after 5 seconds if flatpickr not found
            setTimeout(() => clearInterval(checkFlatpickr), 5000);

            // Fallback for manual input
            dobInput.addEventListener('change', function() {
                if (!this._flatpickr) {
                    updateAge(new Date(this.value));
                }
            });
        });

        // Sibling Search and Selection Logic
        document.addEventListener('DOMContentLoaded', function() {
            const hasSiblingCheckbox = document.getElementById('has_sibling');
            const siblingSection = document.getElementById('sibling_section');
            const siblingSearch = document.getElementById('sibling_search');
            const siblingResults = document.getElementById('sibling_results');
            const selectedSiblingsContainer = document.getElementById('selected_siblings_container');
            const siblingActions = document.getElementById('sibling_actions');
            const copyParentInfoCheckbox = document.getElementById('copy_parent_info');

            let selectedSiblings = [];

            // Toggle sibling section
            hasSiblingCheckbox.addEventListener('change', function() {
                siblingSection.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    resetSiblingSelection();
                }
            });

            // Sibling search with debounce
            let debounceTimer;
            siblingSearch.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    siblingResults.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`<?php echo e(route('institution.admission.search-siblings')); ?>?q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            siblingResults.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(student => {
                                    // Don't show already selected siblings
                                    if (selectedSiblings.some(s => s.id === student.id))
                                        return;

                                    const item = document.createElement('a');
                                    item.href = '#';
                                    item.className =
                                        'list-group-item list-group-item-action';
                                    item.innerHTML = `
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">${student.first_name} ${student.last_name}</h6>
                                            <small>${student.admission_number || 'No Admission #'}</small>
                                        </div>
                                        <p class="mb-1" style="font-size: 0.8rem;">Father: ${student.father_name || 'N/A'}</p>
                                    `;
                                    item.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        addSibling(student);
                                    });
                                    siblingResults.appendChild(item);
                                });
                                siblingResults.style.display = 'block';
                            } else {
                                siblingResults.innerHTML =
                                    '<div class="list-group-item">No students found</div>';
                                siblingResults.style.display = 'block';
                            }
                        });
                }, 300);
            });

            // Close results when clicking outside
            document.addEventListener('click', function(e) {
                if (!siblingSearch.contains(e.target) && !siblingResults.contains(e.target)) {
                    siblingResults.style.display = 'none';
                }
            });

            function addSibling(student) {
                selectedSiblings.push(student);
                renderSelectedSiblings();
                siblingResults.style.display = 'none';
                siblingSearch.value = '';
                siblingActions.style.display = 'block';
            }

            function removeSibling(studentId) {
                selectedSiblings = selectedSiblings.filter(s => s.id !== studentId);
                renderSelectedSiblings();
                if (selectedSiblings.length === 0) {
                    siblingActions.style.display = 'none';
                    copyParentInfoCheckbox.checked = false;
                }
            }

            function renderSelectedSiblings() {
                selectedSiblingsContainer.innerHTML = '';
                selectedSiblings.forEach(student => {
                    const div = document.createElement('div');
                    div.className =
                        'alert alert-info d-flex justify-content-between align-items-center mb-2';
                    div.innerHTML = `
                        <div>
                            <input type="hidden" name="sibling_ids[]" value="${student.id}">
                            <strong>Sibling:</strong> ${student.first_name} ${student.last_name} 
                            (${student.admission_number || 'N/A'})
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-sibling-btn" data-id="${student.id}">
                            <i class="ti ti-x"></i> Remove
                        </button>
                    `;
                    selectedSiblingsContainer.appendChild(div);
                });

                // Re-attach event listeners to remove buttons
                document.querySelectorAll('.remove-sibling-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        removeSibling(parseInt(this.dataset.id));
                    });
                });
            }

            function resetSiblingSelection() {
                selectedSiblings = [];
                renderSelectedSiblings();
                siblingActions.style.display = 'none';
                copyParentInfoCheckbox.checked = false;
            }

            // Copy parent and address info
            copyParentInfoCheckbox.addEventListener('change', function() {
                if (this.checked && selectedSiblings.length > 0) {
                    const student = selectedSiblings[0]; // Copy from the first selected sibling

                    // Fields to copy
                    const fields = {
                        'father_name': student.father_name,
                        'mother_name': student.mother_name,
                        'father_occupation': student.father_occupation,
                        'father_phone': student.father_phone,
                        'permanent_address': student.permanent_address || student.address,
                        'permanent_pincode': student.permanent_pincode || student.pincode,
                        'permanent_district': student.permanent_district || student.district,
                        'address': student.address,
                        'pincode': student.pincode,
                        'district': student.district
                    };

                    for (const [name, value] of Object.entries(fields)) {
                        const input = document.querySelector(`[name="${name}"]`);
                        if (input && value) {
                            input.value = value;
                        }
                    }

                    // Also copy caste/tribe and religion if available
                    if (student.caste_tribe) {
                        const casteInput = document.querySelector('[name="caste_tribe"]');
                        if (casteInput) casteInput.value = student.caste_tribe;
                    }
                    if (student.religion) {
                        const religionInput = document.querySelector('[name="religion"]');
                        if (religionInput) religionInput.value = student.religion;
                    }

                    // Enforce same parents for all siblings is naturally handled by copying to the form
                    // which will be saved for the new student. The link between siblings is maintained by IDs.
                }
            });
        });

        let currentInputId = null;
        let stream = null;
        let cameraModalInstance = null;

        function openCamera(inputId) {
            currentInputId = inputId;
            const modalElement = document.getElementById('cameraModal');

            if (!cameraModalInstance) {
                cameraModalInstance = new bootstrap.Modal(modalElement);
            }

            cameraModalInstance.show();
            startCamera();
        }

        async function startCamera() {
            try {
                const constraints = {
                    video: {
                        facingMode: 'environment',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                const video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            } catch (err) {
                console.error("Error accessing camera: ", err);
                alert("Could not access camera. Please ensure you have given permission.");
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        // Wait for DOM to be ready before attaching listeners
        document.addEventListener('DOMContentLoaded', function() {
            const cameraModal = document.getElementById('cameraModal');
            if (cameraModal) {
                cameraModal.addEventListener('hidden.bs.modal', stopCamera);
            }

            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn) {
                captureBtn.addEventListener('click', function() {
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    const context = canvas.getContext('2d');

                    if (!video.videoWidth || video.readyState !== 4) {
                        alert("Camera is still loading, please wait...");
                        return;
                    }

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(function(blob) {
                        if (!blob) return;

                        const file = new File([blob], "captured_photo.jpg", {
                            type: "image/jpeg"
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        const input = document.getElementById(currentInputId);
                        if (input) {
                            input.files = dataTransfer.files;
                            const event = new Event('change', {
                                bubbles: true
                            });
                            input.dispatchEvent(event);
                        }

                        if (cameraModalInstance) {
                            cameraModalInstance.hide();
                        }
                    }, 'image/jpeg', 0.9);
                });
            }
        });
    </script>

    <style>
        #camera-container {
            background: #000;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        #video {
            transform: scaleX(-1);
            /* Mirror effect */
        }

        .camera-controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 15px;
        }

        #capture-btn {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background-color: #0d6efd;
            /* Fallback to blue if primary is different */
        }

        #capture-btn i {
            font-size: 32px !important;
            color: #fff;
        }

        #capture-btn:hover {
            transform: scale(1.1);
            background-color: #0b5ed7;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .document-upload-btn {
            cursor: pointer;
            border: 1px dashed #ced4da;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            transition: all 0.2s;
        }

        .document-upload-btn:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd;
        }
    </style>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true"
        style="z-index: 9999;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Take Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="camera-container" class="position-relative bg-dark rounded overflow-hidden mb-3"
                        style="min-height: 400px;">
                        <video id="video" autoplay playsinline class="w-100 h-100"
                            style="object-fit: cover;"></video>
                        <canvas id="canvas" class="d-none"></canvas>
                    </div>
                    <div class="camera-controls">
                        <button type="button" id="capture-btn" class="btn btn-primary rounded-circle">
                            <i class="ti ti-camera"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/students/admission/admission-form.blade.php ENDPATH**/ ?>