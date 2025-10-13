<?php $__env->startSection('title', 'Student Dashboard | Attendance Management'); ?>

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
                            <a href="<?php echo e(route('student.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Attendance Statistics Cards -->
        <div class="row mb-4" id="attendance-stats">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Total Days</h6>
                                <h3 class="mb-0 text-white" id="total-days">0</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-calendar text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Present</h6>
                                <h3 class="mb-0 text-white" id="present-days">0</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-check text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Absent</h6>
                                <h3 class="mb-0 text-white" id="absent-days">0</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-x text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white mb-1">Attendance %</h6>
                                <h3 class="mb-0 text-white" id="attendance-percentage">0%</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ti ti-percentage text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Attendance Records</h6>
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- From Date -->
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="text" class="form-control" id="from_date" name="from_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="text" class="form-control" id="to_date" name="to_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Attendance Records Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3" id="attendance-title">My Attendance Records</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="attendance-matrix-table">
                        <thead class="table-light" id="attendance-table-head">
                            <tr>
                                <th colspan="100%" class="text-center">Select date range to view your attendance records</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <tr>
                                <td colspan="100%" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No attendance records found</h6>
                                    <p class="text-muted mb-0">Use the filter above to view your attendance records.</p>
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
    <script src="<?php echo e(asset('custom/js/student/attendance.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/student/attendance/index.blade.php ENDPATH**/ ?>