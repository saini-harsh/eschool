<?php $__env->startSection('title', 'Record Payment'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Record Payment</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('institution.fee-structure.index')); ?>">Fee Structure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Record Payment</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.fee-structure.index')); ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Fee Structure
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Fee Structure Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1 fw-semibold"><?php echo e($feeStructure->name); ?></h6>
                            <p class="text-muted mb-0">
                                <i class="ti ti-school me-1"></i><?php echo e($feeStructure->schoolClass->name ?? 'N/A'); ?>

                                <?php if($feeStructure->section): ?>
                                    | <i class="ti ti-users me-1"></i><?php echo e($feeStructure->section->name); ?>

                                <?php endif; ?>
                                | <i class="ti ti-credit-card me-1"></i>
                                <span class="badge bg-<?php echo e($feeStructure->fee_type == 'monthly' ? 'primary' : ($feeStructure->fee_type == 'quarterly' ? 'info' : ($feeStructure->fee_type == 'yearly' ? 'success' : 'warning'))); ?>">
                                    <?php echo e($feeStructure->fee_type == 'onetime' ? 'One Time' : ucfirst($feeStructure->fee_type)); ?>

                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <h4 class="mb-0 text-primary fw-bold">₹<?php echo e(number_format($feeStructure->amount, 2)); ?></h4>
                            <small class="text-muted">Fee Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="fee_structure_id" value="<?php echo e($feeStructure->id); ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e($feeStructure->class_id == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-select" id="student_id" name="student_id" required>
                                        <option value="">Select Student</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" min="0" value="<?php echo e($feeStructure->amount); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpickr" id="payment_date" name="payment_date" 
                                           value="<?php echo e(date('Y-m-d')); ?>" placeholder="Select payment date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="online">Online Payment</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label">Transaction ID/Reference</label>
                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                           placeholder="Enter transaction ID (optional)">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Enter any additional notes (optional)"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-credit-card me-1"></i>Record Payment
                            </button>
                            <a href="<?php echo e(route('institution.fee-structure.index')); ?>" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Payment Form -->

</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize Flatpickr for date input
    flatpickr("#payment_date", {
        dateFormat: "Y-m-d",
        allowInput: true,
        placeholder: "Select payment date"
    });

    // Load students when class is selected
    $('#class_id').change(function() {
        const classId = $(this).val();
        const studentSelect = $('#student_id');
        
        studentSelect.html('<option value="">Loading students...</option>');
        
        if (classId) {
            $.ajax({
                url: `/institution/payments/students/${classId}`,
                method: 'GET',
                success: function(response) {
                    studentSelect.html('<option value="">Select Student</option>');
                    response.forEach(function(student) {
                        studentSelect.append(`<option value="${student.id}">${student.first_name} ${student.last_name} (${student.admission_number})</option>`);
                    });
                },
                error: function() {
                    studentSelect.html('<option value="">Error loading students</option>');
                }
            });
        } else {
            studentSelect.html('<option value="">Select Student</option>');
        }
    });

    // Load students for the pre-selected class
    const selectedClassId = $('#class_id').val();
    if (selectedClassId) {
        $('#class_id').trigger('change');
    }

    // Form submission
    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?php echo e(route("institution.payments.store")); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = response.redirect_url;
                } else {
                    toastr.error(response.message || 'Failed to record payment');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const field = $(`[name="${key}"]`);
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while recording the payment');
                }
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/payment/payments/create.blade.php ENDPATH**/ ?>