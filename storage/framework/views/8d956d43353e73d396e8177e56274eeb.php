<?php $__env->startSection('title', 'Payment Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Dashboard</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payments</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Payment Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Fees</h6>
                            <h4 class="mb-0">₹<?php echo e(number_format($totalFees, 2)); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-receipt" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Paid</h6>
                            <h4 class="mb-0">₹<?php echo e(number_format($totalPaid, 2)); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-check" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Balance Due</h6>
                            <h4 class="mb-0">₹<?php echo e(number_format($totalBalance, 2)); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Overdue</h6>
                            <h4 class="mb-0"><?php echo e($overdueFees); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo e(route('student.payments.pending')); ?>" class="btn btn-outline-warning">
                            <i class="ti ti-clock me-1"></i>View Pending Payments
                        </a>
                        <a href="<?php echo e(route('student.payments.history')); ?>" class="btn btn-outline-info">
                            <i class="ti ti-history me-1"></i>Payment History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Fees and Payments -->
    <div class="row">
        <!-- Recent Fees -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Fees</h6>
                    <a href="<?php echo e(route('student.payments.pending')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if($studentFees->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $studentFees->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($fee->feeStructure->fee_name); ?></h6>
                                        <small class="text-muted">
                                            <?php echo e($fee->feeStructure->schoolClass->name ?? 'N/A'); ?>

                                            <?php if($fee->feeStructure->section): ?>
                                                - <?php echo e($fee->feeStructure->section->name); ?>

                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">₹<?php echo e(number_format($fee->amount, 2)); ?></span>
                                        <br>
                                        <span class="badge 
                                            <?php if($fee->status == 'paid'): ?> bg-success
                                            <?php elseif($fee->status == 'partial'): ?> bg-warning
                                            <?php elseif($fee->status == 'overdue'): ?> bg-danger
                                            <?php else: ?> bg-secondary <?php endif; ?>">
                                            <?php echo e(ucfirst($fee->status)); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="ti ti-receipt-off" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">No fees found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Payments</h6>
                    <a href="<?php echo e(route('student.payments.history')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if($payments->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $payments->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($payment->studentFee->feeStructure->fee_name); ?></h6>
                                        <small class="text-muted">
                                            <?php echo e($payment->getFormattedPaymentDate()); ?> - 
                                            <?php echo e(ucfirst($payment->payment_method)); ?>

                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-success">₹<?php echo e(number_format($payment->amount, 2)); ?></span>
                                        <br>
                                        <span class="badge bg-success">Completed</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="ti ti-credit-card-off" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">No payments found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Status Overview -->
    <?php if($studentFees->count() > 0): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Fee Status Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fee Name</th>
                                        <th>Class</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $studentFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($fee->feeStructure->fee_name); ?></h6>
                                                    <?php if($fee->feeStructure->description): ?>
                                                        <small class="text-muted"><?php echo e(Str::limit($fee->feeStructure->description, 30)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e($fee->feeStructure->schoolClass->name ?? 'N/A'); ?>

                                                <?php if($fee->feeStructure->section): ?>
                                                    <br><small class="text-muted"><?php echo e($fee->feeStructure->section->name); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold">₹<?php echo e(number_format($fee->amount, 2)); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-success">₹<?php echo e(number_format($fee->paid_amount, 2)); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-warning">₹<?php echo e(number_format($fee->balance_amount, 2)); ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-bold <?php echo e($fee->isOverdue() ? 'text-danger' : ($fee->getDueDateStatus() == 'due_today' ? 'text-warning' : 'text-success')); ?>">
                                                    <?php echo e($fee->getFormattedDueDate()); ?>

                                                </span>
                                                <?php if($fee->isOverdue()): ?>
                                                    <br><small class="text-danger">
                                                        <i class="ti ti-alert-circle me-1"></i>
                                                        Overdue by <?php echo e($fee->getDaysOverdue()); ?> days
                                                    </small>
                                                <?php elseif($fee->getDueDateStatus() == 'due_today'): ?>
                                                    <br><small class="text-warning">
                                                        <i class="ti ti-clock me-1"></i>
                                                        Due today
                                                    </small>
                                                <?php elseif($fee->getDueDateStatus() == 'due_soon'): ?>
                                                    <br><small class="text-warning">
                                                        <i class="ti ti-clock me-1"></i>
                                                        Due in <?php echo e($fee->getDaysUntilDue()); ?> days
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    <?php if($fee->status == 'paid'): ?> bg-success
                                                    <?php elseif($fee->status == 'partial'): ?> bg-warning
                                                    <?php elseif($fee->status == 'overdue'): ?> bg-danger
                                                    <?php else: ?> bg-secondary <?php endif; ?>">
                                                    <?php echo e(ucfirst($fee->status)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('student.payments.fee-details', $fee)); ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/student/payment/index.blade.php ENDPATH**/ ?>