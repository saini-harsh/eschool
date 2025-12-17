<?php $__env->startSection('title', 'Admin | Salary Payments'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Salary Payments</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Salary Payments</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('admin.salary.payments.export', request()->all())); ?>" class="btn btn-outline-success">
                <i class="ti ti-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-check fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Total Paid</p>
                            <h4 class="mb-0 text-white">₹<?php echo e(number_format($stats['total_paid'], 2)); ?></h4>
                            <small class="text-white-50"><?php echo e($stats['count_paid']); ?> payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-clock fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Pending</p>
                            <h4 class="mb-0 text-white">₹<?php echo e(number_format($stats['total_pending'], 2)); ?></h4>
                            <small class="text-white-50"><?php echo e($stats['count_pending']); ?> payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-loader fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Processing</p>
                            <h4 class="mb-0 text-white">₹<?php echo e(number_format($stats['total_processing'], 2)); ?></h4>
                            <small class="text-white-50"><?php echo e($stats['count_processing']); ?> payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 me-3">
                            <i class="ti ti-x fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white-50">Failed</p>
                            <h4 class="mb-0 text-white">₹<?php echo e(number_format($stats['total_failed'], 2)); ?></h4>
                            <small class="text-white-50"><?php echo e($stats['count_failed']); ?> payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.salary.payments.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Institution</label>
                    <select name="institution_id" class="form-select" id="institutionFilter">
                        <option value="">All Institutions</option>
                        <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($institution->id); ?>" <?php echo e(request('institution_id') == $institution->id ? 'selected' : ''); ?>>
                                <?php echo e($institution->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                        <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Employee Type</label>
                    <select name="payee_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="teacher" <?php echo e(request('payee_type') == 'teacher' ? 'selected' : ''); ?>>Teacher</option>
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
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-1"><i class="ti ti-filter"></i></button>
                    <a href="<?php echo e(route('admin.salary.payments.index')); ?>" class="btn btn-outline-secondary"><i class="ti ti-x"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Institution Quick Stats (shows when institution is selected) -->
    <div id="institutionStats" class="card mb-4" style="display: none;">
        <div class="card-header">
            <h6 class="fw-bold mb-0"><i class="ti ti-building me-2"></i>Institution Details</h6>
        </div>
        <div class="card-body">
            <div class="row" id="institutionStatsContent">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-bold mb-0">All Salary Payments</h6>
        </div>
        <div class="card-body p-0">
            <?php if($payments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Institution</th>
                                <th>Employee</th>
                                <th>Period</th>
                                <th>Net Salary</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Payment Date</th>
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
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?php echo e($payment->institution->name ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo e($payment->payeeName); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e(ucfirst($payment->payee_type)); ?></small>
                                    </td>
                                    <td><?php echo e($months[$payment->month] ?? ''); ?> <?php echo e($payment->year); ?></td>
                                    <td><strong>₹<?php echo e(number_format($payment->net_salary, 2)); ?></strong></td>
                                    <td>
                                        <small>
                                            <?php if($payment->payment_method == 'razorpay'): ?>
                                                <i class="ti ti-building-bank me-1"></i>RazorpayX
                                            <?php elseif($payment->payment_method == 'bank_transfer'): ?>
                                                <i class="ti ti-building-bank me-1"></i>Bank
                                            <?php elseif($payment->payment_method == 'cash'): ?>
                                                <i class="ti ti-cash me-1"></i>Cash
                                            <?php elseif($payment->payment_method == 'cheque'): ?>
                                                <i class="ti ti-file-text me-1"></i>Cheque
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if($payment->status == 'paid'): ?>
                                            <span class="badge bg-success"><i class="ti ti-check me-1"></i>Paid</span>
                                        <?php elseif($payment->status == 'pending'): ?>
                                            <span class="badge bg-warning"><i class="ti ti-clock me-1"></i>Pending</span>
                                        <?php elseif($payment->status == 'processing'): ?>
                                            <span class="badge bg-info"><i class="ti ti-loader me-1"></i>Processing</span>
                                        <?php elseif($payment->status == 'failed'): ?>
                                            <span class="badge bg-danger"><i class="ti ti-x me-1"></i>Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($payment->payment_date): ?>
                                            <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('d M Y')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.salary.payments.show', $payment->id)); ?>" 
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <?php if(in_array($payment->status, ['pending', 'failed'])): ?>
                                                <button type="button" class="btn btn-outline-success process-btn" 
                                                        data-id="<?php echo e($payment->id); ?>" title="Process Payment">
                                                    <i class="ti ti-send"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-secondary status-btn" 
                                                    data-id="<?php echo e($payment->id); ?>" 
                                                    data-status="<?php echo e($payment->status); ?>"
                                                    title="Update Status">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    <?php echo e($payments->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="ti ti-receipt-off fs-1 text-muted"></i>
                    <p class="mt-3 mb-0 text-muted">No salary payments found</p>
                    <?php if(request()->hasAny(['institution_id', 'status', 'payee_type', 'month', 'year'])): ?>
                        <a href="<?php echo e(route('admin.salary.payments.index')); ?>" class="btn btn-outline-primary mt-3">Clear Filters</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Payment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="modalStatus" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="mb-3" id="failureReasonGroup" style="display: none;">
                    <label class="form-label">Failure Reason</label>
                    <textarea id="modalFailureReason" class="form-control" rows="2" placeholder="Enter reason for failure"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let currentPaymentId = null;

    // Institution filter change - load stats
    $('#institutionFilter').on('change', function() {
        const institutionId = $(this).val();
        if (institutionId) {
            $.get('<?php echo e(url("admin/salary/payments/institution-stats")); ?>/' + institutionId, function(data) {
                let html = `
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1">${data.total_teachers}</h4>
                            <small class="text-muted">Teachers</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1">${data.total_staff}</h4>
                            <small class="text-muted">Staff</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1">${data.salary_structures}</h4>
                            <small class="text-muted">Structures</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1 ${data.razorpay_configured ? 'text-success' : 'text-danger'}">
                                ${data.razorpay_configured ? '✓' : '✗'}
                            </h4>
                            <small class="text-muted">Razorpay</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1 text-success">₹${data.total_paid_this_month.toLocaleString()}</h4>
                            <small class="text-muted">Paid This Month</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <h4 class="mb-1 text-warning">${data.pending_this_month}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                `;
                $('#institutionStatsContent').html(html);
                $('#institutionStats').show();
            });
        } else {
            $('#institutionStats').hide();
        }
    });

    // Process payment
    $('.process-btn').on('click', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        if (confirm('Are you sure you want to process this payment?')) {
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            
            $.ajax({
                url: '<?php echo e(url("admin/salary/payments")); ?>/' + id + '/process',
                type: 'POST',
                data: { _token: '<?php echo e(csrf_token()); ?>' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('<i class="ti ti-send"></i>');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to process payment');
                    btn.prop('disabled', false).html('<i class="ti ti-send"></i>');
                }
            });
        }
    });

    // Status modal
    $('.status-btn').on('click', function() {
        currentPaymentId = $(this).data('id');
        $('#modalStatus').val($(this).data('status'));
        $('#failureReasonGroup').hide();
        $('#statusModal').modal('show');
    });

    $('#modalStatus').on('change', function() {
        if ($(this).val() === 'failed') {
            $('#failureReasonGroup').show();
        } else {
            $('#failureReasonGroup').hide();
        }
    });

    $('#saveStatusBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: '<?php echo e(url("admin/salary/payments")); ?>/' + currentPaymentId + '/status',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                status: $('#modalStatus').val(),
                failure_reason: $('#modalFailureReason').val()
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#statusModal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).text('Save Changes');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to update status');
                btn.prop('disabled', false).text('Save Changes');
            }
        });
    });

    // Trigger stats load if institution is pre-selected
    if ($('#institutionFilter').val()) {
        $('#institutionFilter').trigger('change');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/salary/payments/index.blade.php ENDPATH**/ ?>