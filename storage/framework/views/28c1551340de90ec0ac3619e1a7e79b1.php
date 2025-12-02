<?php $__env->startSection('title', 'Institution | Exam Management | Edit Room'); ?>

<?php $__env->startPush('head'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Edit Exam Room</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('institution.dashboard')); ?>">
                                <i class="ti ti-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('institution.exam-management.rooms.index')); ?>">Exam Rooms</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Edit Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit Exam Room Details</h6>
                    </div>
                    <div class="card-body">
                        <form id="editRoomForm" method="POST"
                            action="<?php echo e(route('institution.exam-management.rooms.update', $classRoom->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('POST'); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_no" class="form-label">Room Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['room_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="room_no" name="room_no" value="<?php echo e(old('room_no', $classRoom->room_no)); ?>"
                                            required>
                                        <?php $__errorArgs = ['room_no'];
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_name" class="form-label">Room Name</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['room_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="room_name" name="room_name"
                                            value="<?php echo e(old('room_name', $classRoom->room_name)); ?>">
                                        <?php $__errorArgs = ['room_name'];
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label">Capacity <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="capacity" name="capacity"
                                            value="<?php echo e(old('capacity', $classRoom->capacity)); ?>" min="1" required>
                                        <?php $__errorArgs = ['capacity'];
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="students_per_bench" class="form-label">Students per Bench</label>
                                        <select class="form-control <?php $__errorArgs = ['students_per_bench'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="students_per_bench" name="students_per_bench">
                                            <option value="1"
                                                <?php echo e(old('students_per_bench', $classRoom->students_per_bench ?? 1) == 1 ? 'selected' : ''); ?>>
                                                1 Student</option>
                                            <option value="2"
                                                <?php echo e(old('students_per_bench', $classRoom->students_per_bench ?? 1) == 2 ? 'selected' : ''); ?>>
                                                2 Students</option>
                                            <option value="3"
                                                <?php echo e(old('students_per_bench', $classRoom->students_per_bench ?? 1) == 3 ? 'selected' : ''); ?>>
                                                3 Students</option>
                                            <option value="4"
                                                <?php echo e(old('students_per_bench', $classRoom->students_per_bench ?? 1) == 4 ? 'selected' : ''); ?>>
                                                4 Students</option>
                                        </select>
                                        <?php $__errorArgs = ['students_per_bench'];
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
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status"
                                    name="status" required>
                                    <option value="1" <?php echo e(old('status', $classRoom->status) == 1 ? 'selected' : ''); ?>>
                                        Active</option>
                                    <option value="0" <?php echo e(old('status', $classRoom->status) == 0 ? 'selected' : ''); ?>>
                                        Inactive</option>
                                </select>
                                <?php $__errorArgs = ['status'];
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

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('institution.exam-management.rooms.index')); ?>"
                                    class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Room</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submission
            document.getElementById('editRoomForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Updating...';

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showToast('success', data.success);

                            // Redirect after a short delay
                            setTimeout(() => {
                                window.location.href =
                                    '<?php echo e(route('institution.exam-management.rooms.index')); ?>';
                            }, 1500);
                        } else {
                            showToast('error', data.message || 'An error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while updating the room');
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    });
            });
        });

        function showToast(type, message) {
            // Create toast element
            const toastHtml = `
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                    <div class="toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing toasts
            document.querySelectorAll('.toast').forEach(toast => toast.remove());

            // Add new toast
            document.body.insertAdjacentHTML('beforeend', toastHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.toast').forEach(toast => toast.remove());
            }, 5000);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/examination/room-edit.blade.php ENDPATH**/ ?>