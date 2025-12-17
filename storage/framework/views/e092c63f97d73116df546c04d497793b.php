<?php $__env->startSection('title', 'Salary Payments'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Salary Payments</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Salary Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payments</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('institution.salary.payments.bulk')); ?>" class="btn btn-outline-primary">
                    <i class="ti ti-users me-1"></i> Bulk Process
                </a>
                <a href="<?php echo e(route('institution.salary.payments.create')); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> New Payment
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h3 class="mb-0">₹<?php echo e(number_format($stats['total_pending'], 2)); ?></h3>
                                <small class="text-muted"><?php echo e($stats['count_pending']); ?> payments</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="ti ti-clock text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Paid</h6>
                                <h3 class="mb-0">₹<?php echo e(number_format($stats['total_paid'], 2)); ?></h3>
                                <small class="text-muted"><?php echo e($stats['count_paid']); ?> payments</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="ti ti-check text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Failed</h6>
                                <h3 class="mb-0">₹<?php echo e(number_format($stats['total_failed'], 2)); ?></h3>
                                <small class="text-muted"><?php echo e($stats['count_failed']); ?> payments</small>
                            </div>
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <i class="ti ti-x text-danger fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                            <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                            <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Employee Type</label>
                        <select name="payee_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="teacher" <?php echo e(request('payee_type') == 'teacher' ? 'selected' : ''); ?>>Teachers</option>
                            <option value="staff" <?php echo e(request('payee_type') == 'staff' ? 'selected' : ''); ?>>Staff</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-select">
                            <option value="">All Months</option>
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($num); ?>" <?php echo e(request('month') == $num ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select">
                            <option value="">All Years</option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Transaction ID" value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i>
                        </button>
                        <a href="<?php echo e(route('institution.salary.payments.index')); ?>" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-body">
                <?php if($payments->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Base Salary</th>
                                    <th>Net Salary</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <code><?php echo e($payment->transaction_id); ?></code>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($payment->payee_name); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($payment->payee_type_display); ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo e($payment->period); ?></td>
                                        <td>₹<?php echo e(number_format($payment->base_salary, 2)); ?></td>
                                        <td><strong>₹<?php echo e(number_format($payment->net_salary, 2)); ?></strong></td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($payment->payment_method_display); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($payment->getStatusBadgeClass()); ?>">
                                                <?php echo e(ucfirst($payment->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo e(route('institution.salary.payments.show', $payment->id)); ?>" 
                                                    class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if($payment->canProcess()): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success process-btn"
                                                        data-id="<?php echo e($payment->id); ?>" title="Process Payment">
                                                        <i class="ti ti-send"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($payments->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ti ti-receipt fs-1 text-muted"></i>
                        <h5 class="mt-3">No Salary Payments Found</h5>
                        <p class="text-muted">Create salary payments for your employees.</p>
                        <a href="<?php echo e(route('institution.salary.payments.create')); ?>" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Create Payment
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Process Confirmation Modal -->
    <div class="modal fade" id="processModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to process this salary payment?</p>
                    <p class="text-muted small">This will initiate the fund transfer to the employee's bank account.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmProcess">
                        <i class="ti ti-send me-1"></i> Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let processId = null;

    // Process button click
    $('.process-btn').on('click', function() {
        processId = $(this).data('id');
        $('#processModal').modal('show');
    });

    // Confirm process
    $('#confirmProcess').on('click', function() {
        if (!processId) return;
        
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

        $.ajax({
            url: `/institution/salary/payments/${processId}/process`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to process payment');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="ti ti-send me-1"></i> Process Payment');
                $('#processModal').modal('hide');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/salary/payments/index.blade.php ENDPATH**/ ?>