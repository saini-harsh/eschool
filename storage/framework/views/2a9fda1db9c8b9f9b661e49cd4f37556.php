<?php $__env->startSection('title', 'Admin Dashboard | Attendance Management'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Attendance Management</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href=""><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('admin.attendance.mark-page')); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Mark Attendance
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            <?php if(isset($institutions) && count($institutions) > 0): ?>
                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($institution->id); ?>"><?php echo e($institution->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option value="">No institutions found</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field" style="display:none;">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field" style="display:none;">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-2">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control" id="from_date" name="from_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="<?php echo e(date('d M, Y')); ?>">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-2">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="text" class="form-control" id="to_date" name="to_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="<?php echo e(date('d M, Y')); ?>">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3" id="attendance-title">Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="attendance-matrix-table">
                        <thead class="table-light" id="attendance-table-head">
                            <tr>
                                <th colspan="100%" class="text-center">Select filters to view attendance records</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <tr>
                                <td colspan="100%" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No attendance records found</h6>
                                    <p class="text-muted mb-0">Use the filter above to search for attendance records.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/attendance.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/admin/administration/attendance/attendance.blade.php ENDPATH**/ ?>