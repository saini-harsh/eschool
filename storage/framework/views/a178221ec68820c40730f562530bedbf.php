<?php $__env->startSection('title', 'Student Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Dashboard</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Welcome back, <?php echo e(Auth::guard('student')->user()->name); ?></span>
        </div>
    </div>
    <!-- End Page Header -->
     

   
</div>
<!-- End Content -->

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any charts or interactive elements
        console.log('Student Dashboard loaded');
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/student/index.blade.php ENDPATH**/ ?>