<?php $__env->startSection('title', 'Admission Fee'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Admission Fee</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Admission Fee</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.admission-fee.create')); ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Add Admission Fee
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Admission Fee List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Admission Fee List</h5>
                </div>
                <div class="card-body">
                    <?php if($admissionFees->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Amount</th>
                                        <th>Effective Period</th>
                                        <th>Mandatory</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $admissionFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admissionFee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($admissionFee->name); ?></h6>
                                                    <?php if($admissionFee->description): ?>
                                                        <small class="text-muted"><?php echo e(Str::limit($admissionFee->description, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo e($admissionFee->schoolClass->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($admissionFee->section->name ?? 'All Sections'); ?></td>
                                            <td>
                                                <span class="fw-semibold">₹<?php echo e(number_format($admissionFee->amount, 2)); ?></span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">From:</small> <?php echo e($admissionFee->effective_from->format('d M Y')); ?>

                                                    <?php if($admissionFee->effective_until): ?>
                                                        <br><small class="text-muted">Until:</small> <?php echo e($admissionFee->effective_until->format('d M Y')); ?>

                                                    <?php else: ?>
                                                        <br><small class="text-success">Ongoing</small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($admissionFee->is_mandatory): ?>
                                                    <span class="badge bg-danger">Mandatory</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Optional</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox" 
                                                           data-id="<?php echo e($admissionFee->id); ?>" 
                                                           <?php echo e($admissionFee->status ? 'checked' : ''); ?>>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?php echo e(route('institution.admission-fee.show', $admissionFee->id)); ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('institution.admission-fee.edit', $admissionFee->id)); ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                            data-id="<?php echo e($admissionFee->id); ?>" title="Delete">
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
                            <h5 class="text-muted">No Admission Fees Found</h5>
                            <p class="text-muted">Start by creating your first admission fee.</p>
                            <a href="<?php echo e(route('institution.admission-fee.create')); ?>" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Add Admission Fee
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Admission Fee List -->

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
            url: `/institution/admission-fee/${id}/status`,
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
                    url: `/institution/admission-fee/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error('Failed to delete admission fee');
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

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/payment/admission-fee/index.blade.php ENDPATH**/ ?>