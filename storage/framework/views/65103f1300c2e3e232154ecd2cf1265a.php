<?php $__env->startSection('title', 'Institution | Exam Invigilator'); ?>
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
                <h5 class="fw-bold">Exam Invigilator</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Invigilator</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Exam Records</h6>
                <form id="exam-filter-form" class="row g-3 align-items-end" method="GET"
                    action="<?php echo e(route('institution.exam-management.invigilator.index')); ?>">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            <?php if(isset($institutions) && count($institutions) > 0): ?>
                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($institution->id); ?>"
                                        <?php echo e(auth()->user()->id == $institution->id ? 'selected' : ''); ?>>
                                        <?php echo e($institution->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option value="">No institutions found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2" id="class-field">
                        <label for="exam-type" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam-type" name="exam_type">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="exam-month" class="form-label">Month</label>
                        <select class="form-select" id="exam-month" name="month">
                            <option value="">Select Month</option>
                            <option value="1" <?php echo e(request('month') == '1' ? 'selected' : ''); ?>>January</option>
                            <option value="2" <?php echo e(request('month') == '2' ? 'selected' : ''); ?>>February</option>
                            <option value="3" <?php echo e(request('month') == '3' ? 'selected' : ''); ?>>March</option>
                            <option value="4" <?php echo e(request('month') == '4' ? 'selected' : ''); ?>>April</option>
                            <option value="5" <?php echo e(request('month') == '5' ? 'selected' : ''); ?>>May</option>
                            <option value="6" <?php echo e(request('month') == '6' ? 'selected' : ''); ?>>June</option>
                            <option value="7" <?php echo e(request('month') == '7' ? 'selected' : ''); ?>>July</option>
                            <option value="8" <?php echo e(request('month') == '8' ? 'selected' : ''); ?>>August</option>
                            <option value="9" <?php echo e(request('month') == '9' ? 'selected' : ''); ?>>September</option>
                            <option value="10" <?php echo e(request('month') == '10' ? 'selected' : ''); ?>>October</option>
                            <option value="11" <?php echo e(request('month') == '11' ? 'selected' : ''); ?>>November</option>
                            <option value="12" <?php echo e(request('month') == '12' ? 'selected' : ''); ?>>December</option>
                        </select>
                    </div>
                    <!-- Class Dropdown (for students) -->
                    

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('institution.exam-management.exams')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-x me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php if(isset($examSchedules) && count($examSchedules) > 0): ?>
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">Exam Rooms & Invigilators</h6>
                    <div class="text-muted">Filtered by: <?php echo e(request('exam_type') ? 'Type #' . request('exam_type') : 'All'); ?>

                        <?php echo e(request('month') ? '| Month ' . request('month') : ''); ?></div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $examSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">
                                <i
                                    class="ti ti-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($schedule['date'])->format('d M, Y')); ?>

                                <span class="text-muted ms-2"><?php echo e($schedule['exam']->title); ?></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">Room</th>
                                            <th style="width: 20%">Capacity</th>
                                            <th style="width: 40%">Assign Teacher</th>
                                            <th style="width: 20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $schedule['rooms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $existing = $schedule['assignments'][$room->id] ?? null;
                                            ?>
                                            <tr>
                                                <td><?php echo e($room->room_no); ?>

                                                    <?php echo e($room->room_name ? ' - ' . $room->room_name : ''); ?></td>
                                                <td><?php echo e($room->capacity ?? 'N/A'); ?></td>
                                                <td>
                                                    <form method="POST"
                                                        action="<?php echo e(route('institution.exam-management.invigilator.assign')); ?>"
                                                        class="d-flex align-items-center gap-2">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="exam_id"
                                                            value="<?php echo e($schedule['exam']->id); ?>">
                                                        <input type="hidden" name="date"
                                                            value="<?php echo e($schedule['date']); ?>">
                                                        <input type="hidden" name="class_room_id"
                                                            value="<?php echo e($room->id); ?>">
                                                        <select name="teacher_id" class="form-select" required>
                                                            <option value="">Select Teacher</option>
                                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($teacher->id); ?>"
                                                                    <?php echo e($existing && $existing->teacher_id == $teacher->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary">
                                                            <?php echo e($existing ? 'Update' : 'Assign'); ?>

                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <?php if($existing): ?>
                                                        <span class="badge bg-success">Assigned</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Unassigned</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php elseif(request()->filled('exam_type') || request()->filled('month')): ?>
            <div class="alert alert-warning">
                <i class="ti ti-alert-triangle me-1"></i>No exams found for the selected filters.
            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/exams.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/examination/invigilator/index.blade.php ENDPATH**/ ?>