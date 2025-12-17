<?php $__env->startSection('title', 'Institution | Edit Teacher'); ?>
<?php $__env->startSection('content'); ?>

<div class="content">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div>
                <h6 class="mb-3 fs-14">
                    <a href="<?php echo e(route('institution.teachers.index')); ?>"><i class="ti ti-arrow-left me-1"></i>Back to Teachers</a>
                </h6>
                <!-- <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?> -->
                <form action="<?php echo e(route('institution.teachers.update', $teacher->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="card rounded-0">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Edit Teacher Details</h6>
                        </div>
                        
                        <div class="card-body">
                            <!-- Image Upload -->
                            <div>
                                <label class="form-label">Image</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed position-relative me-3 flex-shrink-0 p-2">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo e(asset($teacher->profile_image ?? 'admin/img/employees/employee-01.jpg')); ?>" class="img-fluid" alt="User Img">
                                        </div>
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <a href="javascript:void(0);" class="btn btn-soft-danger rounded-pill avatar-badge border-0 fs-12" onclick="document.getElementById('profile_image').value = null; this.closest('.avatar').querySelector('img').src = '<?php echo e(asset('/admin/img/employees/employee-01.jpg')); ?>';">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i>Change Image
                                            <input type="file" name="profile_image" id="profile_image" class="form-control image-sign" accept="image/png, image/jpeg">
                                        </div>
                                        <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                    </div>
                                </div>
                            </div>


                            <!-- Form Fields -->
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="<?php echo e(old('first_name', $teacher->first_name)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name', $teacher->middle_name)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name', $teacher->last_name)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $teacher->email)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $teacher->phone)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                            data-date-format="Y-m-d"
                                            value="<?php echo e(\Carbon\Carbon::parse($teacher->dob)->format('Y-m-d')); ?>">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $teacher->address)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" value="<?php echo e(old('pincode', $teacher->pincode)); ?>">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="select">
                                        <option value="">Select</option>
                                        <option value="Male" <?php echo e($teacher->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="Female" <?php echo e($teacher->gender == 'Female' ? 'selected' : ''); ?>>Female</option>
                                        <option value="Other" <?php echo e($teacher->gender == 'Other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Caste / Tribe</label>
                                    <input type="text" name="caste_tribe" class="form-control" value="<?php echo e(old('caste_tribe', $teacher->caste_tribe)); ?>">
                                </div></div>

                                <!-- Institution is automatically set based on logged-in user -->
                                <input type="hidden" name="institution_id" value="<?php echo e($institution->id); ?>">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep unchanged">
                                </div></div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="select">
                                        <option value="1" <?php echo e($teacher->status == 1 ? 'selected' : ''); ?>>Active</option>
                                        <option value="0" <?php echo e($teacher->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                    </select>
                                </div></div>

                            </div> <!-- end row -->

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="<?php echo e(route('institution.teachers.index')); ?>" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/teachers.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/administration/teachers/edit.blade.php ENDPATH**/ ?>