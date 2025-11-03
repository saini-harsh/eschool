<?php $__env->startSection('title', 'Fee Structure'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Fee Structure</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Fee Structure</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.fee-structure.create')); ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Add Fee Structure
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Fee Structure Cards -->
    <div class="row">
        <?php if($feeStructures->count() > 0): ?>
            <?php $__currentLoopData = $feeStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeStructure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                    <i class="ti ti-receipt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?php echo e($feeStructure->name); ?></h6>
                                    <small class="text-muted"><?php echo e($feeStructure->schoolClass->name ?? 'N/A'); ?></small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                       data-id="<?php echo e($feeStructure->id); ?>" 
                                       <?php echo e($feeStructure->status ? 'checked' : ''); ?>>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($feeStructure->description): ?>
                                <p class="text-muted mb-3"><?php echo e(Str::limit($feeStructure->description, 80)); ?></p>
                            <?php endif; ?>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-school text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Class</small>
                                            <span class="fw-semibold"><?php echo e($feeStructure->schoolClass->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-users text-info me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Section</small>
                                            <span class="fw-semibold"><?php echo e($feeStructure->section->name ?? 'All Sections'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar text-success me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Due Date</small>
                                            <span class="fw-semibold"><?php echo e($feeStructure->due_date->format('d M Y')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-credit-card text-warning me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Fee Type</small>
                                            <span class="badge bg-<?php echo e($feeStructure->fee_type == 'monthly' ? 'primary' : ($feeStructure->fee_type == 'quarterly' ? 'info' : ($feeStructure->fee_type == 'yearly' ? 'success' : 'warning'))); ?>">
                                                <?php echo e($feeStructure->fee_type == 'onetime' ? 'One Time' : ucfirst($feeStructure->fee_type)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <div class="bg-light rounded p-3">
                                    <small class="text-muted d-block">Amount</small>
                                    <h4 class="mb-0 text-primary fw-bold">₹<?php echo e(number_format($feeStructure->amount, 2)); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="<?php echo e(route('institution.payments.record', $feeStructure->id)); ?>" 
                                   class="btn btn-sm btn-success" title="Record Payment">
                                    <i class="ti ti-credit-card me-1"></i>Record Payment
                                </a>
                                <a href="<?php echo e(route('institution.fee-structure.show', $feeStructure->id)); ?>" 
                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="ti ti-eye me-1"></i>View
                                </a>
                                <a href="<?php echo e(route('institution.fee-structure.edit', $feeStructure->id)); ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="ti ti-edit me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                        data-id="<?php echo e($feeStructure->id); ?>" title="Delete">
                                    <i class="ti ti-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-receipt-off" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                            <h5 class="text-muted">No Fee Structures Found</h5>
                            <p class="text-muted">Start by creating your first fee structure.</p>
                            <a href="<?php echo e(route('institution.fee-structure.create')); ?>" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Add Fee Structure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- End Fee Structure Cards -->

</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: `/institution/fee-structure/${id}/status`,
            method: 'POST',
            data: {
                status: status,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error('Failed to update status');
                    // Revert the toggle
                    $('.status-toggle[data-id="' + id + '"]').prop('checked', !status);
                }
            },
            error: function() {
                toastr.error('An error occurred');
                // Revert the toggle
                $('.status-toggle[data-id="' + id + '"]').prop('checked', !status);
            }
        });
    });

    // Delete confirmation
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/institution/fee-structure/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error('Failed to delete fee structure');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred');
                    }
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/payment/fee-structure/index.blade.php ENDPATH**/ ?>