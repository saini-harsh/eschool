
<?php $__env->startSection('title', 'Create Admission Fee'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Create Admission Fee</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('institution.admission-fee.index')); ?>">Admission Fee</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.admission-fee.index')); ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Create Admission Fee Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Admission Fee Information</h5>
                </div>
                <div class="card-body">
                    <form id="admissionFeeForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Admission Fee Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">Select Section (Optional)</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="effective_from" class="form-label">Effective From <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpickr" id="effective_from" name="effective_from" placeholder="Select date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="effective_until" class="form-label">Effective Until</label>
                                    <input type="text" class="form-control flatpickr" id="effective_until" name="effective_until" placeholder="Select date (optional)">
                                    <small class="form-text text-muted">Leave empty for ongoing admission fee</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter admission fee description (optional)"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_mandatory" name="is_mandatory" checked>
                                <label class="form-check-label" for="is_mandatory">
                                    This admission fee is mandatory
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Create Admission Fee
                            </button>
                            <a href="<?php echo e(route('institution.admission-fee.index')); ?>" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Create Admission Fee Form -->

</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize Flatpickr for date inputs
    flatpickr("#effective_from", {
        dateFormat: "Y-m-d",
        allowInput: true,
        placeholder: "Select effective from date"
    });

    flatpickr("#effective_until", {
        dateFormat: "Y-m-d",
        allowInput: true,
        placeholder: "Select effective until date (optional)"
    });

    // Load sections when class is selected
    $('#class_id').change(function() {
        const classId = $(this).val();
        const sectionSelect = $('#section_id');
        
        sectionSelect.html('<option value="">Loading sections...</option>');
        
        if (classId) {
            $.ajax({
                url: `/institution/admission-fee/sections/${classId}`,
                method: 'GET',
                success: function(response) {
                    sectionSelect.html('<option value="">Select Section (Optional)</option>');
                    response.forEach(function(section) {
                        sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                },
                error: function() {
                    sectionSelect.html('<option value="">Error loading sections</option>');
                }
            });
        } else {
            sectionSelect.html('<option value="">Select Section (Optional)</option>');
        }
    });

    // Form submission
    $('#admissionFeeForm').submit(function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?php echo e(route("institution.admission-fee.store")); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = '<?php echo e(route("institution.admission-fee.index")); ?>';
                } else {
                    toastr.error(response.message || 'Failed to create admission fee');
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
                    toastr.error('An error occurred while creating the admission fee');
                }
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/payment/admission-fee/create.blade.php ENDPATH**/ ?>