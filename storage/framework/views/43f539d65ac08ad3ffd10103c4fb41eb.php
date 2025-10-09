

<?php $__env->startSection('title', 'Student Settings'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Student Settings</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('student.dashboard')); ?>">
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
            <a href="#nav_tab_1" class="btn btn-sm btn-light border active fs-14 me-2" data-bs-toggle="tab" role="tab">Personal Info</a>
            <a href="#nav_tab_2" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Parents & Guardian Info</a>
            <a href="#nav_tab_3" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Document Info</a>
            <a href="#nav_tab_4" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Change Password</a>
        </div>

        <div class="tab-content">
            <!-- Personal Info Tab -->
            <div class="tab-pane show active" id="nav_tab_1" role="tabpanel">
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
                                                   value="<?php echo e($student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : ''); ?>">
                                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GROUP</label>
                                        <select name="group" class="form-control">
                                            <option value="">Group</option>
                                            <option value="Science" <?php echo e($student->group == 'Science' ? 'selected' : ''); ?>>Science</option>
                                            <option value="Arts" <?php echo e($student->group == 'Arts' ? 'selected' : ''); ?>>Arts</option>
                                            <option value="Commerce" <?php echo e($student->group == 'Commerce' ? 'selected' : ''); ?>>Commerce</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ADMISSION NUMBER</label>
                                        <input type="text" name="admission_number" class="form-control" value="<?php echo e($student->admission_number); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Roll</label>
                                        <input type="text" name="roll_number" class="form-control" value="<?php echo e($student->roll_number); ?>">
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
                                        <input type="email" name="email" class="form-control" value="<?php echo e($student->email); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PHONE NUMBER</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo e($student->phone); ?>">
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
                                        <textarea name="address" class="form-control" rows="3" required><?php echo e($student->address); ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PINCODE <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" class="form-control" value="<?php echo e($student->pincode); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">DISTRICT <span class="text-danger">*</span></label>
                                        <input type="text" name="district" class="form-control" value="<?php echo e($student->district); ?>" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">PERMANENT ADDRESS</label>
                                        <textarea name="permanent_address" class="form-control" rows="3"><?php echo e($student->permanent_address); ?></textarea>
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
                                        <input type="text" name="first_name" class="form-control" value="<?php echo e($student->first_name); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">LAST NAME</label>
                                        <input type="text" name="last_name" class="form-control" value="<?php echo e($student->last_name); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GENDER</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Gender</option>
                                            <option value="Male" <?php echo e($student->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                                            <option value="Female" <?php echo e($student->gender == 'Female' ? 'selected' : ''); ?>>Female</option>
                                            <option value="Other" <?php echo e($student->gender == 'Other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">DATE OF BIRTH</label>
                                        <div class="input-group w-auto input-group-flat">
                                            <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                                   data-date-format="d M, Y" placeholder="dd/mm/yyyy" 
                                                   value="<?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M, Y') : ''); ?>">
                                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">RELIGION</label>
                                        <select name="religion" class="form-control">
                                            <option value="">Religion</option>
                                            <option value="Islam" <?php echo e($student->religion == 'Islam' ? 'selected' : ''); ?>>Islam</option>
                                            <option value="Hinduism" <?php echo e($student->religion == 'Hinduism' ? 'selected' : ''); ?>>Hinduism</option>
                                            <option value="Christianity" <?php echo e($student->religion == 'Christianity' ? 'selected' : ''); ?>>Christianity</option>
                                            <option value="Buddhism" <?php echo e($student->religion == 'Buddhism' ? 'selected' : ''); ?>>Buddhism</option>
                                            <option value="Other" <?php echo e($student->religion == 'Other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CASTE</label>
                                        <input type="text" name="caste_tribe" class="form-control" value="<?php echo e($student->caste_tribe); ?>">
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
                                            <div class="photo-preview mt-2" id="photoPreviewContainer" style="<?php echo e($student->photo ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="photoPreview" src="<?php echo e($student->photo ? asset($student->photo) : ''); ?>" alt="Profile Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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
                                            <option value="A+" <?php echo e($student->blood_group == 'A+' ? 'selected' : ''); ?>>A+</option>
                                            <option value="A-" <?php echo e($student->blood_group == 'A-' ? 'selected' : ''); ?>>A-</option>
                                            <option value="B+" <?php echo e($student->blood_group == 'B+' ? 'selected' : ''); ?>>B+</option>
                                            <option value="B-" <?php echo e($student->blood_group == 'B-' ? 'selected' : ''); ?>>B-</option>
                                            <option value="AB+" <?php echo e($student->blood_group == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                                            <option value="AB-" <?php echo e($student->blood_group == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                                            <option value="O+" <?php echo e($student->blood_group == 'O+' ? 'selected' : ''); ?>>O+</option>
                                            <option value="O-" <?php echo e($student->blood_group == 'O-' ? 'selected' : ''); ?>>O-</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CATEGORY</label>
                                        <select name="category" class="form-control">
                                            <option value="">Category</option>
                                            <option value="General" <?php echo e($student->category == 'General' ? 'selected' : ''); ?>>General</option>
                                            <option value="OBC" <?php echo e($student->category == 'OBC' ? 'selected' : ''); ?>>OBC</option>
                                            <option value="SC" <?php echo e($student->category == 'SC' ? 'selected' : ''); ?>>SC</option>
                                            <option value="ST" <?php echo e($student->category == 'ST' ? 'selected' : ''); ?>>ST</option>
                                            <option value="EWS" <?php echo e($student->category == 'EWS' ? 'selected' : ''); ?>>EWS</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">HEIGHT</label>
                                        <input type="text" name="height" class="form-control" value="<?php echo e($student->height); ?>" placeholder="e.g., 5'6">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">WEIGHT</label>
                                        <input type="text" name="weight" class="form-control" value="<?php echo e($student->weight); ?>" placeholder="e.g., 60 kg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parents & Guardian Info Tab -->
            <div class="tab-pane" id="nav_tab_2" role="tabpanel">
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
                                        <input type="text" name="father_name" class="form-control" value="<?php echo e($student->father_name); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">FATHER OCCUPATION</label>
                                        <input type="text" name="father_occupation" class="form-control" value="<?php echo e($student->father_occupation); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">FATHER PHONE</label>
                                        <input type="text" name="father_phone" class="form-control" value="<?php echo e($student->father_phone); ?>">
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
                                            <div class="photo-preview mt-2" id="fatherPhotoPreviewContainer" style="<?php echo e($student->father_photo ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="fatherPhotoPreview" src="<?php echo e($student->father_photo ? asset($student->father_photo) : ''); ?>" alt="Father Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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
                                        <input type="text" name="mother_name" class="form-control" value="<?php echo e($student->mother_name); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">MOTHER OCCUPATION</label>
                                        <input type="text" name="mother_occupation" class="form-control" value="<?php echo e($student->mother_occupation); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">MOTHER PHONE</label>
                                        <input type="text" name="mother_phone" class="form-control" value="<?php echo e($student->mother_phone); ?>">
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
                                            <div class="photo-preview mt-2" id="motherPhotoPreviewContainer" style="<?php echo e($student->mother_photo ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="motherPhotoPreview" src="<?php echo e($student->mother_photo ? asset($student->mother_photo) : ''); ?>" alt="Mother Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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
                                        <input type="text" name="guardian_name" class="form-control" value="<?php echo e($student->guardian_name); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">GUARDIAN RELATION</label>
                                        <select name="guardian_relation" class="form-control" onchange="toggleGuardianRelationText(this.value)">
                                            <option value="">Relation</option>
                                            <option value="Father" <?php echo e($student->guardian_relation == 'Father' ? 'selected' : ''); ?>>Father</option>
                                            <option value="Mother" <?php echo e($student->guardian_relation == 'Mother' ? 'selected' : ''); ?>>Mother</option>
                                            <option value="Grandfather" <?php echo e($student->guardian_relation == 'Grandfather' ? 'selected' : ''); ?>>Grandfather</option>
                                            <option value="Grandmother" <?php echo e($student->guardian_relation == 'Grandmother' ? 'selected' : ''); ?>>Grandmother</option>
                                            <option value="Uncle" <?php echo e($student->guardian_relation == 'Uncle' ? 'selected' : ''); ?>>Uncle</option>
                                            <option value="Aunt" <?php echo e($student->guardian_relation == 'Aunt' ? 'selected' : ''); ?>>Aunt</option>
                                            <option value="Other" <?php echo e($student->guardian_relation == 'Other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3" id="guardian_relation_text_div" style="display: <?php echo e($student->guardian_relation == 'Other' ? 'block' : 'none'); ?>;">
                                        <label class="form-label">RELATION (OTHER)</label>
                                        <input type="text" name="guardian_relation_text" class="form-control" value="<?php echo e($student->guardian_relation_text); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">GUARDIAN EMAIL</label>
                                        <input type="email" name="guardian_email" class="form-control" value="<?php echo e($student->guardian_email); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">GUARDIAN PHONE</label>
                                        <input type="text" name="guardian_phone" class="form-control" value="<?php echo e($student->guardian_phone); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">GUARDIAN OCCUPATION</label>
                                        <input type="text" name="guardian_occupation" class="form-control" value="<?php echo e($student->guardian_occupation); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">GUARDIAN ADDRESS</label>
                                        <textarea name="guardian_address" class="form-control" rows="3"><?php echo e($student->guardian_address); ?></textarea>
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
                                            <div class="photo-preview mt-2" id="guardianPhotoPreviewContainer" style="<?php echo e($student->guardian_photo ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="guardianPhotoPreview" src="<?php echo e($student->guardian_photo ? asset($student->guardian_photo) : ''); ?>" alt="Guardian Photo" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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
            <div class="tab-pane" id="nav_tab_3" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Document Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0 text-primary">DOCUMENT INFORMATION</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NATIONAL ID</label>
                                        <input type="text" name="national_id" class="form-control" value="<?php echo e($student->national_id); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">BIRTH CERTIFICATE NUMBER</label>
                                        <input type="text" name="birth_certificate_number" class="form-control" value="<?php echo e($student->birth_certificate_number); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">BANK NAME</label>
                                        <input type="text" name="bank_name" class="form-control" value="<?php echo e($student->bank_name); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">BANK ACCOUNT NUMBER</label>
                                        <input type="text" name="bank_account_number" class="form-control" value="<?php echo e($student->bank_account_number); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IFSC CODE</label>
                                        <input type="text" name="ifsc_code" class="form-control" value="<?php echo e($student->ifsc_code); ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ADDITIONAL NOTES</label>
                                        <textarea name="additional_notes" class="form-control" rows="3"><?php echo e($student->additional_notes); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Attachments -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0 text-primary">DOCUMENT ATTACHMENTS</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">DOCUMENT 01 TITLE</label>
                                        <input type="text" name="document_01_title" class="form-control" value="<?php echo e($student->document_01_title); ?>">
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
                                            <div class="photo-preview mt-2" id="document01PreviewContainer" style="<?php echo e($student->document_01_file ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="document01Preview" src="<?php echo e($student->document_01_file ? asset($student->document_01_file) : ''); ?>" alt="Document 01" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                <div class="photo-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_01_file').click()">Change</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument01()">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">DOCUMENT 02 TITLE</label>
                                        <input type="text" name="document_02_title" class="form-control" value="<?php echo e($student->document_02_title); ?>">
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
                                            <div class="photo-preview mt-2" id="document02PreviewContainer" style="<?php echo e($student->document_02_file ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="document02Preview" src="<?php echo e($student->document_02_file ? asset($student->document_02_file) : ''); ?>" alt="Document 02" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                <div class="photo-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_02_file').click()">Change</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument02()">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">DOCUMENT 03 TITLE</label>
                                        <input type="text" name="document_03_title" class="form-control" value="<?php echo e($student->document_03_title); ?>">
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
                                            <div class="photo-preview mt-2" id="document03PreviewContainer" style="<?php echo e($student->document_03_file ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="document03Preview" src="<?php echo e($student->document_03_file ? asset($student->document_03_file) : ''); ?>" alt="Document 03" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                                <div class="photo-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('document_03_file').click()">Change</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument03()">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">DOCUMENT 04 TITLE</label>
                                        <input type="text" name="document_04_title" class="form-control" value="<?php echo e($student->document_04_title); ?>">
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
                                            <div class="photo-preview mt-2" id="document04PreviewContainer" style="<?php echo e($student->document_04_file ? 'display: block;' : 'display: none;'); ?>">
                                                <img id="document04Preview" src="<?php echo e($student->document_04_file ? asset($student->document_04_file) : ''); ?>" alt="Document 04" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
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

            <!-- Change Password Tab -->
            <div class="tab-pane" id="nav_tab_4" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Change Password</h6>
                            </div>
                            <form id="change-password-form">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">CURRENT PASSWORD <span class="text-danger">*</span></label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">NEW PASSWORD <span class="text-danger">*</span></label>
                                        <input type="password" name="new_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CONFIRM NEW PASSWORD <span class="text-danger">*</span></label>
                                        <input type="password" name="new_password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">CHANGE PASSWORD</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" onclick="saveAllSettings()">SAVE ALL SETTINGS</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <?php $__env->startPush('styles'); ?>
        <style>
            .toast-container {
                z-index: 9999;
            }
            .toast {
                min-width: 300px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .toast-header {
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }
            .toast-body {
                padding: 12px 16px;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {
                // Initialize flatpickr for date inputs
                if (typeof flatpickr !== 'undefined') {
                    flatpickr("[data-provider='flatpickr']", {
                        dateFormat: "d M, Y"
                    });
                }

                // Toast notification function
                window.showToast = function(message, type = 'info') {
                    // Remove existing toasts
                    $('.toast-container').remove();
                    
                    // Create toast container
                    const toastContainer = $('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
                    $('body').append(toastContainer);
                    
                    // Determine toast class and icon
                    let toastClass = '';
                    let icon = '';
                    switch(type) {
                        case 'success':
                            toastClass = 'bg-success text-white';
                            icon = '<i class="ti ti-check-circle me-2"></i>';
                            break;
                        case 'error':
                            toastClass = 'bg-danger text-white';
                            icon = '<i class="ti ti-x-circle me-2"></i>';
                            break;
                        case 'warning':
                            toastClass = 'bg-warning text-dark';
                            icon = '<i class="ti ti-alert-triangle me-2"></i>';
                            break;
                        case 'info':
                        default:
                            toastClass = 'bg-info text-white';
                            icon = '<i class="ti ti-info-circle me-2"></i>';
                            break;
                    }
                    
                    // Create toast element
                    const toast = $(`
                        <div class="toast ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header ${toastClass}">
                                ${icon}
                                <strong class="me-auto">Notification</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        </div>
                    `);
                    
                    toastContainer.append(toast);
                    
                    // Initialize and show toast
                    const bsToast = new bootstrap.Toast(toast[0], {
                        autohide: true,
                        delay: type === 'error' ? 5000 : 3000
                    });
                    bsToast.show();
                    
                    // Remove toast container after hiding
                    toast.on('hidden.bs.toast', function() {
                        toastContainer.remove();
                    });
                };

                // Fallback for showAlert function (in case it's used elsewhere)
                window.showAlert = function(message, type) {
                    showToast(message, type);
                };

                // Guardian relation toggle
                window.toggleGuardianRelationText = function(value) {
                    const div = document.getElementById('guardian_relation_text_div');
                    if (value === 'Other') {
                        div.style.display = 'block';
                    } else {
                        div.style.display = 'none';
                    }
                };

                // Photo preview functions
                window.previewPhoto = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('photoPreview').src = e.target.result;
                            document.getElementById('photoPreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewFatherPhoto = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('fatherPhotoPreview').src = e.target.result;
                            document.getElementById('fatherPhotoPreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewMotherPhoto = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('motherPhotoPreview').src = e.target.result;
                            document.getElementById('motherPhotoPreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewGuardianPhoto = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('guardianPhotoPreview').src = e.target.result;
                            document.getElementById('guardianPhotoPreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                // Document preview functions
                window.previewDocument01 = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('document01Preview').src = e.target.result;
                            document.getElementById('document01PreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewDocument02 = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('document02Preview').src = e.target.result;
                            document.getElementById('document02PreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewDocument03 = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('document03Preview').src = e.target.result;
                            document.getElementById('document03PreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                window.previewDocument04 = function(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('document04Preview').src = e.target.result;
                            document.getElementById('document04PreviewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                };

                // Remove photo functions
                window.removePhoto = function() {
                    document.getElementById('photo').value = '';
                    document.getElementById('photoPreviewContainer').style.display = 'none';
                };

                window.removeFatherPhoto = function() {
                    document.getElementById('father_photo').value = '';
                    document.getElementById('fatherPhotoPreviewContainer').style.display = 'none';
                };

                window.removeMotherPhoto = function() {
                    document.getElementById('mother_photo').value = '';
                    document.getElementById('motherPhotoPreviewContainer').style.display = 'none';
                };

                window.removeGuardianPhoto = function() {
                    document.getElementById('guardian_photo').value = '';
                    document.getElementById('guardianPhotoPreviewContainer').style.display = 'none';
                };

                // Remove document functions
                window.removeDocument01 = function() {
                    document.getElementById('document_01_file').value = '';
                    document.getElementById('document01PreviewContainer').style.display = 'none';
                };

                window.removeDocument02 = function() {
                    document.getElementById('document_02_file').value = '';
                    document.getElementById('document02PreviewContainer').style.display = 'none';
                };

                window.removeDocument03 = function() {
                    document.getElementById('document_03_file').value = '';
                    document.getElementById('document03PreviewContainer').style.display = 'none';
                };

                window.removeDocument04 = function() {
                    document.getElementById('document_04_file').value = '';
                    document.getElementById('document04PreviewContainer').style.display = 'none';
                };

                // Save all settings function
                window.saveAllSettings = function() {
                    const formData = new FormData();
                    
                    // Collect all form data
                    const inputs = document.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.name && input.type !== 'file') {
                            formData.append(input.name, input.value);
                        }
                    });

                    // Add file inputs
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => {
                        if (input.files && input.files[0]) {
                            formData.append(input.name, input.files[0]);
                        }
                    });

                    // Show loading toast
                    showToast('Saving settings...', 'info');

                    $.ajax({
                        url: "<?php echo e(route('student.settings.profile')); ?>",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                showToast(response.message, 'success');
                            } else {
                                showToast(response.message, 'error');
                                displayValidationErrors(response.errors);
                            }
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON.errors;
                            if (errors) {
                                showToast('Validation failed. Please check the form.', 'error');
                                displayValidationErrors(errors);
                            } else {
                                showToast('An error occurred while updating profile.', 'error');
                            }
                        }
                    });
                };

                // Change Password Form Submission
                $('#change-password-form').submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    // Show loading toast
                    showToast('Changing password...', 'info');

                    $.ajax({
                        url: "<?php echo e(route('student.settings.change-password')); ?>",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                showToast(response.message, 'success');
                                $('#change-password-form')[0].reset();
                            } else {
                                showToast(response.message, 'error');
                                displayValidationErrors(response.errors);
                            }
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON.errors;
                            if (errors) {
                                showToast('Validation failed. Please check the form.', 'error');
                                displayValidationErrors(errors);
                            } else {
                                showToast('An error occurred while changing password.', 'error');
                            }
                        }
                    });
                });

                function displayValidationErrors(errors) {
                    $('.invalid-feedback').remove();
                    $('.form-control').removeClass('is-invalid');

                    for (const field in errors) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/student/settings/index.blade.php ENDPATH**/ ?>