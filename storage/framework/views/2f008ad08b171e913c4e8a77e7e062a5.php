
<?php $__env->startSection('title', 'Admin | Manage Permissions - ' . $student->first_name . ' ' . $student->last_name); ?>
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
                <h5 class="fw-bold">Manage Permissions - <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.students.index')); ?>">Students</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Permissions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-primary"><i class="ti ti-arrow-left me-1"></i>Back to List</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Set Permissions for Student</h5>
                        <p class="text-muted mb-0">Select which modules and features this student can access. If no permissions are selected, the student will have access to all features.</p>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.students.permissions.update', $student->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="alert alert-info mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Note:</strong> These permissions control what the student can access in their dashboard.
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Student Permissions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php $__currentLoopData = \App\Helpers\PermissionHelper::getStudentPermissions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="<?php echo e($key); ?>" 
                                                       id="permission_<?php echo e($key); ?>"
                                                       <?php echo e($student->permissions === null || in_array($key, $student->permissions ?? []) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="permission_<?php echo e($key); ?>">
                                                    <?php echo e($label); ?>

                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" id="selectAll">Select All</button>
                                    <button type="button" class="btn btn-outline-secondary" id="deselectAll">Deselect All</button>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-primary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Deselect all checkboxes
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Auto-hide toast after 3 seconds
    setTimeout(function() {
        var toastEl = document.querySelector('.toast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl);
            toast.hide();
        }
    }, 3000);
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/administration/students/permissions.blade.php ENDPATH**/ ?>