<?php $__env->startSection('title', 'Institution Settings'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Institution Settings</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('institution.dashboard')); ?>">
                                <i class="ti ti-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="d-flex align-items-center flex-wrap nav-tab-dark row-gap-2 mb-3" role="tablist">
            <a href="#nav_tab_1" class="btn btn-sm btn-light border active fs-14 me-2" data-bs-toggle="tab" role="tab">Profile Settings</a>
            <a href="#nav_tab_2" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Change Password</a>
        </div>

        <div class="tab-content">
            <!-- Profile Settings Tab -->
            <div class="tab-pane show active" id="nav_tab_1" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Institution Information</h6>
                            </div>
                            <form id="profile-form" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-xxl border border-dashed position-relative me-3 flex-shrink-0 p-2">
                                            <div class="d-felx align-items-center">
                                                <img src="<?php echo e($institution->logo ? asset($institution->logo) : asset('/adminpanel/img/users/avatar-2.jpg')); ?>" 
                                                     class="img-fluid" alt="Institution Logo" id="profile-image-preview" style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            <div class="position-absolute top-0 end-0 m-1">
                                                <a href="javascript:void(0);" class="btn btn-soft-danger rounded-pill avatar-badge border-0 fs-12" 
                                                   onclick="deleteProfileImage()">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-inline-flex flex-column align-items-start">
                                            <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                <i class="ti ti-photo me-1"></i>Change Logo
                                                <input type="file" class="form-control image-sign" id="profile-image-input" accept="image/*">
                                            </div>
                                            <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row row-gap-3">
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Institution Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="name" value="<?php echo e($institution->name ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Email Address<span class="text-danger ms-1">*</span></label>
                                                <input type="email" class="form-control" name="email" value="<?php echo e($institution->email ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Phone Number<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="phone" value="<?php echo e($institution->phone ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Website</label>
                                                <input type="url" class="form-control" name="website" value="<?php echo e($institution->website ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Board<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="board" value="<?php echo e($institution->board ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Established Date<span class="text-danger ms-1">*</span></label>
                                                <input type="date" class="form-control" name="established_date" value="<?php echo e($institution->established_date ? $institution->established_date->format('Y-m-d') : ''); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h6 class="mb-3">Address Information</h6>

                                    <div class="row row-gap-3">
                                        <div class="col-lg-12">
                                            <div class="mb-0">
                                                <label class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                                <textarea class="form-control" name="address" rows="3" required><?php echo e($institution->address ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">State<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="state" value="<?php echo e($institution->state ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">District<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="district" value="<?php echo e($institution->district ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-0">
                                                <label class="form-label">Pin Code<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="<?php echo e($institution->pincode ?? ''); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-white me-2" onclick="resetProfileForm()">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane" id="nav_tab_2" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Change Password</h6>
                            </div>
                            <form id="password-form">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Current Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="current_password" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">New Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="new_password" required minlength="6">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Confirm New Password<span class="text-danger ms-1">*</span></label>
                                                <input type="password" class="form-control" name="new_password_confirmation" required minlength="6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-white me-2" onclick="resetPasswordForm()">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Define URLs for the JavaScript file
const profileUpdateUrl = '<?php echo e(route("institution.settings.profile")); ?>';
const passwordUpdateUrl = '<?php echo e(route("institution.settings.change-password")); ?>';
const deleteImageUrl = '<?php echo e(route("institution.settings.delete-profile-image")); ?>';
const defaultImageUrl = '<?php echo e(asset("/adminpanel/img/users/avatar-2.jpg")); ?>';
</script>
<script src="<?php echo e(asset('custom/js/institution/settings.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/settings/index.blade.php ENDPATH**/ ?>