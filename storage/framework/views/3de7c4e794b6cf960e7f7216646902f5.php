<?php $__env->startSection('title', 'Institution | Add Teacher'); ?>
<?php $__env->startSection('content'); ?>
<?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?> 
    <!-- Start Content -->
    <div class="content">

        <!-- start row -->
        <div class="row">

            <div class="col-lg-10 mx-auto">
                <div>
                    <h6 class="mb-3 fs-14"><a href="<?php echo e(route('institution.teachers.index')); ?>"><i class="ti ti-arrow-left me-1"></i>Teachers</a></h6>
                    <form action="<?php echo e(route('institution.teachers.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> 
                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Basic Details</h6>
                            </div> <!-- end card header -->
                            <div class="card-body">

                            <div>
                                <label class="form-label">Image</label>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                        <i class="ti ti-photo text-primary"></i>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="ti ti-photo me-1"></i>Upload Image
                                            <input type="file" name="profile_image" class="form-control image-sign" multiple="">
                                        </div>
                                        <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- start row -->
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control">
                                    </div>
                                </div>

                                <!-- Middle Name (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>

                                <!-- Phone (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>

                                <!-- DOB using your existing flatpickr setup -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <div class="input-group w-auto input-group-flat">
                                            <input type="text" name="dob" class="form-control" data-provider="flatpickr"
                                                data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control">
                                    </div>
                                </div>

                                <!-- Pincode (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" class="form-control">
                                    </div>
                                </div>

                                <!-- Gender (new field, uses your `select` class) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="select" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Caste / Tribe (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Caste / Tribe</label>
                                        <input type="text" name="caste_tribe" class="form-control">
                                    </div>
                                </div>

                                <!-- Institution is automatically set based on logged-in user -->
                                <input type="hidden" name="institution_id" value="<?php echo e($institution->id); ?>">

                                <!-- Password (new field) -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>


                                <!-- Already existing invite checkbox -->
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label fw-normal" for="customCheck1">Send them an
                                            invite email so they can log in immediately</label>
                                    </div>
                                </div>

                            </div>
                            <!-- end row -->

                        </div>

                             <!-- end card body -->
                        </div> <!-- end card -->

                       
                        <!-- Salary & Bank Details Card -->
                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Salary & Bank Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Monthly Salary (₹)</label>
                                            <input type="number" step="0.01" name="salary" class="form-control" placeholder="e.g., 25000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Salary Structure</label>
                                            <select name="salary_structure_id" class="form-select">
                                                <option value="">Select Structure (Optional)</option>
                                                <?php if(isset($salaryStructures)): ?>
                                                    <?php $__currentLoopData = $salaryStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $structure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($structure->id); ?>"><?php echo e($structure->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-3">
                                <h6 class="mb-3">Bank Account Details</h6>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" name="bank_name" class="form-control" placeholder="e.g., State Bank of India">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Bank Branch</label>
                                            <input type="text" name="bank_branch" class="form-control" placeholder="e.g., Main Branch">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" name="bank_account_number" class="form-control" placeholder="Enter account number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">IFSC Code</label>
                                            <input type="text" name="bank_ifsc_code" class="form-control" placeholder="e.g., SBIN0001234">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Salary & Bank Details Card -->

                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-light me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Teacher</button>
                        </div>

                    </form> <!-- end form -->

                </div>
            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/teachers.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/teachers/create.blade.php ENDPATH**/ ?>