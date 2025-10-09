<?php $__env->startSection('title', 'Student | Assignments'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">My Assignments</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="<?php echo e(route('student.dashboard')); ?>"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Assignments</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Class: <?php echo e($student->schoolClass->name ?? 'N/A'); ?> - Section: <?php echo e($student->section->name ?? 'N/A'); ?></span>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <?php if($assignments->count() > 0): ?>
            <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0"><?php echo e($assignment->title); ?></h6>
                            <span class="badge bg-<?php echo e($assignment->studentAssignments->first() ? 
                                ($assignment->studentAssignments->first()->status == 'submitted' ? 'success' : 
                                ($assignment->studentAssignments->first()->status == 'late' ? 'warning' : 'info')) : 'secondary'); ?>">
                                <?php echo e($assignment->studentAssignments->first() ? 
                                    ucfirst($assignment->studentAssignments->first()->status) : 'Pending'); ?>

                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Subject:</small>
                                <span class="fw-semibold"><?php echo e($assignment->subject->name ?? 'N/A'); ?></span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Teacher:</small>
                                <span class="fw-semibold"><?php echo e($assignment->teacher->first_name ?? 'N/A'); ?> <?php echo e($assignment->teacher->last_name ?? ''); ?></span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Due Date:</small>
                                <span class="fw-semibold <?php echo e(\Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'text-danger' : 'text-success'); ?>">
                                    <?php echo e(\Carbon\Carbon::parse($assignment->due_date)->format('M d, Y')); ?>

                                </span>
                            </div>
                            <?php if($assignment->description): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Description:</small>
                                    <p class="mb-0 text-muted small"><?php echo e(Str::limit($assignment->description, 100)); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($assignment->studentAssignments->first()): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Submitted:</small>
                                    <span class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($assignment->studentAssignments->first()->submission_date)->format('M d, Y H:i')); ?></span>
                                </div>
                                <?php if($assignment->studentAssignments->first()->marks): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">Marks:</small>
                                        <span class="fw-semibold text-success"><?php echo e($assignment->studentAssignments->first()->marks); ?>/100</span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('student.assignments.show', $assignment->id)); ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="ti ti-eye me-1"></i>View Details
                                </a>
                                <?php if($assignment->assignment_file): ?>
                                    <a href="<?php echo e(route('student.assignments.download-assignment', $assignment->id)); ?>" 
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="ti ti-download me-1"></i>Download
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-file-off fs-48 text-muted"></i>
                        </div>
                        <h5 class="fw-bold">No Assignments Found</h5>
                        <p class="text-muted">You don't have any assignments assigned to your class and section yet.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any assignment-specific JavaScript here
        console.log('Student Assignments page loaded');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/student/assignments/index.blade.php ENDPATH**/ ?>