<?php $__env->startSection('title', 'Admission Details'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-4">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Admission Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <i class="ti ti-home me-1"></i>Home
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('institution.students.index')); ?>">Students</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('institution.admission.list')); ?>">Admissions</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('institution.admission.print', $admission->id)); ?>" target="_blank"
                    class="btn btn-outline-primary">
                    <i class="ti ti-printer me-1"></i>Print Form
                </a>
                <a href="<?php echo e(route('institution.admission.list')); ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-12">
                <!-- Student Photo and Basic Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <?php if($admission->photo): ?>
                                    <img src="<?php echo e(asset($admission->photo)); ?>" alt="Student Photo"
                                        class="img-fluid rounded-circle"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                        style="width: 150px; height: 150px;">
                                        <span style="font-size: 60px;">
                                            <?php echo e(strtoupper(substr($admission->first_name, 0, 1))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-10">
                                <h3 class="fw-bold mb-2"><?php echo e($admission->first_name); ?> <?php echo e($admission->last_name); ?></h3>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Admission Number:</strong>
                                            <span
                                                class="badge bg-primary"><?php echo e($admission->admission_number ?? 'N/A'); ?></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Status:</strong>
                                            <?php if($admission->status == 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif($admission->status == 'rejected'): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Class:</strong>
                                            <span class="badge bg-info"><?php echo e($admission->schoolClass->name ?? 'N/A'); ?></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Roll Number:</strong>
                                            <?php echo e($admission->roll_number ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-school me-2"></i>Academic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small">Admission Date</label>
                                <p class="mb-0 fw-semibold">
                                    <?php echo e($admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') : 'N/A'); ?>

                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Submitted Date</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->created_at->format('d M, Y h:i A')); ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">PEN Number</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->pen_no ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Institution Code</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->institution_code ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="text-muted small">First Name</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->first_name); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Last Name</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->last_name); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Gender</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->gender ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Date of Birth</label>
                                <p class="mb-0 fw-semibold">
                                    <?php echo e($admission->dob ? \Carbon\Carbon::parse($admission->dob)->format('d M, Y') : 'N/A'); ?>

                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">DOB Status</label>
                                <p class="mb-0">
                                    <span
                                        class="badge <?php echo e($admission->dob_status == 'Verified' ? 'bg-success' : 'bg-warning'); ?>">
                                        <?php echo e($admission->dob_status ?? 'Not Verified'); ?>

                                    </span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Age (as of Jan 2026)</label>
                                <p class="mb-0 fw-semibold">
                                    <?php echo e($admission->age_years ?? '0'); ?> Years, <?php echo e($admission->age_months ?? '0'); ?> Months
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Religion</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->religion ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Caste/Tribe</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->caste_tribe ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Aadhaar Number</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->aadhaar_no ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-heartbeat me-2"></i>Medical Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small">Blood Group</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->blood_group ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Height</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->height ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Weight</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->weight ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-phone me-2"></i>Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->email ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Phone</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->phone ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-map-pin me-2"></i>Address Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <h6 class="fw-semibold mb-2">Current Address</h6>
                                <p class="mb-1"><strong>Address:</strong> <?php echo e($admission->address ?? 'N/A'); ?></p>
                                <p class="mb-1"><strong>Pincode:</strong> <?php echo e($admission->pincode ?? 'N/A'); ?></p>
                                <p class="mb-0"><strong>District:</strong> <?php echo e($admission->district ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-12">
                                <h6 class="fw-semibold mb-2">Permanent Address</h6>
                                <p class="mb-1"><strong>Address:</strong> <?php echo e($admission->permanent_address ?? 'N/A'); ?>

                                </p>
                                <p class="mb-1"><strong>Pincode:</strong> <?php echo e($admission->permanent_pincode ?? 'N/A'); ?>

                                </p>
                                <p class="mb-0"><strong>District:</strong> <?php echo e($admission->permanent_district ?? 'N/A'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-users me-2"></i>Parent Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Father Name</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->father_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Father Occupation</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->father_occupation ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Father Phone</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->father_phone ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Mother Name</label>
                                <p class="mb-0 fw-semibold"><?php echo e($admission->mother_name ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <?php if($admission->guardian_name): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0"><i class="ti ti-user-check me-2"></i>Guardian Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Guardian Name</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->guardian_name ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Relation</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->guardian_relation_text ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Guardian Phone</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->guardian_phone ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Guardian Address</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->guardian_address ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Previous Academic Information -->
                <?php if($admission->previous_school_name): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0"><i class="ti ti-school me-2"></i>Previous Academic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Previous School Name</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->previous_school_name ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Previous School Class</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->previousSchoolClass->name ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-12">
                                    <label class="text-muted small">Previous School Address</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->previous_school_address ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-12">
                                    <label class="text-muted small">Previous School Result</label>
                                    <p class="mb-0 fw-semibold"><?php echo e($admission->previous_school_result ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Payment Information -->
                <?php if($admissionPayments->count() > 0 || $tuitionFeePayments->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0"><i class="ti ti-receipt me-2"></i>Payment Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if($admission->discount_category): ?>
                                    <div class="col-md-12 mb-4">
                                        <div class="alert alert-info border-info mb-0">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-info-circle fs-20 me-2"></i>
                                                <div>
                                                    <p class="mb-0"><strong>Discount Applied:</strong>
                                                        <?php if($admission->discount_category == 'orphanage'): ?>
                                                            Orphanage
                                                        <?php elseif($admission->discount_category == 'teacher_child'): ?>
                                                            Teacher's Child
                                                        <?php elseif($admission->discount_category == 'personal_selection'): ?>
                                                            Personal Selection
                                                        <?php else: ?>
                                                            <?php echo e($admission->discount_category); ?>

                                                        <?php endif; ?>
                                                        - Amount: ₹<?php echo e(number_format($admission->discount_amount, 2)); ?>

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($admissionPayments->where('feeStructure.fee_type', 'onetime')->count() > 0): ?>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="fw-semibold mb-3">Admission Fee</h6>
                                        <?php $__currentLoopData = $admissionPayments->where('feeStructure.fee_type', 'onetime'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="border rounded p-3 mb-3">
                                                <p class="mb-2"><strong>Amount:</strong>
                                                    ₹<?php echo e(number_format($payment->amount, 2)); ?></p>
                                                <p class="mb-2"><strong>Payment Method:</strong>
                                                    <?php echo e(ucfirst($payment->payment_method)); ?></p>
                                                <p class="mb-2"><strong>Receipt Number:</strong>
                                                    <?php echo e($payment->receipt_number); ?></p>
                                                <p class="mb-0"><strong>Payment Date:</strong>
                                                    <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('d M, Y')); ?>

                                                </p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if($tuitionFeePayments->count() > 0): ?>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="fw-semibold mb-3">Tuition Fee</h6>
                                        <?php $__currentLoopData = $tuitionFeePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="border rounded p-3 mb-3">
                                                <p class="mb-2"><strong>Amount:</strong>
                                                    ₹<?php echo e(number_format($payment->payment_amount, 2)); ?></p>
                                                <p class="mb-2"><strong>Payment Method:</strong>
                                                    <?php echo e(ucfirst($payment->payment_method)); ?></p>
                                                <p class="mb-2"><strong>Receipt Number:</strong>
                                                    <?php echo e($payment->receipt_number); ?></p>
                                                <?php if($payment->selected_months): ?>
                                                    <p class="mb-2"><strong>Months Paid:</strong>
                                                        <?php
                                                            $months = [
                                                                '',
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
                                                            $monthNames = array_map(function ($m) use ($months) {
                                                                return $months[$m] ?? $m;
                                                            }, $payment->selected_months);
                                                        ?>
                                                        <?php echo e(implode(', ', $monthNames)); ?>

                                                    </p>
                                                <?php endif; ?>
                                                <p class="mb-0"><strong>Payment Date:</strong>
                                                    <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('d M, Y')); ?>

                                                </p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?php echo e(route('institution.admission.print', $admission->id)); ?>" target="_blank"
                                class="btn btn-primary">
                                <i class="ti ti-printer me-1"></i>Print Form
                            </a>
                            <a href="<?php echo e(route('institution.admission.list')); ?>" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/students/admission/admission-details.blade.php ENDPATH**/ ?>