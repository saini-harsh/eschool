<?php $__env->startSection('title', 'Payment Management'); ?>
<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-alert-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Management</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">Payment</li>
                    <li class="breadcrumb-item active" aria-current="page">Payments</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.payments.create')); ?>" class="btn btn-primary me-2">
                <i class="ti ti-plus me-1"></i>Record Payment
            </a>
            <button class="btn btn-outline-success" onclick="generateBills()">
                <i class="ti ti-refresh me-1"></i>Generate Bills
            </button>
        </div>
    </div>
    <!-- End Page Header -->

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Payment Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Payments</h6>
                            <h4 class="mb-0"><?php echo e($payments->total()); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-credit-card" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <h6 class="card-title text-white mb-1">Completed</h6>
                            <h4 class="mb-0"><?php echo e($payments->where('payment_status', 'completed')->count()); ?></h4>
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
                            <h6 class="card-title text-white mb-1">Pending</h6>
                            <h4 class="mb-0"><?php echo e($payments->where('payment_status', 'pending')->count()); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-white mb-1">Total Amount</h6>
                            <h4 class="mb-0">₹<?php echo e(number_format($payments->sum('amount'), 2)); ?></h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ti ti-currency-rupee" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">Payment Records</h6>
        </div>
        <div class="card-body">
            <?php if($payments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payment Ref</th>
                                <th>Student</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?php echo e($payment->payment_reference); ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0"><?php echo e($payment->student->first_name); ?> <?php echo e($payment->student->last_name); ?></h6>
                                            <small class="text-muted"><?php echo e($payment->student->admission_number ?? 'N/A'); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0"><?php echo e($payment->studentFee->feeStructure->fee_name); ?></h6>
                                            <small class="text-muted"><?php echo e($payment->studentFee->feeStructure->schoolClass->name ?? 'N/A'); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">₹<?php echo e(number_format($payment->amount, 2)); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($payment->payment_method)); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo e($payment->getFormattedPaymentDate()); ?></span>
                                        <br><small class="text-muted"><?php echo e($payment->getFormattedPaymentDateWithTime()); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            <?php if($payment->payment_status == 'completed'): ?> bg-success
                                            <?php elseif($payment->payment_status == 'pending'): ?> bg-warning
                                            <?php elseif($payment->payment_status == 'failed'): ?> bg-danger
                                            <?php else: ?> bg-secondary <?php endif; ?>">
                                            <?php echo e(ucfirst($payment->payment_status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($payment->receipt_number): ?>
                                            <span class="text-muted"><?php echo e($payment->receipt_number); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="<?php echo e(route('institution.payments.show', $payment)); ?>">
                                                        <i class="ti ti-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                <?php if($payment->payment_status == 'completed'): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('institution.payments.receipt', $payment)); ?>">
                                                            <i class="ti ti-eye me-2"></i>View Receipt
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('institution.payments.download-receipt', $payment)); ?>">
                                                            <i class="ti ti-download me-2"></i>Download PDF
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($payments->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-credit-card-off" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    <h6 class="text-muted">No payments found</h6>
                    <p class="text-muted">Start by recording a payment or generating bills.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo e(route('institution.payments.create')); ?>" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Record Payment
                        </a>
                        <button class="btn btn-outline-success" onclick="generateBills()">
                            <i class="ti ti-refresh me-1"></i>Generate Bills
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide toasts after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });
});

function generateBills() {
    if (confirm('Are you sure you want to generate monthly bills for all students?')) {
        fetch('<?php echo e(route("institution.payments.generate-bills")); ?>', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error generating bills: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating bills. Please try again.');
        });
    }
}

function printReceipt(paymentId) {
    // Open print receipt in new window
    window.open(`/institution/payment/payments/${paymentId}/receipt`, '_blank');
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/payment/payments/index.blade.php ENDPATH**/ ?>