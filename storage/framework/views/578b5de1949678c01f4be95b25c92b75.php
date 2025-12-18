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
            <a href="#nav_tab_razorpay" class="btn btn-sm btn-light border fs-14 me-2" data-bs-toggle="tab" role="tab">Razorpay Settings</a>
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

            <!-- Razorpay Settings Tab -->
            <div class="tab-pane" id="nav_tab_razorpay" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card rounded-0 mb-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">RazorpayX Configuration</h6>
                                <p class="text-muted small mb-0">Configure your RazorpayX account for salary payouts</p>
                            </div>
                            <form id="razorpay-form">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="alert alert-info mb-4">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Note:</strong> You need a RazorpayX account (not standard Razorpay) to enable salary payouts. 
                                        <a href="https://razorpay.com/x/" target="_blank">Learn more about RazorpayX</a>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">API Key ID<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control" name="razorpay_key_id" 
                                                    value="<?php echo e($institution->razorpay_key_id ?? ''); ?>" 
                                                    placeholder="rzp_live_xxxxxxxxxx or rzp_test_xxxxxxxxxx">
                                                <small class="text-muted">Your RazorpayX API Key ID from the dashboard</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">API Key Secret<span class="text-danger ms-1">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="razorpay_key_secret" id="razorpay_key_secret"
                                                        value="<?php echo e($institution->razorpay_key_secret ?? ''); ?>" 
                                                        placeholder="Enter API Key Secret">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="toggleSecretVisibility()">
                                                        <i class="ti ti-eye" id="secret-toggle-icon"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted">Your RazorpayX API Key Secret</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Webhook Secret</label>
                                                <input type="text" class="form-control" name="razorpay_webhook_secret" 
                                                    value="<?php echo e($institution->razorpay_webhook_secret ?? ''); ?>" 
                                                    placeholder="Enter Webhook Secret (optional)">
                                                <small class="text-muted">Used to verify webhook signatures for payment status updates</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label">Webhook URL</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly 
                                                value="<?php echo e(url('/webhook/razorpay/' . $institution->id)); ?>" id="webhook-url">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyWebhookUrl()">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Add this URL in your RazorpayX dashboard under Webhooks</small>
                                    </div>

                                    <?php if($institution->razorpay_key_id): ?>
                                    <div class="alert alert-success">
                                        <i class="ti ti-check me-2"></i>
                                        Razorpay is configured. You can now process salary payouts.
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save Razorpay Settings</button>
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
const razorpayUpdateUrl = '<?php echo e(route("institution.settings.razorpay")); ?>';
const defaultImageUrl = '<?php echo e(asset("/adminpanel/img/users/avatar-2.jpg")); ?>';

// Toggle secret visibility
function toggleSecretVisibility() {
    const input = document.getElementById('razorpay_key_secret');
    const icon = document.getElementById('secret-toggle-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
    } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
    }
}

// Copy webhook URL
function copyWebhookUrl() {
    const input = document.getElementById('webhook-url');
    input.select();
    document.execCommand('copy');
    toastr.success('Webhook URL copied to clipboard');
}

// Razorpay form submission
$('#razorpay-form').on('submit', function(e) {
    e.preventDefault();
    
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
    
    $.ajax({
        url: razorpayUpdateUrl,
        type: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to save settings');
        },
        complete: function() {
            btn.prop('disabled', false).html('Save Razorpay Settings');
        }
    });
});
</script>
<script src="<?php echo e(asset('custom/js/institution/settings.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/settings/index.blade.php ENDPATH**/ ?>