
<?php $__env->startSection('title', 'Institution | Edit Boarding Student'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Edit Boarding Student</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('institution.boarding.index')); ?>">Boarding</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('institution.boarding.index')); ?>" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('institution.boarding.update', $boarding->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="student_id" name="student_id" required>
                                    <option value="<?php echo e($boarding->student->id); ?>" selected>
                                        <?php echo e($boarding->student->first_name); ?> <?php echo e($boarding->student->middle_name); ?> <?php echo e($boarding->student->last_name); ?>

                                        <?php if($boarding->student->admission_number): ?> - <?php echo e($boarding->student->admission_number); ?> <?php endif; ?>
                                        <?php if($boarding->student->schoolClass): ?> (<?php echo e($boarding->student->schoolClass->name); ?>) <?php endif; ?>
                                    </option>
                                </select>
                                <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Note: Changing the student will replace the current boarding entry.</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="<?php echo e(route('institution.boarding.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="ti ti-x me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Update Boarding
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Student Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php if($boarding->student->photo): ?>
                                <img src="<?php echo e(asset($boarding->student->photo)); ?>" alt="Photo"
                                    class="avatar avatar-xl rounded-circle">
                            <?php else: ?>
                                <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto">
                                    <?php echo e(strtoupper(substr($boarding->student->first_name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <h6 class="text-center mb-3">
                            <?php echo e($boarding->student->first_name); ?> <?php echo e($boarding->student->middle_name); ?> <?php echo e($boarding->student->last_name); ?>

                        </h6>
                        <div class="mb-2">
                            <strong>Admission No.:</strong> <?php echo e($boarding->student->admission_number ?? 'N/A'); ?>

                        </div>
                        <div class="mb-2">
                            <strong>Roll No.:</strong> <?php echo e($boarding->student->roll_number ?? 'N/A'); ?>

                        </div>
                        <div class="mb-2">
                            <strong>Class:</strong> 
                            <?php if($boarding->student->schoolClass): ?>
                                <span class="badge badge-soft-primary"><?php echo e($boarding->student->schoolClass->name); ?></span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                        <div class="mb-2">
                            <strong>Section:</strong> 
                            <?php if($boarding->student->section): ?>
                                <span class="badge badge-soft-info"><?php echo e($boarding->student->section->name); ?></span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> <?php echo e($boarding->student->email ?? 'N/A'); ?>

                        </div>
                        <div class="mb-2">
                            <strong>Phone:</strong> <?php echo e($boarding->student->phone ?? 'N/A'); ?>

                        </div>
                        <div class="mt-3">
                            <a href="<?php echo e(route('institution.students.show', $boarding->student->id)); ?>" class="btn btn-sm btn-outline-primary w-100">
                                <i class="ti ti-eye me-1"></i>View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/boarding/edit.blade.php ENDPATH**/ ?>