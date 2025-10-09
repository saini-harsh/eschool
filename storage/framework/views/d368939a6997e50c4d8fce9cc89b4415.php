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

    <!-- Fee Structure List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fee Structure List</h5>
                </div>
                <div class="card-body">
                    <?php if($feeStructures->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Amount</th>
                                        <th>Fee Type</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $feeStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeStructure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($feeStructure->name); ?></h6>
                                                    <?php if($feeStructure->description): ?>
                                                        <small class="text-muted"><?php echo e(Str::limit($feeStructure->description, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo e($feeStructure->schoolClass->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($feeStructure->section->name ?? 'All Sections'); ?></td>
                                            <td>
                                                <span class="fw-semibold">₹<?php echo e(number_format($feeStructure->amount, 2)); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($feeStructure->fee_type == 'monthly' ? 'primary' : ($feeStructure->fee_type == 'quarterly' ? 'info' : 'success')); ?>">
                                                    <?php echo e(ucfirst($feeStructure->fee_type)); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($feeStructure->start_date->format('d M Y')); ?></td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox" 
                                                           data-id="<?php echo e($feeStructure->id); ?>" 
                                                           <?php echo e($feeStructure->status ? 'checked' : ''); ?>>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?php echo e(route('institution.fee-structure.show', $feeStructure->id)); ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('institution.fee-structure.edit', $feeStructure->id)); ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                            data-id="<?php echo e($feeStructure->id); ?>" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Fee Structure List -->

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
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/payment/fee-structure/index.blade.php ENDPATH**/ ?>