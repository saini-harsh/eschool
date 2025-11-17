

<?php $__env->startSection('title', 'Student Details'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .avatar-xxxl {
        width: 120px;
        height: 120px;
    }
    .nav-tab-dark .btn.active {
        background-color: #6366f1;
        color: white;
        border-color: #6366f1;
    }
    .nav-tab-dark .btn {
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }
    .nav-tab-dark .btn:hover {
        background-color: #e9ecef;
        color: #495057;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="content">
        <!-- start row -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div>
                    <h6 class="mb-3 fs-14"><a href="<?php echo e(route('admin.students.index')); ?>"><i class="ti ti-arrow-left me-1"></i>Students</a></h6>
                </div>
                
                <!-- Tab Navigation -->
                <div class="d-flex align-items-center flex-wrap row-gap-2 mb-3 pb-1 nav-tab-dark" role="tablist">
                    <a href="#nav_tab_1" class="btn btn-sm btn-light border fs-14 active me-2" data-bs-toggle="tab" role="tab">Personal Info</a>
                    <a href="#nav_tab_2" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Academic Info</a>
                    <a href="#nav_tab_3" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Parents & Guardian</a>
                    <a href="#nav_tab_4" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Documents</a>
                    <a href="#nav_tab_5" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Medical Info</a>
                    <a href="#nav_tab_6" class="btn btn-sm btn-light border fs-14" data-bs-toggle="tab" role="tab">Settings</a>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane show active" id="nav_tab_1" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-6 d-flex">
                                <div class="card rounded-0 shadow flex-fill mb-xl-0">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <?php if($student->photo): ?>
                                                <span class="avatar avatar-xxxl avatar-rounded mb-3">
                                                    <img src="<?php echo e(asset($student->photo)); ?>" alt="Student Photo">
                                                </span>
                                            <?php else: ?>
                                                <span class="avatar avatar-xxxl avatar-rounded mb-3 bg-light border">
                                                    <i class="ti ti-user fs-48 text-muted"></i>
                                                </span>
                                            <?php endif; ?>
                                            <h6 class="fs-16 mb-1"><?php echo e($student->first_name); ?> <?php echo e($student->middle_name); ?> <?php echo e($student->last_name); ?></h6>
                                            <p class="mb-0"><?php echo e($student->email); ?></p>
                                        </div>
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <div class="flex-fill">
                                                <div class="bg-light border rounded p-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Student ID</h6>
                                                    <p class="mb-0"><?php echo e($student->student_id); ?></p>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="bg-light border rounded p-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Date of Birth</h6>
                                                    <p class="mb-0"><?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M Y') : 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="bg-light border rounded p-2">
                                                    <h6 class="fw-semibold fs-14 mb-1">Gender</h6>
                                                    <p class="mb-0"><?php echo e($student->gender ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 d-flex">
                                <div class="card rounded-0 shadow flex-fill mb-0">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 fw-bold">Contact Details</h6>
                                        <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2 mb-3">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark"><?php echo e($student->phone ?? 'N/A'); ?></p>
                                            </div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2 mb-3">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark"><?php echo e($student->email ?? 'N/A'); ?></p>
                                            </div>
                                            <div class="bg-light border rounded d-flex align-items-center p-2">
                                                <span class="btn btn-icon btn-sm bg-white text-dark border flex-shrink-0 me-2">
                                                    <i class="ti ti-map-pin"></i>
                                                </span>
                                                <p class="mb-0 fs-13 text-dark"><?php echo e($student->address ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Info Tab -->
                    <div class="tab-pane" id="nav_tab_2" role="tabpanel">
                        <div>
                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Institution</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-primary d-inline-flex">
                                                    <?php echo e($student->institution->name ?? 'N/A'); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Class</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-dark d-inline-flex">
                                                    
                                                    <?php echo e($student->schoolClass->name ?? 'N/A'); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Section</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-dark d-inline-flex">
                                                    <?php echo e($student->section->name ?? 'N/A'); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Assigned Teacher</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <?php if($student->teacher): ?>
                                                    <span class="avatar avatar-sm avatar-rounded flex-shrink-0 me-2">
                                                        <?php if($student->teacher->photo): ?>
                                                            <img src="<?php echo e(asset($student->teacher->photo)); ?>" alt="">
                                                        <?php else: ?>
                                                            <i class="ti ti-user"></i>
                                                        <?php endif; ?>
                                                    </span>
                                                    <p class="mb-0"><?php echo e($student->teacher->first_name); ?> <?php echo e($student->teacher->last_name); ?></p>
                                                <?php else: ?>
                                                    <span class="text-muted">No teacher assigned</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow card-sm mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-md-5">
                                            <div>
                                                <h6 class="fw-semibold fs-14 mb-0">Admission Date</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-light border py-1 px-2 rounded fs-13 fw-medium text-dark d-inline-flex">
                                                    <?php echo e($student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A'); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-md-end">
                                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn p-1 border-0 btn-outline-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parents & Guardian Tab -->
                    <div class="tab-pane" id="nav_tab_3" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow mb-3">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Father Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <?php if($student->father_photo): ?>
                                                <span class="avatar avatar-xl avatar-rounded mb-3">
                                                    <img src="<?php echo e(asset($student->father_photo)); ?>" alt="Father Photo">
                                                </span>
                                            <?php else: ?>
                                                <span class="avatar avatar-xl avatar-rounded mb-3 bg-light border">
                                                    <i class="ti ti-user fs-24 text-muted"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Name</h6>
                                            <p class="mb-0"><?php echo e($student->father_name ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Phone</h6>
                                            <p class="mb-0"><?php echo e($student->father_phone ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Occupation</h6>
                                            <p class="mb-0"><?php echo e($student->father_occupation ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow mb-3">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Mother Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <?php if($student->mother_photo): ?>
                                                <span class="avatar avatar-xl avatar-rounded mb-3">
                                                    <img src="<?php echo e(asset($student->mother_photo)); ?>" alt="Mother Photo">
                                                </span>
                                            <?php else: ?>
                                                <span class="avatar avatar-xl avatar-rounded mb-3 bg-light border">
                                                    <i class="ti ti-user fs-24 text-muted"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Name</h6>
                                            <p class="mb-0"><?php echo e($student->mother_name ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Phone</h6>
                                            <p class="mb-0"><?php echo e($student->mother_phone ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Occupation</h6>
                                            <p class="mb-0"><?php echo e($student->mother_occupation ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow mb-0">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Guardian Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center">
                                                <?php if($student->guardian_photo): ?>
                                                    <span class="avatar avatar-xl avatar-rounded mb-3">
                                                        <img src="<?php echo e(asset($student->guardian_photo)); ?>" alt="Guardian Photo">
                                                    </span>
                                                <?php else: ?>
                                                    <span class="avatar avatar-xl avatar-rounded mb-3 bg-light border">
                                                        <i class="ti ti-user fs-24 text-muted"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="bg-light border rounded p-2 mb-2">
                                                            <h6 class="fw-semibold fs-14 mb-1">Name</h6>
                                                            <p class="mb-0"><?php echo e($student->guardian_name ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg-light border rounded p-2 mb-2">
                                                            <h6 class="fw-semibold fs-14 mb-1">Phone</h6>
                                                            <p class="mb-0"><?php echo e($student->guardian_phone ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg-light border rounded p-2 mb-2">
                                                            <h6 class="fw-semibold fs-14 mb-1">Relation</h6>
                                                            <p class="mb-0"><?php echo e($student->guardian_relation ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg-light border rounded p-2 mb-2">
                                                            <h6 class="fw-semibold fs-14 mb-1">Occupation</h6>
                                                            <p class="mb-0"><?php echo e($student->guardian_occupation ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="bg-light border rounded p-2">
                                                            <h6 class="fw-semibold fs-14 mb-1">Address</h6>
                                                            <p class="mb-0"><?php echo e($student->guardian_address ?? 'N/A'); ?></p>
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

                    <!-- Documents Tab -->
                    <div class="tab-pane" id="nav_tab_4" role="tabpanel">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0">Documents</h6>
                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn btn-primary fs-12 py-1">
                                    <i class="ti ti-circle-plus me-1"></i>Edit Documents
                                </a>
                            </div>
                            <div class="card-body">
                                <!-- New Document Details Section -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h6 class="fw-bold mb-0 text-dark">Aadhaar Card</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <strong>Number:</strong> <?php echo e($student->aadhaar_no ?? 'N/A'); ?>

                                                </div>
                                                <?php if($student->aadhaar_front || $student->aadhaar_back): ?>
                                                <div class="row">
                                                    <?php if($student->aadhaar_front): ?>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <strong class="d-block mb-2">Front</strong>
                                                            <img src="<?php echo e(asset($student->aadhaar_front)); ?>" alt="Aadhaar Front" class="img-fluid border rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <a href="<?php echo e(asset($student->aadhaar_front)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                                                <i class="ti ti-external-link me-1"></i>Open
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if($student->aadhaar_back): ?>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <strong class="d-block mb-2">Back</strong>
                                                            <img src="<?php echo e(asset($student->aadhaar_back)); ?>" alt="Aadhaar Back" class="img-fluid border rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <a href="<?php echo e(asset($student->aadhaar_back)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                                                <i class="ti ti-external-link me-1"></i>Open
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php else: ?>
                                                <div class="text-center text-muted py-3">
                                                    <i class="ti ti-photo-off fs-24 mb-2 d-block"></i>
                                                    <p class="mb-0">No images uploaded</p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h6 class="fw-bold mb-0 text-dark">PAN Card</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <strong>Number:</strong> <?php echo e($student->pan_no ?? 'N/A'); ?>

                                                </div>
                                                <?php if($student->pan_front || $student->pan_back): ?>
                                                <div class="row">
                                                    <?php if($student->pan_front): ?>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <strong class="d-block mb-2">Front</strong>
                                                            <img src="<?php echo e(asset($student->pan_front)); ?>" alt="PAN Front" class="img-fluid border rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <a href="<?php echo e(asset($student->pan_front)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                                                <i class="ti ti-external-link me-1"></i>Open
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if($student->pan_back): ?>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <strong class="d-block mb-2">Back</strong>
                                                            <img src="<?php echo e(asset($student->pan_back)); ?>" alt="PAN Back" class="img-fluid border rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <a href="<?php echo e(asset($student->pan_back)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                                                <i class="ti ti-external-link me-1"></i>Open
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php else: ?>
                                                <div class="text-center text-muted py-3">
                                                    <i class="ti ti-photo-off fs-24 mb-2 d-block"></i>
                                                    <p class="mb-0">No images uploaded</p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PEN Number Section -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h6 class="fw-bold mb-0 text-dark">PEN Number</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <strong>Number:</strong> <?php echo e($student->pen_no ?? 'N/A'); ?>

                                                </div>
                                                <div class="mb-2">
                                                    <strong>Student ID:</strong> <?php echo e($student->student_id ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3">Other Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-nowrap border">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($student->document_01_file): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 fs-14"><?php echo e($student->document_01_title ?? 'Document 01'); ?></h6>
                                                </td>
                                                <td>
                                                    <i class="ti ti-file-type-pdf fs-20 text-danger"></i>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_01_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-download me-1"></i>Download
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_01_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($student->document_02_file): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 fs-14"><?php echo e($student->document_02_title ?? 'Document 02'); ?></h6>
                                                </td>
                                                <td>
                                                    <i class="ti ti-file-type-pdf fs-20 text-danger"></i>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_02_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-download me-1"></i>Download
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_02_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($student->document_03_file): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 fs-14"><?php echo e($student->document_03_title ?? 'Document 03'); ?></h6>
                                                </td>
                                                <td>
                                                    <i class="ti ti-file-type-pdf fs-20 text-danger"></i>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_03_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-download me-1"></i>Download
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_03_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($student->document_04_file): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 fs-14"><?php echo e($student->document_04_title ?? 'Document 04'); ?></h6>
                                                </td>
                                                <td>
                                                    <i class="ti ti-file-type-pdf fs-20 text-danger"></i>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_04_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-download me-1"></i>Download
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(asset($student->document_04_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if(!$student->document_01_file && !$student->document_02_file && !$student->document_03_file && !$student->document_04_file): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="ti ti-file-off fs-48 mb-3 d-block"></i>
                                                    <p class="mb-0">No documents uploaded</p>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Info Tab -->
                    <div class="tab-pane" id="nav_tab_5" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow mb-3">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Medical Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Blood Group</h6>
                                            <p class="mb-0"><?php echo e($student->blood_group ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Category</h6>
                                            <p class="mb-0"><?php echo e($student->category ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Caste/Tribe</h6>
                                            <p class="mb-0"><?php echo e($student->caste_tribe ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2">
                                            <h6 class="fw-semibold fs-14 mb-1">District</h6>
                                            <p class="mb-0"><?php echo e($student->district ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow mb-3">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0 text-primary">Address Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Current Address</h6>
                                            <p class="mb-0"><?php echo e($student->address ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2 mb-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Permanent Address</h6>
                                            <p class="mb-0"><?php echo e($student->permanent_address ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="bg-light border rounded p-2">
                                            <h6 class="fw-semibold fs-14 mb-1">Pincode</h6>
                                            <p class="mb-0"><?php echo e($student->pincode ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane" id="nav_tab_6" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-7">
                                <div class="card rounded-0 mb-xl-0">
                                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                        <h6 class="fw-bold mb-0">Student Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Student ID</h6>
                                                <p class="mb-0 fs-13"><?php echo e($student->student_id ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Email</h6>
                                                <p class="mb-0 fs-13"><?php echo e($student->email ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Phone</h6>
                                                <p class="mb-0 fs-13"><?php echo e($student->phone ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                            <div>
                                                <h6 class="fs-14 fw-semibold mb-1">Status</h6>
                                                <p class="mb-0 fs-13">
                                                    <?php if($student->status == 'active'): ?>
                                                        <span class="badge badge-soft-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5">
                                <div class="card rounded-0 mb-0">
                                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                                        <h6 class="fw-bold mb-0">Quick Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn btn-primary">
                                                <i class="ti ti-edit me-1"></i>Edit Student
                                            </a>
                                            <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-secondary">
                                                <i class="ti ti-arrow-left me-1"></i>Back to List
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding tab pane
                const targetPane = document.querySelector(this.getAttribute('href'));
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/administration/students/show.blade.php ENDPATH**/ ?>