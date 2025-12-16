<?php $__env->startSection('title', 'Admission Success'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <!-- Success Header -->
                <div class="card mb-4 border-success">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="ti ti-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="text-success fw-bold mb-3">Admission Submitted Successfully!</h2>
                        <p class="text-muted mb-4">The admission form has been submitted and payment records have been
                            created.</p>

                        <div class="alert alert-info d-inline-block">
                            <strong>Admission Number:</strong> <?php echo e($admission->admission_number ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>

                <!-- Admission Details -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ti ti-user me-2"></i>Admission Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Student Name:</strong> <?php echo e($admission->first_name); ?> <?php echo e($admission->last_name); ?>

                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Admission Date:</strong>
                                <?php echo e($admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') : 'N/A'); ?>

                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Class:</strong> <?php echo e($admission->schoolClass->name ?? 'N/A'); ?>

                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Roll Number:</strong> <?php echo e($admission->roll_number ?? 'N/A'); ?>

                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong> <?php echo e($admission->phone ?? 'N/A'); ?>

                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong> <?php echo e($admission->email ?? 'N/A'); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Records -->
                <div class="row">
                    <!-- Admission Fee Payment -->
                    <?php if($admissionPayments->where('feeStructure.fee_type', 'onetime')->count() > 0): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="ti ti-receipt me-2"></i>Admission Fee Payment</h5>
                                </div>
                                <div class="card-body">
                                    <?php $__currentLoopData = $admissionPayments->where('feeStructure.fee_type', 'onetime'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>Receipt Number:</strong>
                                                <span class="badge bg-primary"><?php echo e($payment->receipt_number); ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong> ₹<?php echo e(number_format($payment->amount, 2)); ?>

                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Method:</strong> <?php echo e(ucfirst($payment->payment_method)); ?>

                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Date:</strong>
                                                <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('d M, Y')); ?>

                                            </div>
                                            <a href="<?php echo e(route('institution.admission.receipt.admission', [$admission->id, $payment->id])); ?>"
                                                target="_blank" class="btn btn-sm btn-primary">
                                                <i class="ti ti-printer me-1"></i>Print Receipt
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tuition Fee Payment -->
                    <?php if($tuitionFeePayments->count() > 0): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="ti ti-receipt me-2"></i>Tuition Fee Payment</h5>
                                </div>
                                <div class="card-body">
                                    <?php $__currentLoopData = $tuitionFeePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>Receipt Number:</strong>
                                                <span
                                                    class="badge bg-warning text-dark"><?php echo e($payment->receipt_number); ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong> ₹<?php echo e(number_format($payment->payment_amount, 2)); ?>

                                            </div>
                                            <div class="mb-2">
                                                <strong>Months Paid:</strong>
                                                <?php if($payment->selected_months): ?>
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

                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Method:</strong> <?php echo e(ucfirst($payment->payment_method)); ?>

                                            </div>
                                            <div class="mb-2">
                                                <strong>Payment Date:</strong>
                                                <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('d M, Y')); ?>

                                            </div>
                                            <a href="<?php echo e(route('institution.admission.receipt.tuition', [$admission->id, $payment->id])); ?>"
                                                target="_blank" class="btn btn-sm btn-warning">
                                                <i class="ti ti-printer me-1"></i>Print Receipt
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="<?php echo e(route('institution.admission.print', $admission->id)); ?>" target="_blank"
                                class="btn btn-primary btn-lg">
                                <i class="ti ti-printer me-2"></i>Print Admission Form
                            </a>
                            <a href="<?php echo e(route('institution.admission.admission-form')); ?>"
                                class="btn btn-outline-secondary btn-lg">
                                <i class="ti ti-plus me-2"></i>Submit Another Admission
                            </a>
                            <a href="<?php echo e(route('institution.students.index')); ?>" class="btn btn-outline-primary btn-lg">
                                <i class="ti ti-arrow-left me-2"></i>Back to Students
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\school management software\eschool\resources\views/institution/administration/students/admission/admission-success.blade.php ENDPATH**/ ?>