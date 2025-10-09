<?php $__env->startSection('title', 'Teacher Dashboard | Mark Student Attendance'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Mark Student Attendance</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('teacher.attendance')); ?>"><i class="ti ti-home me-1"></i>Attendance</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Mark Attendance</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('teacher.attendance')); ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Attendance
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Mark Attendance Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Select Class, Section and Date</h6>
                <form id="mark-attendance-form" class="row g-3 align-items-end">
                    <!-- Class Dropdown -->
                    <div class="col-md-3">
                        <label for="class" class="form-label">Class *</label>
                        <select class="form-select" id="class" name="class_id" required>
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Section Dropdown -->
                    <div class="col-md-3">
                        <label for="section" class="form-label">Section *</label>
                        <select class="form-select" id="section" name="section_id" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date *</label>
                        <input type="text" class="form-control" id="date" name="date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="<?php echo e(date('d M, Y')); ?>" required>
                    </div>

                    <!-- Load Students Button -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-users me-1"></i>Load Students
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students List Card -->
        <div class="card" id="students-card" style="display: none;">
            <div class="card-body">
                <h6 class="card-title mb-3" id="students-title">Students List</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;" class="text-center">#</th>
                                <th style="width: 35%;">Student Name</th>
                                <th style="width: 15%;" class="text-center">Roll Number</th>
                                <th style="width: 20%;" class="text-center">Status</th>
                                <th style="width: 25%;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="students-table-body">
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-success" id="save-attendance-btn" style="display: none;">
                        <i class="ti ti-check me-1"></i>Save Attendance
                    </button>
                </div>
            </div>
        </div>

        <!-- Mark My Attendance Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Mark My Attendance</h6>
                <form id="mark-my-attendance-form" class="row g-3 align-items-end">
                    <!-- Date -->
                    <div class="col-md-3">
                        <label for="my-date" class="form-label">Date *</label>
                        <input type="text" class="form-control" id="my-date" name="date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="<?php echo e(date('d M, Y')); ?>" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <label for="my-status" class="form-label">Status *</label>
                        <select class="form-select" id="my-status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>

                    <!-- Remarks -->
                    <div class="col-md-4">
                        <label for="my-remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="my-remarks" name="remarks" placeholder="Optional remarks">
                    </div>

                    <!-- Save Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-user-check me-1"></i>Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/teacher/mark-attendance.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/teacher/administration/attendance/mark-attendance.blade.php ENDPATH**/ ?>