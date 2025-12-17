<?php $__env->startSection('title', 'Payment Details'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Payment Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('institution.salary.payments.index')); ?>">Salary Payments</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <?php if($payment->canProcess()): ?>
                    <button type="button" class="btn btn-success" id="processBtn">
                        <i class="ti ti-send me-1"></i> Process Payment
                    </button>
                <?php endif; ?>
                <a href="<?php echo e(route('institution.salary.payments.index')); ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-8">
                <!-- Payment Info -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Payment Information</h6>
                        <span class="badge <?php echo e($payment->getStatusBadgeClass()); ?> fs-6"><?php echo e(ucfirst($payment->status)); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted">Transaction ID</td>
                                        <td><code><?php echo e($payment->transaction_id); ?></code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Employee</td>
                                        <td>
                                            <strong><?php echo e($payment->payee_name); ?></strong>
                                            <br><small class="text-muted"><?php echo e($payment->payee_type_display); ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Period</td>
                                        <td><?php echo e($payment->period); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Payment Method</td>
                                        <td><?php echo e($payment->payment_method_display); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted">Created At</td>
                                        <td><?php echo e($payment->created_at->format('d M Y, h:i A')); ?></td>
                                    </tr>
                                    <?php if($payment->payment_date): ?>
                                    <tr>
                                        <td class="text-muted">Payment Date</td>
                                        <td><?php echo e($payment->payment_date->format('d M Y')); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($payment->razorpay_payout_id): ?>
                                    <tr>
                                        <td class="text-muted">Razorpay Payout ID</td>
                                        <td><code><?php echo e($payment->razorpay_payout_id); ?></code></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($payment->failure_reason): ?>
                                    <tr>
                                        <td class="text-muted">Failure Reason</td>
                                        <td class="text-danger"><?php echo e($payment->failure_reason); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>

                        <?php if($payment->notes): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <strong>Notes:</strong>
                                <p class="mb-0 mt-1"><?php echo e($payment->notes); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Salary Breakdown -->
                <?php if($payment->salary_breakdown): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Salary Breakdown</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success"><i class="ti ti-plus me-1"></i>Earnings</h6>
                                <?php if(!empty($payment->salary_breakdown['earnings'])): ?>
                                    <?php $__currentLoopData = $payment->salary_breakdown['earnings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $earning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php echo e($earning['name']); ?>

                                                <?php if($earning['is_percentage']): ?>
                                                    <small class="text-muted">(<?php echo e($earning['percentage']); ?>%)</small>
                                                <?php endif; ?>
                                            </span>
                                            <span class="text-success">+₹<?php echo e(number_format($earning['amount'], 2)); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <p class="text-muted">No earnings</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger"><i class="ti ti-minus me-1"></i>Deductions</h6>
                                <?php if(!empty($payment->salary_breakdown['deductions'])): ?>
                                    <?php $__currentLoopData = $payment->salary_breakdown['deductions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deduction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php echo e($deduction['name']); ?>

                                                <?php if($deduction['is_percentage']): ?>
                                                    <small class="text-muted">(<?php echo e($deduction['percentage']); ?>%)</small>
                                                <?php endif; ?>
                                            </span>
                                            <span class="text-danger">-₹<?php echo e(number_format($deduction['amount'], 2)); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <p class="text-muted">No deductions</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Salary Summary -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="fw-bold mb-0">Salary Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Base Salary</span>
                            <strong>₹<?php echo e(number_format($payment->base_salary, 2)); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>+ Total Earnings</span>
                            <strong>₹<?php echo e(number_format($payment->total_earnings, 2)); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-danger">
                            <span>- Total Deductions</span>
                            <strong>₹<?php echo e(number_format($payment->total_deductions, 2)); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fs-5 fw-bold">Net Salary</span>
                            <span class="fs-4 fw-bold text-primary">₹<?php echo e(number_format($payment->net_salary, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <?php $payee = $payment->payee; ?>
                <?php if($payee && $payee->hasBankDetails()): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Bank Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Bank Name</td>
                                <td><?php echo e($payee->bank_name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Branch</td>
                                <td><?php echo e($payee->bank_branch ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account No.</td>
                                <td><?php echo e($payee->bank_account_number ? '****' . substr($payee->bank_account_number, -4) : 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">IFSC Code</td>
                                <td><?php echo e($payee->bank_ifsc_code ?? 'N/A'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="text-center text-warning">
                            <i class="ti ti-alert-triangle fs-1"></i>
                            <p class="mt-2 mb-0">Bank details not configured for this employee</p>
                        </div>
                    </div>
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
                    <p>Are you sure you want to process this salary payment of <strong>₹<?php echo e(number_format($payment->net_salary, 2)); ?></strong>?</p>
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
    $('#processBtn').on('click', function() {
        $('#processModal').modal('show');
    });

    $('#confirmProcess').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

        $.ajax({
            url: '<?php echo e(route("institution.salary.payments.process", $payment->id)); ?>',
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

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/salary/payments/show.blade.php ENDPATH**/ ?>