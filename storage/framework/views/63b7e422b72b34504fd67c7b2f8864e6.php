
<?php $__env->startSection('title', 'Institution Dashboard | Mark Attendance'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Mark Attendance</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('institution.attendance')); ?>"><i class="ti ti-arrow-left me-1"></i>Attendance Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Mark Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Mark Attendance Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Select Criteria</h6>
                <form id="mark-attendance-form" class="row g-3 align-items-end">
                    <!-- Role Dropdown -->
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field" style="display:none;">
                        <label for="class" class="form-label">Class *</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field" style="display:none;">
                        <label for="section" class="form-label">Section *</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-md-2">
                        <label for="date" class="form-label">Date *</label>
                        <input type="text" class="form-control" id="date" name="date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="<?php echo e(date('d M, Y')); ?>" required>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary w-100" id="load-users-btn">
                            <i class="ti ti-users me-1"></i>Load Users
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List Card -->
        <div class="card" id="users-list" style="display:none;">
            <div class="card-body">
                <h6 class="card-title mb-3" id="users-title">Users List</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>ID/Email</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            <!-- Users will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-success" id="save-attendance-btn" style="display:none;">
                        <i class="ti ti-check me-1"></i>Save Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/mark-attendance.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/administration/attendance/mark-attendance.blade.php ENDPATH**/ ?>