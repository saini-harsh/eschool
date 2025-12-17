<?php $__env->startSection('title', 'Admin | Create Salary Structure'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Create Salary Structure</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.salary.structures.index')); ?>">Salary Structures</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="<?php echo e(route('admin.salary.structures.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Institution <span class="text-danger">*</span></label>
                                <select name="institution_id" class="form-select <?php $__errorArgs = ['institution_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Select Institution</option>
                                    <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($institution->id); ?>" <?php echo e(old('institution_id') == $institution->id ? 'selected' : ''); ?>>
                                            <?php echo e($institution->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['institution_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Structure Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('name')); ?>" placeholder="e.g., Standard Salary Structure" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Optional description"><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo e(old('status', true) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                </div>

                <!-- Salary Components -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Salary Components</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="addComponent">
                            <i class="ti ti-plus me-1"></i> Add Component
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="componentsContainer">
                            <!-- Components will be added here -->
                        </div>
                        <div id="noComponents" class="text-center text-muted py-4">
                            <i class="ti ti-list-details fs-1"></i>
                            <p class="mt-2 mb-0">No components added yet. Click "Add Component" to add earnings or deductions.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Salary Preview</h6>
                        <small class="text-muted">Based on ₹10,000 base salary</small>
                    </div>
                    <div class="card-body" id="salaryPreview">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Base Salary:</span>
                            <strong>₹10,000</strong>
                        </div>
                        <hr>
                        <div id="previewEarnings"></div>
                        <div id="previewDeductions"></div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Net Salary:</strong>
                            <strong class="text-primary" id="previewNetSalary">₹10,000</strong>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="ti ti-check me-1"></i> Create Structure
                        </button>
                        <a href="<?php echo e(route('admin.salary.structures.index')); ?>" class="btn btn-outline-secondary w-100">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Component Template -->
<template id="componentTemplate">
    <div class="component-row border rounded p-3 mb-3">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Name</label>
                <input type="text" name="components[INDEX][name]" class="form-control component-name" placeholder="e.g., HRA, PF" required>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Type</label>
                <select name="components[INDEX][type]" class="form-select component-type" required>
                    <option value="earning">Earning (+)</option>
                    <option value="deduction">Deduction (-)</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Amount/Rate</label>
                <input type="number" name="components[INDEX][amount]" class="form-control component-amount" step="0.01" min="0" required>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger remove-component w-100">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
        <div class="form-check mt-2">
            <input type="checkbox" name="components[INDEX][is_percentage]" class="form-check-input component-percentage">
            <label class="form-check-label">Percentage of base salary</label>
        </div>
    </div>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let componentIndex = 0;

    function updatePreview() {
        const baseSalary = 10000;
        let totalEarnings = 0;
        let totalDeductions = 0;
        let earningsHtml = '';
        let deductionsHtml = '';

        $('.component-row').each(function() {
            const name = $(this).find('.component-name').val() || 'Component';
            const type = $(this).find('.component-type').val();
            const amount = parseFloat($(this).find('.component-amount').val()) || 0;
            const isPercentage = $(this).find('.component-percentage').is(':checked');

            const calculatedAmount = isPercentage ? (baseSalary * amount / 100) : amount;

            if (type === 'earning') {
                totalEarnings += calculatedAmount;
                earningsHtml += `<div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">${name}${isPercentage ? ' (' + amount + '%)' : ''}</span>
                    <span class="text-success">+₹${calculatedAmount.toLocaleString()}</span>
                </div>`;
            } else {
                totalDeductions += calculatedAmount;
                deductionsHtml += `<div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">${name}${isPercentage ? ' (' + amount + '%)' : ''}</span>
                    <span class="text-danger">-₹${calculatedAmount.toLocaleString()}</span>
                </div>`;
            }
        });

        if (earningsHtml) {
            $('#previewEarnings').html('<h6 class="text-success small mb-2">Earnings</h6>' + earningsHtml + '<hr>');
        } else {
            $('#previewEarnings').html('');
        }

        if (deductionsHtml) {
            $('#previewDeductions').html('<h6 class="text-danger small mb-2">Deductions</h6>' + deductionsHtml + '<hr>');
        } else {
            $('#previewDeductions').html('');
        }

        const netSalary = baseSalary + totalEarnings - totalDeductions;
        $('#previewNetSalary').text('₹' + netSalary.toLocaleString());
    }

    function toggleNoComponents() {
        if ($('.component-row').length > 0) {
            $('#noComponents').hide();
        } else {
            $('#noComponents').show();
        }
    }

    $('#addComponent').on('click', function() {
        const template = $('#componentTemplate').html().replace(/INDEX/g, componentIndex);
        $('#componentsContainer').append(template);
        componentIndex++;
        toggleNoComponents();
        updatePreview();
    });

    $(document).on('click', '.remove-component', function() {
        $(this).closest('.component-row').remove();
        toggleNoComponents();
        updatePreview();
    });

    $(document).on('input change', '.component-name, .component-type, .component-amount, .component-percentage', function() {
        updatePreview();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/salary/structures/create.blade.php ENDPATH**/ ?>