<?php $__env->startSection('title', 'Create Salary Payment'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Create Salary Payment</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('institution.salary.payments.index')); ?>">Salary Payments</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <form id="paymentForm">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Payment Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                                    <select name="payee_type" id="payeeType" class="form-select" required>
                                        <option value="">Select Type</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="staff">Non-Working Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                                    <select name="payee_id" id="payeeId" class="form-select" required disabled>
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Month <span class="text-danger">*</span></label>
                                    <select name="month" class="form-select" required>
                                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($num); ?>" <?php echo e(date('n') == $num ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Year <span class="text-danger">*</span></label>
                                    <select name="year" class="form-select" required>
                                        <?php for($y = date('Y') - 1; $y <= date('Y') + 1; $y++): ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e(date('Y') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="razorpay">RazorpayX (Bank Transfer)</option>
                                    <option value="bank_transfer">Manual Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <!-- Salary Preview -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Salary Breakdown</h6>
                        </div>
                        <div class="card-body" id="salaryPreview">
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-user-search fs-1"></i>
                                <p class="mt-2 mb-0">Select an employee to view salary breakdown</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Info -->
                    <div class="card" id="employeeInfoCard" style="display: none;">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Employee Information</h6>
                        </div>
                        <div class="card-body">
                            <div id="employeeInfo"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    <i class="ti ti-check me-1"></i> Create Payment
                </button>
                <a href="<?php echo e(route('institution.salary.payments.index')); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    const teachers = <?php echo json_encode($teachers, 15, 512) ?>;
    const staff = <?php echo json_encode($staff, 15, 512) ?>;

    // Payee type change
    $('#payeeType').on('change', function() {
        const type = $(this).val();
        const $payeeSelect = $('#payeeId');
        
        $payeeSelect.html('<option value="">Select Employee</option>');
        
        if (type === 'teacher') {
            if (teachers.length === 0) {
                $payeeSelect.append('<option value="" disabled>No teachers found</option>');
            }
            teachers.forEach(t => {
                const salary = t.salary ? parseFloat(t.salary) : 0;
                const salaryText = salary > 0 ? `₹${salary.toLocaleString()}` : '(No salary set)';
                $payeeSelect.append(`<option value="${t.id}" data-salary="${salary}" data-bank="${t.bank_account_number ? 'yes' : 'no'}">${t.first_name} ${t.last_name} - ${salaryText}</option>`);
            });
            $payeeSelect.prop('disabled', false);
        } else if (type === 'staff') {
            if (staff.length === 0) {
                $payeeSelect.append('<option value="" disabled>No staff found</option>');
            }
            staff.forEach(s => {
                const salary = s.salary ? parseFloat(s.salary) : 0;
                const salaryText = salary > 0 ? `₹${salary.toLocaleString()}` : '(No salary set)';
                $payeeSelect.append(`<option value="${s.id}" data-salary="${salary}" data-bank="${s.bank_account_number ? 'yes' : 'no'}">${s.first_name} ${s.last_name} - ${salaryText}</option>`);
            });
            $payeeSelect.prop('disabled', false);
        } else {
            $payeeSelect.prop('disabled', true);
        }

        $('#salaryPreview').html(`
            <div class="text-center text-muted py-4">
                <i class="ti ti-user-search fs-1"></i>
                <p class="mt-2 mb-0">Select an employee to view salary breakdown</p>
            </div>
        `);
        $('#employeeInfoCard').hide();
        $('#submitBtn').prop('disabled', true);
    });

    // Payee select change
    $('#payeeId').on('change', function() {
        const payeeId = $(this).val();
        const payeeType = $('#payeeType').val();
        
        if (!payeeId) {
            $('#salaryPreview').html(`
                <div class="text-center text-muted py-4">
                    <i class="ti ti-user-search fs-1"></i>
                    <p class="mt-2 mb-0">Select an employee to view salary breakdown</p>
                </div>
            `);
            $('#employeeInfoCard').hide();
            $('#submitBtn').prop('disabled', true);
            return;
        }

        // Fetch salary preview
        $.ajax({
            url: '<?php echo e(route("institution.salary.payments.preview")); ?>',
            type: 'GET',
            data: {
                payee_type: payeeType,
                payee_id: payeeId
            },
            success: function(response) {
                if (response.success) {
                    const b = response.breakdown;
                    let html = `
                        <div class="d-flex justify-content-between mb-2">
                            <span>Base Salary:</span>
                            <strong>₹${parseFloat(b.base_salary).toLocaleString()}</strong>
                        </div>
                    `;

                    if (b.earnings && b.earnings.length > 0) {
                        html += '<hr><h6 class="text-success small mb-2">Earnings</h6>';
                        b.earnings.forEach(e => {
                            html += `
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">${e.name}${e.is_percentage ? ' (' + e.percentage + '%)' : ''}</span>
                                    <span class="text-success">+₹${parseFloat(e.amount).toLocaleString()}</span>
                                </div>
                            `;
                        });
                    }

                    if (b.deductions && b.deductions.length > 0) {
                        html += '<hr><h6 class="text-danger small mb-2">Deductions</h6>';
                        b.deductions.forEach(d => {
                            html += `
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">${d.name}${d.is_percentage ? ' (' + d.percentage + '%)' : ''}</span>
                                    <span class="text-danger">-₹${parseFloat(d.amount).toLocaleString()}</span>
                                </div>
                            `;
                        });
                    }

                    html += `
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Net Salary:</span>
                            <strong class="text-primary fs-5">₹${parseFloat(b.net_salary).toLocaleString()}</strong>
                        </div>
                    `;

                    $('#salaryPreview').html(html);

                    // Bank details warning
                    let infoHtml = '';
                    if (!response.has_bank_details) {
                        infoHtml = `
                            <div class="alert alert-warning mb-0">
                                <i class="ti ti-alert-triangle me-1"></i>
                                <strong>Warning:</strong> Bank details not configured for this employee. 
                                RazorpayX payment will not work.
                            </div>
                        `;
                    } else {
                        infoHtml = `
                            <div class="alert alert-success mb-0">
                                <i class="ti ti-check me-1"></i>
                                Bank details are configured. Ready for payment processing.
                            </div>
                        `;
                    }
                    $('#employeeInfo').html(infoHtml);
                    $('#employeeInfoCard').show();

                    $('#submitBtn').prop('disabled', false);
                }
            },
            error: function() {
                toastr.error('Failed to fetch salary preview');
            }
        });
    });

    // Form submission
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');

        $.ajax({
            url: '<?php echo e(route("institution.salary.payments.store")); ?>',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = response.redirect_url;
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Payment');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Failed to create payment');
                }
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Payment');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/salary/payments/create.blade.php ENDPATH**/ ?>