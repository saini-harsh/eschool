<?php $__env->startSection('title', 'Institution | Non-Working Staff Management'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo e(session('success')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Non-Working Staff</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="<?php echo e(route('institution.nonworkingstaff.index')); ?>"><i class="ti ti-home me-1"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Non-Working Staff</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.nonworkingstaff.create')); ?>" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i> New Staff
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="table-responsive">
        <table class="table table-nowrap datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Institution</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($staff) && $staff->count() > 0): ?>
                    <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-sm avatar-rounded">
                                        <img src="<?php echo e(asset($member->profile_image ?? 'admin/img/default.png')); ?>" alt="img">
                                    </a>
                                    <div class="ms-2">
                                        <h6 class="fs-14 mb-0">
                                            <?php echo e($member->first_name); ?> <?php echo e($member->last_name); ?>

                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($member->email); ?></td>
                            <td><?php echo e($member->phone); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($member->institution_code); ?></span>
                            </td>
                            <td><?php echo e($member->designation); ?></td>
                            <td>
                                <div>
                                    <select class="form-select form-select-sm status-select" data-staff-id="<?php echo e($member->id); ?>">
                                        <option value="1" <?php echo e($member->status == 1 ? 'selected' : ''); ?>>Active</option>
                                        <option value="0" <?php echo e($member->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center">
                                    <a href="<?php echo e(route('institution.nonworkingstaff.edit', $member->id)); ?>" 
                                        class="btn btn-icon btn-sm btn-outline-white border-0">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" 
                                        onclick="confirmDelete(`<?php echo e(route('institution.nonworkingstaff.delete', $member->id)); ?>`)" 
                                        class="btn btn-icon btn-sm btn-outline-white border-0" 
                                        data-bs-toggle="modal" data-bs-target="#delete_modal">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No Non-Working Staff Found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000);
</script>
<!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/nonworkingstaff.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/administration/nonworkingstaff/index.blade.php ENDPATH**/ ?>